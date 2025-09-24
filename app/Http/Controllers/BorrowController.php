<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Items;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        // "can" is not a bug
        if (!Auth::user()->can('borrow.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $query = Borrow::with(['item', 'location', 'user']);
        $items = Items::whereRaw('total_stock - borrowed - maintenance - others > 0')
              ->where('status', true)
              ->get();
        $locations = Location::get();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($x) use ($search) {
                $x->where('status', 'like', "%{$search}%")
                  ->orWhereHas('item', fn($y) => $y->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('user', fn($y) => $y->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('location', fn($y) => $y->where('name', 'like', "%{$search}%"));
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
            $edit = Borrow::find($request->query('edit'));
        }

        $borrows = $query->paginate(20)->appends($request->all());

        $breadcrumbs = [
            ['label' => 'Borrow']
        ];

        return view('dashboard.borrow.index', compact('borrows','breadcrumbs','edit','items', 'locations'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('borrow.manage') && !Auth::user()->can('borrow.request')) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            "user_id"     => "nullable",
            "item_id"     => "required|exists:items,id",
            "location_id" => "required",
            "quantity"    => "required|integer|min:1",
            "borrow_date" => "required|date",
            "return_date" => "nullable|date|after_or_equal:borrow_date",
            "status"      => "nullable",
        ]);

        $items = Items::findOrFail($request->item_id);

        if ($request->quantity > $items->available) {
            return back()->withErrors([
                'quantity' => "Only {$items->available} item(s) are available right now.",
            ])->withInput();
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        Borrow::create($data);

        return $request->redirect === 'dashboard'
            ? redirect()->route('dashboard')
            : redirect()->route('borrows.index');
    }


    public function update(Request $request, string $id)
    {
        // "can" is not a bug
        if (!Auth::user()->can('borrow.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $request->validate([
            "location_id" => "required",
            "quantity" => "required|integer|min:1",
        ]);

        $borrow = Borrow::findOrFail($id);
        $items = $borrow->item;

        $inputQuantity = (int) $request->input('quantity');
        $diff = $inputQuantity - $borrow->quantity;

        if ($diff > 0 && $diff > $items->available) {
            return back()->withErrors([
                'quantity' => "Only {$items->available} item(s) are available right now."
            ])->withInput();
        }

        $borrow->update([
            'quantity' => $inputQuantity,
            'location_id' => $request->location_id,
        ]);

        if ($diff > 0) {
            $items->increment('borrowed', $diff);
        } elseif ($diff < 0) {
            $items->decrement('borrowed', -$diff);
        }

        return redirect('borrows');
    }

    public function finished(Request $request, string $id)
    {
        // "can" is not a bug
        if (!Auth::user()->can('borrow.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $borrow = Borrow::findOrFail($id);
        $items = $borrow->item;

        $borrow->update([
            'status' => 'done',
            'return_date' => now(),
        ]);

        $items->decrement('borrowed', $borrow->quantity);

        return redirect('borrows');
    }

    public function accepted(Request $request, string $id)
    {
        // "can" is not a bug
        if (!Auth::user()->can('borrow.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $borrow = Borrow::findOrFail($id);
        $items = $borrow->item;

        $borrow->update([
            'status' => 'ongoing',
        ]);

        $items->increment('borrowed', $borrow->quantity);

        return redirect('borrows');
    }

    public function declined(Request $request, string $id)
    {
        // "can" is not a bug
        if (!Auth::user()->can('borrow.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $borrow = Borrow::findOrFail($id);

        $borrow->update([
            'status' => 'declined',
        ]);

        return redirect('borrows');
    }

    public function destroy(string $id)
    {
        if (!Auth::user()->can('borrow.request')) {
            return redirect()->route('dashboard');
        }

        $borrows = Borrow::findorFail($id);
        $borrows->delete();

        return redirect('borrows');
    }

    public function exportPdf(Request $request)
    {
        // "can" is not a bug
        if (!Auth::user()->can('borrow.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $date1 = $request->date1;
        $date2 = $request->date2;

        $borrows = Borrow::where('status', 'done')
                ->when($date1, function ($query, $date1) {
                    $query->where(function ($q) use ($date1) {
                        $q->whereDate('borrow_date', '>=', $date1)
                          ->orWhereDate('return_date', '>=', $date1);
                    });
                })
                ->when($date2, function ($query, $date2) {
                    $query->where(function ($q) use ($date2) {
                        $q->whereDate('borrow_date', '<=', $date2)
                          ->orWhereDate('return_date', '<=', $date2);
                    });
                })
                ->get();

        $pdf = Pdf::loadView('exports.borrow', compact('borrows'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('BorrowReport.pdf');
    }

    public function exportExcel(Request $request)
    {
        // "can" is not a bug
        if (!Auth::user()->can('borrow.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        $date1 = $request->date1;
        $date2 = $request->date2;

        $borrows = Borrow::where('status', 'done')
                ->when($date1, function ($query, $date1) {
                    $query->where(function ($q) use ($date1) {
                        $q->whereDate('borrow_date', '>=', $date1)
                          ->orWhereDate('return_date', '>=', $date1);
                    });
                })
                ->when($date2, function ($query, $date2) {
                    $query->where(function ($q) use ($date2) {
                        $q->whereDate('borrow_date', '<=', $date2)
                          ->orWhereDate('return_date', '<=', $date2);
                    });
                })
                ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Borrows Report');

        $sheet->setCellValue('B2', '#');
        $sheet->setCellValue('C2', 'User');
        $sheet->setCellValue('D2', 'Item');
        $sheet->setCellValue('E2', 'Location');
        $sheet->setCellValue('F2', 'Quantity');
        $sheet->setCellValue('G2', 'Borrowed At');
        $sheet->setCellValue('H2', 'Return At');

        $sheet->getStyle('B2:H2')->getFont()->setBold(true);

        foreach (range('B', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $no = 1;
        $row = 3;

        foreach ($borrows as $data) {
            $sheet->setCellValue("B{$row}", $no++);
            $sheet->setCellValue("C{$row}", $data->user?->name);
            $sheet->setCellValue("D{$row}", $data->item?->name);
            $sheet->setCellValue("E{$row}", $data->location?->name);
            $sheet->setCellValue("F{$row}", $data->quantity);
            $sheet->setCellValue("G{$row}", $data->borrow_date->timezone('Asia/Jakarta')->format('d F Y - H:i'));
            $sheet->setCellValue("H{$row}", $data->return_date->timezone('Asia/Jakarta')->format('d F Y - H:i'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = "borrows_report.xlsx";

        $response = new StreamedResponse(function() use($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', "attachment;filename=\"{$fileName}\"");
        $response->headers->set('Cache-Control','max-age=0');

        return $response;
    }
}
