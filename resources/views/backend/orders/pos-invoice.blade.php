@extends('backend.master')

@section('title', __('receipts.receipt') . '_' . $order->id)

@section('content')

<div class="card no-print">
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <button type="button" onclick="window.print()" class="btn bg-gradient-primary text-white btn-block">
                    <i class="fas fa-print"></i> {{ __('general.print') }}
                </button>
            </div>
            <div class="col-6">
                <a href="{{ route('backend.admin.cart.index') }}" class="btn bg-gradient-success text-white btn-block">
                    <i class="fas fa-cart-plus"></i> العودة إلى نقطة البيع
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
  <div class="receipt-container" id="printable-section"
    style="max-width: {{ $maxWidth}}; font-size: 12px; font-family: 'Courier New', Courier, monospace;">
    {{-- ... (بقية كود الفاتورة يبقى كما هو بدون تغيير) ... --}}
    {{-- ... --}}
    {{-- ... --}}
    <div class="text-center">
      @if(readConfig('is_show_logo_invoice'))
      <img src="{{ assetImage(readconfig('site_logo')) }}" height="30" width="70" alt="{{ __('general.site_logo') }}">
      @endif
      @if(readConfig('is_show_site_invoice'))
      <h3>{{ readConfig('site_name') }}</h3>
      @endif
      @if(readConfig('is_show_address_invoice')){{ readConfig('contact_address') }}  
@endif
      @if(readConfig('is_show_phone_invoice')){{ readConfig('contact_phone') }}  
@endif
      @if(readConfig('is_show_email_invoice')){{ readConfig('contact_email') }}  
@endif
    </div>
    
    {{ __('general.user') . ': ' . auth()->user()->name}}  

    {{ __('orders.order') . ': #' . $order->id}}  

    <hr>
    
    <div class="row justify-content-between mx-auto">
      <div class="text-left">
        @if(readConfig('is_show_customer_invoice'))
        <address>
          {{ __('common.name') }}: {{ $order->customer->name ?? 'N/A' }}  

          {{ __('customers.address') }}: {{ $order->customer->address ?? 'N/A' }}  

          {{ __('customers.phone') }}: {{ $order->customer->phone ?? 'N/A' }}
        </address>
        @endif
      </div>
      <div class="text-right">
        <address class="text-right">
          <p>{{ date('d-M-Y') }}</p>
          <p>{{ date('h:i:s A') }}</p>
        </address>
      </div>
    </div>
    <hr>
    
    <table style="width: 100%;">
      <thead>
        <tr>
          <th style="text-align: left;">{{ __('products.product') }}</th>
          <th style="text-align: right;"></th>
          <th style="text-align: right;">{{ __('common.total') }} {{ currency()->symbol}}</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order->products as $item)
        <tr>
          <td>{{ $item->product->name }}</td>
          <td class="text-right">{{ $item->quantity }}x{{ $item->discounted_price}}</td>
          <td class="text-right">{{ $item->total }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <hr>
    
    <div class="summary">
      <table style="width: 100%;">
        <tr>
          <td>{{ __('orders.subtotal') }}:</td>
          <td class="text-right">{{number_format($order->sub_total, 2) }}</td>
        </tr>
        <tr>
          <td>{{ __('orders.discount') }}:</td>
          <td class="text-right">{{number_format($order->discount, 2) }}</td>
        </tr>
        <tr>
          <td><strong>{{ __('common.total') }}:</strong></td>
          <td class="text-right"><strong>{{number_format($order->total, 2) }}</strong></td>
        </tr>
        <tr>
          <td>{{ __('orders.paid') }}:</td>
          <td class="text-right">{{number_format($order->paid, 2) }}</td>
        </tr>
        <tr>
          <td>{{ __('common.due') }}:</td>
          <td class="text-right">{{number_format($order->due, 2) }}</td>
        </tr>
      </table>
    </div>
    <hr>
    
    <div class="text-center">
      <p class="text-muted" style="font-size: 12px;">@if(readConfig('is_show_note_invoice')){{ readConfig('note_to_customer_invoice') }}@endif</p>
    </div>
  </div>
</div>
@endsection

@push('style')
<style>
  .receipt-container {
    border: 1px dotted #000;
    padding: 8px;
  }
  hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 5px 0;
  }
  table {
    width: 100%;
  }
  td,
  th {
    padding: 2px 0;
  }
  .text-right {
    text-align: right;
  }
  @media print {
    @page {
      margin-top: 5px !important;
      margin-left: 0px !important;
      padding-left: 0px !important;
    }
    footer {
      display: none !important;
    }
    .no-print {
        display: none !important;
    }
  }
</style>
@endpush

{{-- ================================================== --}}
{{-- ⬇️            هذا هو التعديل الوحيد             ⬇️ --}}
{{-- ================================================== --}}
@push('script')
<script>
  // window.print(); // ⬅️ لقد قمت بتعطيل هذا السطر
</script>
@endpush
