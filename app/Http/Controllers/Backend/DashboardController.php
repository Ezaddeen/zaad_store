<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderTransaction;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ⬅️ أضف هذا السطر

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ==================================================
        // ⬇️ الكود المحسن لحساب الإحصائيات العامة ⬇️
        // ==================================================
        $orderTotals = Order::select(
            DB::raw('SUM(sub_total) as sub_total'),
            DB::raw('SUM(discount) as discount'),
            DB::raw('SUM(total) as total'),
            DB::raw('SUM(paid) as paid'),
            DB::raw('SUM(due) as due')
        )->first();

        $data = [
            'sub_total' => $orderTotals->sub_total ?? 0,
            'discount' => $orderTotals->discount ?? 0,
            'total' => $orderTotals->total ?? 0,
            'paid' => $orderTotals->paid ?? 0,
            'due' => $orderTotals->due ?? 0,
            'total_customer' => Customer::count(),
            'total_order' => Order::count(), // أسرع من جلب الكل ثم العد
            'total_product' => Product::count(),
            'total_sale_item' => OrderProduct::sum('quantity'),
        ];

        // --- بقية الكود يبقى كما هو ---

        $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        if($request->has('daterange')) {
            $dates = explode(' to ', $request->query('daterange'));
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->format('Y-m-d');
                $endDate = Carbon::parse($dates[1])->format('Y-m-d');
            }
        }
        $dailyTotals = OrderTransaction::selectRaw('DATE(created_at) as date, SUM(amount) as total_amount')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get();
        $dates = $dailyTotals->pluck('date')->toArray();
        $totalAmounts = $dailyTotals->pluck('total_amount')->toArray();
        $data['dates'] = $dates;
        $data['totalAmounts'] = $totalAmounts;
        $data['dateRange'] = 'from '. $startDate . ' to ' . $endDate;

        $currentYear = now()->year;
        $data['currentYear'] = $currentYear;

        $salesData = OrderTransaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total_amount')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month', 'ASC')->pluck('total_amount', 'month')->toArray();
        $tempMonths = [];
        $tempTotalAmountMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthKey = Carbon::create($currentYear, $i, 1)->format('Y-m');
            $tempMonths[] = $monthKey;
            $tempTotalAmountMonth[] = $salesData[$monthKey] ?? 0;
        }

        $data['months'] = $tempMonths;
        $data['totalAmountMonth'] = $tempTotalAmountMonth;

        return view('backend.index', $data);
    }

    public function profile()
    {
        $user = auth()->user();
        return view('backend.profile.index', compact('user'));
    }
}
