<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Maintenance;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\StreamedResponse;


class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with('item');
        $items = Items::whereRaw('total_stock - borrowed - maintenance - others > 0')
              ->where('status', true)
              ->get();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($x) use ($search) {
                $x->where('status', 'like', "%{$search}%")
                  ->orWhereHas('item', fn($y) => $y->where('name', 'like', "%{$search}%"))
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('start_at', 'like', "%{$search}%")
                  ->orWhere('finish_at', 'like', "%{$search}%")
                  ->orWhere('quantity', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->orderBy('id', 'asc');
                    break;
                case 'latest':
                    $query->orderBy('id', 'desc');
                    break;
                case 'largest':
                    $query->orderBy('quantity', 'desc');
                    break;
                case 'smallest':
                    $query->orderBy('quantity', 'asc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $edit = null;
        if($request->has('edit')) {
            $edit = Maintenance::find($request->query('edit'));
        }

        $maintains = $query->paginate(20)->appends($request->all());

        $breadcrumbs = [
            ['label' => 'Maintenance']
        ];

        return view('dashboard.maintenance.index', compact('maintains','breadcrumbs','edit','items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "item_id"     => "required|exists:items,id",
            "quantity"    => "required|integer|min:1",
            "notes"    => "nullable|string",
            "start_at" => "required|date",
        ]);

        $items = Items::findOrFail($request->item_id);

        if ($request->quantity > $items->available) {
            return back()
                ->withErrors([
                    'quantity' => "Only {$items->available} item(s) are available right now.",
                ])
                ->withInput();
        }

        $items->increment('maintenance', $request->quantity);

        $data = $request->all();
        Maintenance::create($data);

        return redirect('maintains');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            "notes"    => "nullable|string",
            "quantity" => "required|integer|min:1",
        ]);

        $maintains = Maintenance::findOrFail($id);
        $items     = $maintains->item;

        $inputQuantity = (int) $request->input('quantity');
        $diff = $inputQuantity - $maintains->quantity;

        if ($diff > 0 && $diff > $items->available) {
            return back()->withErrors([
                'quantity' => "Only {$items->available} item(s) are available right now."
            ])->withInput();
        }

        $maintains->update([
            'notes'    => $request->input('notes'),
            'quantity' => $inputQuantity,
        ]);

        if ($diff > 0) {
            $items->increment('maintenance', $diff);
        } elseif ($diff < 0) {
            $items->decrement('maintenance', -$diff);
        }

        return redirect('maintains');
    }

    public function finished(Request $request, string $id)
    {
        $maintains = Maintenance::findOrFail($id);
        $items = $maintains->item;

        $maintains->update([
            'status' => true,
            'finish_at' => now(),
        ]);

        $items->decrement('maintenance', $maintains->quantity);

        return redirect('maintains');
    }

    public function destroy(string $id)
    {
        $maintains = Maintenance::findorFail($id);
        $maintains->delete();

        return redirect('maintains');
    }

    public function exportPdf(Request $request)
    {
        $date1 = $request->date1;
        $date2 = $request->date2;

        $maintains = Maintenance::where('status', true)
                ->when($date1, function ($query, $date1) {
                    $query->where(function ($q) use ($date1) {
                        $q->whereDate('start_at', '>=', $date1)
                          ->orWhereDate('finish_at', '>=', $date1);
                    });
                })
                ->when($date2, function ($query, $date2) {
                    $query->where(function ($q) use ($date2) {
                        $q->whereDate('start_at', '<=', $date2)
                          ->orWhereDate('finish_at', '<=', $date2);
                    });
                })
                ->get();

        $pdf = Pdf::loadView('exports.maintenance', compact('maintains'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('maintenace_report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $date1 = $request->date1;
        $date2 = $request->date2;

        $maintains = Maintenance::where('status', true)
                ->when($date1, function ($query, $date1) {
                    $query->where(function ($q) use ($date1) {
                        $q->whereDate('start_at', '>=', $date1)
                          ->orWhereDate('finish_at', '>=', $date1);
                    });
                })
                ->when($date2, function ($query, $date2) {
                    $query->where(function ($q) use ($date2) {
                        $q->whereDate('start_at', '<=', $date2)
                          ->orWhereDate('finish_at', '<=', $date2);
                    });
                })
                ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Items Report');

        $sheet->setCellValue('B2', '#');
        $sheet->setCellValue('C2', 'Item');
        $sheet->setCellValue('D2', 'Quantity');
        $sheet->setCellValue('E2', 'Notes');
        $sheet->setCellValue('F2', 'Start At');
        $sheet->setCellValue('G2', 'Finish At');

        $sheet->getStyle('B2:I2')->getFont()->setBold(true);

        foreach (range('B', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $no = 1;
        $row = 3;

        foreach ($maintains as $data) {
            $sheet->setCellValue("B{$row}", $no++);
            $sheet->setCellValue("C{$row}", $data->item?->name);
            $sheet->setCellValue("D{$row}", $data->quantity);
            $sheet->setCellValue("E{$row}", $data->notes ?? '-');
            $sheet->setCellValue("F{$row}", $data->start_at->timezone('Asia/Jakarta')->format('d F Y - H:i'));
            $sheet->setCellValue("G{$row}", $data->finish_at->timezone('Asia/Jakarta')->format('d F Y - H:i'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = "maintenance_report.xlsx";

        $response = new StreamedResponse(function() use($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', "attachment;filename=\"{$fileName}\"");
        $response->headers->set('Cache-Control','max-age=0');

        return $response;
    }
}
