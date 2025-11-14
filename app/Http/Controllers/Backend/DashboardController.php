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
use Illuminate\Support\Facades\DB; // تأكد من وجود هذا السطر

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. تحديد الفلتر الزمني (الافتراضي: آخر 7 أيام)
        $filterType = $request->query('filter', 'last_7_days');
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        switch ($filterType) {
            case 'today':
                $startDate = now()->startOfDay();
                break;
            case 'last_5_days':
                $startDate = now()->subDays(4)->startOfDay();
                break;
            case 'last_7_days':
                // هو الافتراضي
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfDay();
                break;
            case 'all_time':
                $startDate = null; // سيتم تجاهله
                break;
            case 'custom':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }

        // 2. بناء استعلام الطلبات الرئيسي بناءً على الفلتر
        $ordersQuery = Order::query();
        if ($filterType !== 'all_time' && $startDate) {
            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        // استخدام clone() للحصول على النتائج بدون التأثير على الاستعلام الأصلي
        $orders = $ordersQuery->clone()->get();

        // 3. حساب إحصائيات المربعات العلوية من الطلبات المفلترة
        $data = [
            'sub_total' => $orders->sum('sub_total'),
            'discount' => $orders->sum('discount'),
            'total' => $orders->sum('total'),
            'paid' => $orders->sum('paid'),
            'due' => $orders->sum('due'),
            'total_order' => $orders->count(), // عدد المبيعات (الفواتير)
        ];

        // 4. حساب إحصائيات المربعات الأخرى (بعضها لا يتأثر بالفلتر)
        $data['total_customer'] = Customer::count(); // إجمالي العملاء دائماً
        $data['total_product'] = Product::count(); // إجمالي المنتجات دائماً

        // حساب "عنصر مبيع" بناءً على الفلتر
        $orderIds = $orders->pluck('id');
        $data['total_sale_item'] = OrderProduct::whereIn('order_id', $orderIds)->sum('quantity');

        // 5. إعداد بيانات المخططات
        // مخطط المبيعات اليومية (يستخدم نفس الفلتر الزمني)
        $dailyTotalsQuery = OrderTransaction::query();
        if ($filterType !== 'all_time' && $startDate) {
            $dailyTotalsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $dailyTotals = $dailyTotalsQuery->selectRaw('DATE(created_at) as date, SUM(amount) as total_amount')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $data['dates'] = $dailyTotals->pluck('date')->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))->toArray();
        $data['totalAmounts'] = $dailyTotals->pluck('total_amount')->toArray();
        $data['dateRange'] = $filterType !== 'all_time' ? $startDate->format('M d, Y') . ' to ' . $endDate->format('M d, Y') : 'كل الوقت';

        // مخطط المبيعات الشهرية (يعرض دائماً السنة الحالية)
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

        // 6. تمرير كل البيانات إلى الـ view
        return view('backend.index', array_merge($data, [
            'filterType' => $filterType,
            'startDate' => $filterType !== 'all_time' ? $startDate->format('Y-m-d') : '',
            'endDate' => $filterType !== 'all_time' ? $endDate->format('Y-m-d') : '',
        ]));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('backend.profile.index', compact('user'));
    }
}
