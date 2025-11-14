<?php

namespace App\Http\Controllers\Backend\Pos;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\PosCart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ⬅️ 1. تم إضافة هذا السطر
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // ⬇️ 2. تم تعديل هذه الدالة بالكامل ⬇️
            $orders = Order::with('customer')->latest()->get(); // استخدام latest() للترتيب
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('checkbox', function($row){
                    return '<input type="checkbox" class="order-checkbox" value="'.$row->id.'">';
                })
                ->addColumn('saleId', fn($data) => "#" . $data->id)
                ->addColumn('customer', fn($data) => $data->customer->name ?? '-')
                ->addColumn('item', fn($data) => $data->total_item)
                ->addColumn('sub_total', fn($data) => number_format($data->sub_total, 2, '.', ','))
                ->addColumn('discount', fn($data) => number_format($data->discount, 2, '.', ','))
                ->addColumn('total', fn($data) => number_format($data->total, 2, '.', ','))
                ->addColumn('paid', fn($data) => number_format($data->paid, 2, '.', ','))
                ->addColumn('due', fn($data) => number_format($data->due, 2, '.', ','))
                ->addColumn('status', fn($data) => $data->status
                    ? '<span class="badge bg-primary">مدفوع</span>'
                    : '<span class="badge bg-danger">مستحق</span>')
                ->addColumn('action', function ($data) {
                    $buttons = '';
                    $buttons .= '<a class="btn btn-success btn-sm" href="' . route('backend.admin.orders.invoice', $data->id) . '"><i class="fas fa-file-invoice"></i> فاتورة</a>';
                    $buttons .= ' <a class="btn btn-secondary btn-sm" href="' . route('backend.admin.orders.pos-invoice', $data->id) . '"><i class="fas fa-file-invoice"></i> فاتورة POS</a>';
                    if (!$data->status) {
                        $buttons .= ' <a class="btn btn-warning btn-sm" href="' . route('backend.admin.due.collection', $data->id) . '"><i class="fas fa-receipt"></i> تحصيل المستحق</a>';
                    }
                    $buttons .= ' <a class="btn btn-primary btn-sm" href="' . route('backend.admin.orders.transactions', $data->id) . '"><i class="fas fa-exchange-alt"></i> المعاملات</a>';

                    // زر الحذف الفردي
                    if (auth()->user()->can('sale_delete')) {
                        $deleteRoute = route('backend.admin.orders.destroy', $data->id);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');
                        $buttons .= "
                            <form action='{$deleteRoute}' method='POST' style='display:inline-block; margin-right: 5px;' onsubmit=\"return confirm('هل أنت متأكد؟ سيتم إعادة المنتجات إلى المخزون.');\">
                                {$csrf}
                                {$method}
                                <button type='submit' class='btn btn-danger btn-sm'>
                                    <i class='fas fa-trash'></i>
                                </button>
                            </form>
                        ";
                    }
                    return $buttons;
                })
                ->rawColumns(['status', 'action', 'checkbox'])
                ->toJson();
        }
        return view('backend.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // الكود الأصلي بدون تغيير في هذه الدالة
        $request->validate([
            'customer_id' => ['required', 'exists:customers,id', 'integer'],
            'order_discount' => ['nullable', 'numeric', 'min:0'],
            'paid' => ['nullable', 'numeric', 'min:0'],
        ], [
            'customer_id.required' => 'Please select a customer.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'order_discount.numeric' => 'The order discount must be a number.',
            'paid.numeric' => 'The amount paid must be a number.',
        ]);
        $carts = PosCart::with('product')->where('user_id', auth()->id())->get();
        $order = Order::create(['customer_id' => $request->customer_id, 'user_id' => $request->user()->id]);
        $totalAmountOrder = 0;
        $orderDiscount = $request->order_discount;
        foreach ($carts as $cart) {
            $mainTotal = $cart->product->price * $cart->quantity;
            $totalAfterDiscount = $cart->product->discounted_price * $cart->quantity;
            $discount = $mainTotal - $totalAfterDiscount;
            $totalAmountOrder += $totalAfterDiscount;
            $order->products()->create([
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
                'purchase_price' => $cart->product->purchase_price,
                'sub_total' => $mainTotal,
                'discount' => $discount,
                'total' => $totalAfterDiscount,
                'product_id' => $cart->product->id,
            ]);
            $cart->product->quantity = $cart->product->quantity - $cart->quantity;
            $cart->product->save();
        }
        $total = $totalAmountOrder - $orderDiscount;
        $due = $total - $request->paid;
        $order->sub_total = $totalAmountOrder;
        $order->discount = $orderDiscount;
        $order->paid = $request->paid;
        $order->total = round((float)$total, 2);
        $order->due = round((float)$due, 2);
        $order->status = round((float)$due, 2) <= 0;
        $order->save();
        if ($request->paid > 0) {
            $order->transactions()->create([
                'amount' => $request->paid,
                'customer_id' => $order->customer_id,
                'user_id' => auth()->id(),
                'paid_by' => 'cash',
            ]);
        }
        PosCart::where('user_id', auth()->id())->delete();
        return response()->json(['message' => 'Order completed successfully', 'order' => $order], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    // ⬇️ 3. تم ملء هذه الدالة وإضافة دالة الحذف الجماعي ⬇️
    public function destroy(string $id)
    {
        abort_if(!auth()->user()->can('sale_delete'), 403, 'ليس لديك الصلاحية لحذف المبيعات.');

        DB::beginTransaction();
        try {
            $order = Order::with('products')->findOrFail($id);
            foreach ($order->products as $orderProduct) {
                Product::find($orderProduct->product_id)->increment('quantity', $orderProduct->quantity);
            }
            $order->delete();
            DB::commit();
            return redirect()->route('backend.admin.orders.index')->with('success', 'تم حذف الفاتورة بنجاح وإعادة المنتجات إلى المخزون.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        abort_if(!auth()->user()->can('sale_delete'), 403);

        $request->validate(['ids' => 'required|array']);
        $ids = $request->input('ids');

        DB::beginTransaction();
        try {
            $orders = Order::whereIn('id', $ids)->with('products')->get();
            foreach ($orders as $order) {
                foreach ($order->products as $orderProduct) {
                    Product::find($orderProduct->product_id)->increment('quantity', $orderProduct->quantity);
                }
                $order->delete();
            }
            DB::commit();
            return response()->json(['message' => 'تم حذف الفواتير المحددة بنجاح وإعادة المنتجات إلى المخزون.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()], 500);
        }
    }

    // --- بقية الدوال تبقى كما هي ---
    public function invoice($id)
    {
        $order = Order::with(['customer', 'products.product'])->findOrFail($id);
        return view('backend.orders.print-invoice', compact('order'));
    }

    public function collection(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($request->isMethod('post')) {
            $data = $request->validate(['amount' => 'required|numeric|min:1']);
            $due = $order->due - $data['amount'];
            $paid = $order->paid + $data['amount'];
            $order->due = round((float)$due, 2);
            $order->paid = round((float)$paid, 2);
            $order->status = round((float)$due, 2) <= 0;
            $order->save();
            $order->transactions()->create([
                'amount' => $data['amount'],
                'customer_id' => $order->customer_id,
                'user_id' => auth()->id(),
                'paid_by' => 'cash',
            ]);
            return to_route('backend.admin.collectionInvoice', $order->transactions()->latest()->first()->id);
        }
        return view('backend.orders.collection.create', compact('order'));
    }

    public function collectionInvoice($id)
    {
        $transaction = OrderTransaction::findOrFail($id);
        $collection_amount = $transaction->amount;
        $order = $transaction->order;
        return view('backend.orders.collection.invoice', compact('order', 'collection_amount', 'transaction'));
    }

    public function transactions($id)
    {
        $order = Order::with('transactions')->findOrFail($id);
        return view('backend.orders.collection.index', compact('order'));
    }

    public function posInvoice($id)
    {
        $order = Order::with(['customer', 'products.product'])->findOrFail($id);
        $maxWidth = readConfig('receiptMaxwidth') ?? '300px';
        return view('backend.orders.pos-invoice', compact('order', 'maxWidth'));
    }
}
