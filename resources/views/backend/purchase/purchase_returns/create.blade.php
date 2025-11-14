<!-- D:\qpos\qpos\resources\views\backend\purchase\purchase_returns\create.blade.php -->
@extends('backend.master')

@section('title', 'إضافة مرتجع شراء جديد')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>تسجيل مرتجع شراء</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('backend.admin.purchase-returns.store') }}" method="POST">
            @csrf

            {{-- الخطوة 1: اختيار فاتورة الشراء --}}
            <div class="form-group">
                <label for="purchase_id">اختر فاتورة الشراء الأصلية</label>
                <select name="purchase_id" id="purchase_id" class="form-control select2" required>
                    <option value="">-- اختر فاتورة --</option>
                    @foreach ($purchases as $purchase)
                        <option value="{{ $purchase->id }}">
                            فاتورة #{{ $purchase->id }} - (المورد: {{ $purchase->supplier->name }}) - بتاريخ: {{ $purchase->created_at->format('Y-m-d') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- الخطوة 2: اختيار المنتج (سيتم ملؤه عبر AJAX) --}}
            <div class="form-group">
                <label for="product_id">اختر المنتج المرتجع</label>
                <select name="product_id" id="product_id" class="form-control select2" required disabled>
                    <option value="">-- اختر الفاتورة أولاً --</option>
                </select>
            </div>

            {{-- الخطوة 3: تحديد الكمية المرتجعة --}}
            <div class="form-group">
                <label for="return_quantity">الكمية المرتجعة</label>
                <input type="number" name="return_quantity" id="return_quantity" class="form-control" min="1" required placeholder="أدخل الكمية">
            </div>

            {{-- الخطوة 4: ملاحظات (اختياري) --}}
            <div class="form-group">
                <label for="notes">ملاحظات (سبب الإرجاع)</label>
                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="اكتب ملاحظاتك هنا (اختياري)"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">تسجيل المرتجع</button>
        </form>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        // تهيئة مكتبة Select2
        $('.select2').select2();

        // عند تغيير فاتورة الشراء
        $('#purchase_id').on('change', function() {
            const purchaseId = $(this).val();
            const productsSelect = $('#product_id');

            // تفريغ قائمة المنتجات وتعطيلها
            productsSelect.html('<option value="">-- جاري التحميل... --</option>').prop('disabled', true);

            if (!purchaseId) {
                productsSelect.html('<option value="">-- اختر الفاتورة أولاً --</option>');
                return;
            }

            // طلب AJAX لجلب المنتجات
            $.ajax({
    url: `{{ route('backend.admin.purchase-returns.getProductsByPurchase', ['purchaseId' => ':purchaseId']) }}`.replace(':purchaseId', purchaseId),
                type: 'GET',
                success: function(data) {
                    productsSelect.html('<option value="">-- اختر المنتج --</option>');
                    data.forEach(function(item) {
                        // item.product يحتوي على تفاصيل المنتج
                        productsSelect.append(
                            `<option value="${item.product.id}">
                                ${item.product.name} (الكمية المشتراة: ${item.quantity})
                            </option>`
                        );
                    });
                    productsSelect.prop('disabled', false); // تفعيل القائمة
                },
                error: function() {
                    productsSelect.html('<option value="">-- حدث خطأ --</option>');
                    alert('فشل في جلب منتجات الفاتورة.');
                }
            });
        });
    });
</script>
@endpush
