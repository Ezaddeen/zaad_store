@extends('backend.master')

@section('title', 'قائمة مرتجعات المشتريات')

@push('css')
    {{-- ================================================== --}}
    {{-- ⬇️            هذا هو التعديل الأول            ⬇️ --}}
    {{-- ================================================== --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    {{-- تم حذف السطر التالي لأنه يسبب تعارضاً --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css"> --}}
    
    <style>
        .select2-container--bootstrap4 .select2-selection--single { height: calc(1.5em + .75rem + 2px ) !important; }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered { line-height: calc(1.5em + .75rem); }
    </style>
@endpush

@section('content')

{{-- بطاقة فلاتر البحث --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">فلاتر البحث</h6>
    </div>
    <div class="card-body">
        <form id="filter-form">
            <div class="row align-items-end">
                <div class="col-md-4"><div class="form-group mb-md-0"><label for="supplier_id">المورد</label><select name="supplier_id" id="supplier_id" class="form-control select2"><option value="">كل الموردين</option>@foreach($suppliers as $supplier)<option value="{{ $supplier->id }}">{{ $supplier->name }}</option>@endforeach</select></div></div>
                <div class="col-md-3"><div class="form-group mb-md-0"><label for="start_date">من تاريخ</label><input type="date" name="start_date" id="start_date" class="form-control"></div></div>
                <div class="col-md-3"><div class="form-group mb-md-0"><label for="end_date">إلى تاريخ</label><input type="date" name="end_date" id="end_date" class="form-control"></div></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary btn-block">بحث</button></div>
            </div>
        </form>
    </div>
</div>

{{-- بطاقة قائمة المرتجعات --}}
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-end align-items-center">
            @can('purchase_return_create')
            <a href="{{ route('backend.admin.purchase-returns.create') }}" class="btn bg-gradient-primary">
              <i class="fas fa-plus-circle"></i>
              إضافة مرتجع جديد
            </a>
            @endcan
        </div>
    </div>
  
    <div class="card-body p-2 p-md-4 pt-0">
        @if(session('success'))
            <div class="alert alert-success mt-3" role="alert">{{ session('success') }}</div>
        @endif

        <table id="returns-table" class="table table-hover table-bordered text-center" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>تاريخ المرتجع</th>
                    <th>فاتورة الشراء الأصلية</th>
                    <th>المنتج</th>
                    <th>المورد</th>
                    <th>الكمية</th>
                    <th>سعر الوحدة</th>
                    <th>الإجمالي</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
    {{-- ================================================== --}}
    {{-- ⬇️           وهذا هو التعديل الثاني           ⬇️ --}}
    {{-- ================================================== --}}

    {{-- تم حذف أسطر cdn.datatables.net من هنا أيضاً --}}
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script> --}}
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document ).ready(function() {
            $('.select2').select2({ theme: 'bootstrap4' });
            
            let table = $('#returns-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('backend.admin.purchase-returns.index') }}",
                    data: function (d) {
                        d.supplier_id = $('#supplier_id').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'purchase_ref', name: 'purchase.id' },
                    { data: 'product_name', name: 'product.name' },
                    { data: 'supplier_name', name: 'supplier.name' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'purchase_price', name: 'purchase_price' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'notes', name: 'notes', orderable: false, searchable: false },
                ],
                order: [[ 1, "desc" ]],
                // لا حاجة لأي خيارات إضافية لأنها معرفة بشكل عام في القالب
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });
        });
    </script>
@endpush
