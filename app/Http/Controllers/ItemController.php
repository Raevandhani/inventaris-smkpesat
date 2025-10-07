<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Items;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // "can" is not a bug
        if (!Auth::user()->can('items.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $query = Items::with('category');
        $categories = Category::all();

        if ($request->has('search') && $request->has('sort')) {
            if (trim($request->query('search', '')) === '' && trim($request->query('sort', '')) === '') {
                return redirect()->route('items.index');
            }
        }

        if($request->filled('search')) {
            $search = $request->search;
            $query->where(function($x) use ($search) {
                $x->where('status', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhereHas('category', fn($y) => $y->where('name', 'like', "%{$search}%"));
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
                    $query->orderBy('total_stock', 'desc');
                    break;
                case 'smallest':
                    $query->orderBy('total_stock', 'asc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $edit = null;
        if($request->has('edit')) {
            $edit = Items::find($request->query('edit'));
        }

        $n = 6;

        $items = $query->paginate($n)->appends($request->all());

        $breadcrumbs = [
            ['label' => 'Items']
        ];
        return view('dashboard.items.index', compact('items','breadcrumbs','categories','edit','n'));
    }

    public function store(Request $request)
    {
        // "can" is not a bug
        if (!Auth::user()->can('items.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $request->validate([
            "name" => "required|unique:items,name",
            "category_id" => "required",
            "total_stock" => "required|integer|min:0",
            "status" => "nullable",
        ],[
            'name.unique' => 'This item has already exist',
            'category_id.required' => 'Category field is required',
        ]);

        $data = $request->only([
            'name',
            'category_id',
            'total_stock',
            'status'
        ]);

        Items::create($data);

        return redirect('items')->with('success', 'Item created successfully: "'.$request->name.'"');
    }

    public function update(Request $request, string $id)
    {
        // "can" is not a bug
        if (!Auth::user()->can('items.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $request->validate([
            "name" => [
                "required",
                Rule::unique('items', 'name')->ignore($id),
            ],
            "category_id" => "required",
            "total_stock" => "required|integer|min:0",
            "status" => "nullable",
        ]);

        $items = Items::findOrFail($id);

        $currentUnavailable = $items->borrowed + $items->maintenance + $items->others;

        $newTotal = (int) $request->input('total_stock');

        if ($newTotal < $currentUnavailable) {
            return back()->withErrors([
                'error' => "Total stock cannot be less than ($currentUnavailable) unavailable items."
            ])->withInput();
        }

        $items->total_stock = $newTotal;
        $items->fill($request->except('total_stock'));

        $items->save();

        return redirect('items')->with('success', 'Item updated successfully: "'.$request->name.'"');
    }

    public function destroy(string $id)
    {
        // "can" is not a bug
        if(!Auth::user()->can('items.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $items = Items::findorFail($id);

        $unavailable = $items->maintenance + $items->borrowed + $items->others;

        if($unavailable > 0){
            return back()->withErrors([
                'error' => "These items are currently being used or in repair."
            ])->withInput();
        }

        $items->delete();

        return redirect('items')->with('deleted', 'Item deleted successfully: "'.$items->name.'"');
    }

    public function exportPdf(Request $request)
    {
        // "can" is not a bug
        if (!Auth::user()->can('items.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $date1 = $request->date1;
        $date2 = $request->date2;

        $items = Items::with('category')
                ->when($date1, fn($q) => $q->whereDate('created_at', '>=', $date1))
                ->when($date2, fn($q) => $q->whereDate('created_at', '<=', $date2))
                ->get();

        $pdf = Pdf::loadView('exports.item', compact('items'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('item_report.pdf');
    }

    public function exportExcel(Request $request)
    {
        // "can" is not a bug
        if (!Auth::user()->can('items.manage')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        $date1 = $request->date1;
        $date2 = $request->date2;

        $items = Items::with('category')
                ->when($date1, fn($q) => $q->whereDate('created_at', '>=', $date1))
                ->when($date2, fn($q) => $q->whereDate('created_at', '<=', $date2))
                ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Items Report');

        $sheet->setCellValue('B2', '#');
        $sheet->setCellValue('C2', 'Category');
        $sheet->setCellValue('D2', 'Item');
        $sheet->setCellValue('E2', 'Available');
        $sheet->setCellValue('F2', 'Borrowed');
        $sheet->setCellValue('G2', 'Maintenance');
        $sheet->setCellValue('H2', 'Other');
        $sheet->setCellValue('I2', 'Total Stock');

        $sheet->getStyle('B2:I2')->getFont()->setBold(true);

        foreach (range('B', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $no = 1;
        $row = 3;

        foreach ($items as $data) {
            $sheet->setCellValue("B{$row}", $no++);
            $sheet->setCellValue("C{$row}", $data->category?->name);
            $sheet->setCellValue("D{$row}", $data->name);
            $sheet->setCellValue("E{$row}", $data->available);
            $sheet->setCellValue("F{$row}", $data->borrowed);
            $sheet->setCellValue("G{$row}", $data->maintenance);
            $sheet->setCellValue("H{$row}", $data->others);
            $sheet->setCellValue("I{$row}", $data->total_stock);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = "items_report.xlsx";

        $response = new StreamedResponse(function() use($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', "attachment;filename=\"{$fileName}\"");
        $response->headers->set('Cache-Control','max-age=0');

        return $response;
    }

    // Unused
    public function create(){
        return redirect()->route('categories.index');
    }
    public function show(){
        return redirect()->route('categories.index');
    }
    public function edit(){
        return redirect()->route('categories.index');
    }
}
