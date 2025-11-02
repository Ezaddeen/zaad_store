@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Customers' -> 'العملاء' --}}
@section('title', __('customers.title')) 

@section('content')
<div class="card">

  @can('customer_create')
  <div class="mt-n5 mb-3 d-flex justify-content-end">
    <a href="{{ route('backend.admin.customers.create') }}" class="btn bg-gradient-primary">
      <i class="fas fa-plus-circle"></i>
      {{-- ⬅️ تعريب زر الإضافة: 'Add New' -> 'إضافة جديد' (نفترض في ملف general/customers) --}}
      <span>{{ __('general.add_new') }}</span> 
    </a>
  </div>
  @endcan
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card-body table-responsive p-0" id="table_data">
          <table id="datatables" class="table table-hover">
            <thead>
              <tr>
                {{-- ⬅️ رؤوس الأعمدة --}}
                <th data-orderable="false">#</th>
                
                {{-- ⬅️ استخدام المفاتيح من common.php / customers.php --}}
                <th>{{ __('common.name') }}</th> 
                <th>{{ __('customers.phone') }}</th> 
                <th>{{ __('customers.address') }}</th> 
                <th>{{ __('customers.created') }}</th> {{-- مفتاح تاريخ الإنشاء --}}
                
                <th data-orderable="false">
                  {{ __('common.action') }} 
                </th>
              </tr>
            </thead>
            <tbody>
                {{-- يتم ملء الجدول بواسطة DataTables / AJAX --}}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


@push('script')
<script type="text/javascript">
  // لا حاجة لتعريب هذا الجزء من الكود، حيث أنه يتحكم في منطق البيانات وليس في العرض
  $(function() {
    let table = $('#datatables').DataTable({
      processing: true,
      serverSide: true,
      ordering: true,
      order: [
        [1, 'asc']
      ],
      ajax: {
        url: "{{ route('backend.admin.customers.index') }}"
      },
      
      // لتطبيق تعريب DataTables نفسه، يجب إضافة ملف اللغة هنا:
      // language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json' },

      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'phone',
          name: 'phone'
        },
        {
          data: 'address',
          name: 'address'
        },
        {
          data: 'created_at',
          name: 'created_at'
        },
        {
          data: 'action',
          name: 'action'
        },
      ]
    });
  });
</script>
@endpush