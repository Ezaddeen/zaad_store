@extends('backend.master')

@section('title', __('purchases.purchase_details')) {{-- تم تعريب 'Customers' إلى 'Purchase Details' (الأكثر منطقية للسياق) --}}

@section('content')
<div class="card">
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        {{ __('purchases.supplier') }} {{-- تم تعريب 'Supplier' --}}
        <address>
          <strong>{{ __('common.name') }}: {{ $purchase->supplier->name }}</strong><br> {{-- تم تعريب 'Name' --}}
        </address>
      </div>
    </div>
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card-body table-responsive p-0" id="table_data">
          <table id="datatables" class="table table-bordered text-center">
            <thead>
              <tr>
                <th data-orderable="false">#</th>
                <th>{{ __('purchases.product') }}</th> {{-- تم تعريب 'Product' --}}
                <th>{{ __('purchases.purchase_price') }}{{currency()->symbol??''}}</th> {{-- تم تعريب 'Purchase Price' --}}
                <th>
                  {{ __('common.quantity') }} {{-- تم تعريب 'Quantity' --}}
                </th>
                <th>
                  {{ __('purchases.sub_total') }}{{currency()->symbol??''}} {{-- تم تعريب 'Sub Total' --}}
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($purchase->items as $key => $item)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ number_format($item->purchase_price, 2) }}</td>
                <td>{{ $item->quantity }} {{optional($item->product->unit)->short_name}}</td>
                <td>{{ number_format(($item->purchase_price * $item->quantity), 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-6">
        <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
          {{$purchase->note??''}}
        </p>
      </div>
      <div class="col-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">{{ __('purchases.sub_total') }}:</th> {{-- تم تعريب 'Subtotal' --}}
              <td class="text-right">{{number_format($purchase->sub_total,2,'.',',')}}</td>
            </tr>
            <tr>
              <th>{{ __('purchases.tax') }}:</th> {{-- تم تعريب 'Tax' --}}
              <td class="text-right">{{number_format($purchase->tax,2,'.',',')}}</td>
            </tr>
            <tr>
              <th>{{ __('purchases.discount') }}:</th> {{-- تم تعريب 'Discount' --}}
              <td class="text-right">{{number_format($purchase->discount_value,2,'.',',')}}</td>
            </tr>
            <tr>
              <th>{{ __('purchases.shipping') }}:</th> {{-- تم تعريب 'Shipping' --}}
              <td class="text-right">{{number_format($purchase->shipping,2,'.',',')}}</td>
            </tr>
            <tr>
              <th>{{ __('purchases.grand_total') }}:</th> {{-- تم تعريب 'Total' --}}
              <td class="text-right">{{number_format($purchase->grand_total,2,'.',',')}}</td>
            </tr>
          </table>
        </div>
      </div>
      </div>
  </div>
</div>
@endsection


@push('script')
@endpush