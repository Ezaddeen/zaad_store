<?php

namespace App\Http\Controllers\Backend\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables; // ◀️ الخطوة 1: استدعاء مكتبة DataTables

class PurchaseReturnController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     * (هذا الجزء سليم ويعمل بشكل صحيح)
     */
    public function __construct()
    {
        $this->middleware('can:purchase_return_view,web')->only('index');
        $this->middleware('can:purchase_return_create,web')->only(['create', 'store', 'getProductsByPurchase']);
    }

    /**
     * Display a listing of the resource using Server-Side DataTables.
     * (تم تعديل هذه الدالة لتصبح احترافية مثل صفحة المنتجات)
     */
    public function index(Request $request)
    {
        // ==================================================
        // ⬇️            هذا هو التعديل الأساسي            ⬇️
        // ==================================================

        // التحقق إذا كان الطلب قادماً من DataTables عبر AJAX
        if ($request->ajax()) {
            // بناء الاستعلام الأساسي
            $query = PurchaseReturn::with(['product', 'supplier', 'purchase'])->select('purchase_returns.*');

            // تطبيق الفلاتر التي تأتي من الفورم في الواجهة
            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59']);
            }

            // إعداد وإرسال البيانات بتنسيق DataTables
            return DataTables::of($query)
                ->addIndexColumn() // يضيف عمود الترقيم التلقائي
                ->editColumn('created_at', fn($row) => $row->created_at->format('Y-m-d'))
                ->addColumn('purchase_ref', fn($row) => 'فاتورة #' . $row->purchase_id)
                ->addColumn('product_name', fn($row) => $row->product->name ?? 'منتج محذوف')
                ->addColumn('supplier_name', fn($row) => $row->supplier->name ?? 'مورد محذوف')
                ->editColumn('purchase_price', fn($row) => number_format($row->purchase_price, 2))
                ->editColumn('total_amount', fn($row) => number_format($row->total_amount, 2))
                ->editColumn('notes', fn($row) => $row->notes ?? 'لا يوجد')
                ->make(true);
        }

        // في حالة الطلب العادي (فتح الصفحة لأول مرة)، فقط أرسل الموردين للفلتر
        $suppliers = Supplier::latest()->get();
        return view('backend.purchase.purchase_returns.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     * (هذه الدالة سليمة ولم يتم المساس بها)
     */
    public function create()
    {
        $purchases = Purchase::with('supplier')->latest()->get();
        return view('backend.purchase.purchase_returns.create', compact('purchases'));
    }

    /**
     * Store a newly created resource in storage.
     * (هذه الدالة سليمة ولم يتم المساس بها)
     */
    public function store(Request $request)
    {
        $request->validate(['purchase_id'=>'required|exists:purchases,id','product_id'=>'required|exists:products,id','return_quantity'=>'required|integer|min:1','notes'=>'nullable|string|max:1000',]);
        DB::beginTransaction();
        try {
            $purchase=Purchase::findOrFail($request->purchase_id);$product=Product::findOrFail($request->product_id);
            $purchaseItem=$purchase->items()->where('product_id',$product->id)->first();
            if(!$purchaseItem){return back()->with('error','خطأ: هذا المنتج غير موجود في فاتورة الشراء المحددة.');}
            $previouslyReturned=PurchaseReturn::where('purchase_id',$purchase->id)->where('product_id',$product->id)->sum('quantity');
            $availableToReturn=$purchaseItem->quantity - $previouslyReturned;
            if($request->return_quantity > $availableToReturn){$errorMessage="لا يمكن إرجاع هذه الكمية. الكمية المتاحة للإرجاع هي: {$availableToReturn} فقط.";if($availableToReturn <= 0){$errorMessage="تم إرجاع كل كمية هذا المنتج من هذه الفاتورة بالفعل.";}return back()->with('error',$errorMessage)->withInput();}
            PurchaseReturn::create(['purchase_id'=>$purchase->id,'product_id'=>$product->id,'supplier_id'=>$purchase->supplier_id,'quantity'=>$request->return_quantity,'purchase_price'=>$purchaseItem->price,'total_amount'=>$request->return_quantity * $purchaseItem->price,'notes'=>$request->notes,]);
            $product->decrement('quantity',$request->return_quantity);DB::commit();
            return redirect()->route('backend.admin.purchase-returns.index')->with('success','تم تسجيل المرتجع بنجاح وتحديث المخزون.');
        } catch(\Exception $e){DB::rollBack();return back()->with('error','حدث خطأ غير متوقع أثناء الحفظ. لم يتم تسجيل المرتجع.');}
    }

    /**
     * Get products for a specific purchase via AJAX.
     * (هذه الدالة سليمة ولم يتم المساس بها)
     */
    public function getProductsByPurchase($purchaseId)
    {
        try {
            $purchase=Purchase::with('items.product')->findOrFail($purchaseId);
            return response()->json($purchase->items);
        } catch(\Exception $e){return response()->json(['error'=>'Server Error: حدث خطأ أثناء جلب المنتجات.'],500);}
    }
}
