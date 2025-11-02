@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Transactions Sale #' -> 'معاملات طلب البيع رقم' --}}
@section('title', __('orders.transactions_title') . ' #' . $order->id)

@section('content')
<div class="card">
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card-body table-responsive p-0" id="table_data">
          <table id="datatables" class="table table-hover">
            <thead>
              <tr>
                {{-- ⬅️ رؤوس الأعمدة --}}
                <th data-orderable="false">#</th>
                
                <th>{{ __('transactions.transaction_id') }}</th>
                <th>{{ __('common.amount') }} {{currency()->symbol??''}}</th>
                <th>{{ __('transactions.paid_by') }}</th>
                <th>{{ __('common.created_at') }}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($order->transactions as $index => $transaction)
              <tr>
                <td>{{ $index + 1}}</td>
                <td>#{{$transaction->id}}</td>
                <td>{{number_format($transaction->amount,2,'.',',')}}</td>
                <td>{{$transaction->paid_by}}</td>
                <td>{{ $transaction->created_at->format('M-d Y, h:i A') }}</td>
                <td>
                  {{-- ⬅️ تعريب زر الفاتورة: 'Invoice' -> 'فاتورة' --}}
                  <a class="btn btn-success btn-sm" href="{{route('backend.admin.collectionInvoice',$transaction->id)}}">{{ __('general.invoice') }}</a>
                </td>
              </tr>
              @empty
              <tr>
                {{-- ⬅️ تعريب رسالة عدم وجود معاملات: 'No transaction found.' -> 'لم يتم العثور على أي معاملات.' --}}
                <td colspan="6" class="text-center">{{ __('transactions.no_transactions') }}</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')
@endpush