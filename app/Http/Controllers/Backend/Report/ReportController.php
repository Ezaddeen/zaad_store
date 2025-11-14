<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function saleReport(Request $request)
    {
        abort_if(!auth()->user()->can('reports_sales'), 403);
        $start_date = Carbon::parse($request->input('start_date', now()->subDays(29)))->startOfDay();
        $end_date = Carbon::parse($request->input('end_date', now()))->endOfDay();

        $orders = Order::whereBetween('created_at', [$start_date, $end_date])->with('customer')->get();

        $totals = Order::whereBetween('created_at', [$start_date, $end_date])
            ->select(
                DB::raw('SUM(sub_total) as sub_total'),
                DB::raw('SUM(discount) as discount'),
                DB::raw('SUM(paid) as paid'),
                DB::raw('SUM(due) as due'),
                DB::raw('SUM(total) as total')
            )->first();

        $data = [
            'orders' => $orders,
            'sub_total' => $totals->sub_total ?? 0,
            'discount' => $totals->discount ?? 0,
            'paid' => $totals->paid ?? 0,
            'due' => $totals->due ?? 0,
            'total' => $totals->total ?? 0,
            'start_date' => $start_date->format('M d, Y'),
            'end_date' => $end_date->format('M d, Y'),
        ];

        return view('backend.reports.sale-report', $data);
    }

    public function saleSummery(Request $request)
    {
        abort_if(!auth()->user()->can('reports_summary'), 403);
        $start_date = Carbon::parse($request->input('start_date', now()->subDays(29)))->startOfDay();
        $end_date = Carbon::parse($request->input('end_date', now()))->endOfDay();

        $totals = Order::whereBetween('created_at', [$start_date, $end_date])
            ->select(
                DB::raw('SUM(sub_total) as sub_total'),
                DB::raw('SUM(discount) as discount'),
                DB::raw('SUM(paid) as paid'),
                DB::raw('SUM(due) as due'),
                DB::raw('SUM(total) as total')
            )->first();

        $data = [
            'sub_total' => $totals->sub_total ?? 0,
            'discount' => $totals->discount ?? 0,
            'paid' => $totals->paid ?? 0,
            'due' => $totals->due ?? 0,
            'total' => $totals->total ?? 0,
            'start_date' => $start_date->format('M d, Y'),
            'end_date' => $end_date->format('M d, Y'),
        ];

        return view('backend.reports.sale-summery', $data);
    }

    // ==================================================
    // ⬇️ هذه هي الدالة الصحيحة لتقرير المخزون ⬇️
    // ==================================================
    function inventoryReport(Request $request)
    {
        abort_if(!auth()->user()->can('reports_inventory'), 403);

        if ($request->ajax()) {
            $products = Product::latest()->active()->get();
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('sku', fn($data) => $data->sku)
                ->addColumn(
                    'price',
                    fn($data) => $data->discounted_price .
                        ($data->price > $data->discounted_price
                            ? '  
<del>' . $data->price . '</del>'
                            : '')
                )
                ->addColumn('quantity', fn($data) => $data->quantity . ' ' . optional($data->unit)->short_name)
                ->rawColumns(['price']) // تم إضافة 'price' هنا
                ->toJson();
        }

        return view('backend.reports.inventory');
    }

    public function profitReport(Request $request)
    {
        abort_if(!auth()->user()->can('reports_profit'), 403);

        $filterType = $request->query('filter', 'last_7_days');
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        switch ($filterType) {
            case 'last_5_days': $startDate = now()->subDays(4)->startOfDay(); break;
            case 'last_month': $startDate = now()->subMonth()->startOfDay(); break;
            case 'custom':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }

        $query = OrderProduct::whereBetween('created_at', [$startDate, $endDate]);

        $totalSold = $query->clone()->sum(DB::raw('price * quantity'));
        $totalPurchaseCost = $query->clone()->sum(DB::raw('purchase_price * quantity'));
        $totalProfit = $totalSold - $totalPurchaseCost;

        $detailedProfits = $query->clone()->with('product')->latest()->paginate(25);

        return view('backend.reports.profit_report', [
            'detailedProfits' => $detailedProfits,
            'totalSold' => $totalSold,
            'totalPurchaseCost' => $totalPurchaseCost,
            'totalProfit' => $totalProfit,
            'filterType' => $filterType,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }
}
