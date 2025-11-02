@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Receipt_#...' -> 'إيصال_رقم_#' --}}
@section('title', __('receipts.receipt') . '_' . $order->id)

@section('content')

<div class="card">
  <div class="receipt-container mt-0" id="printable-section"
    style="max-width: {{ $maxWidth}}; font-size: 12px; font-family: 'Courier New', Courier, monospace;">
    <div class="text-center">
      @if(readConfig('is_show_logo_invoice'))
      <img src="{{ assetImage(readconfig('site_logo')) }}" height="30" width="70" alt="{{ __('general.site_logo') }}">
      @endif
      @if(readConfig('is_show_site_invoice'))
      <h3>{{ readConfig('site_name') }}</h3>
      @endif
      @if(readConfig('is_show_address_invoice')){{ readConfig('contact_address') }}<br>@endif
      @if(readConfig('is_show_phone_invoice')){{ readConfig('contact_phone') }}<br>@endif
      @if(readConfig('is_show_email_invoice')){{ readConfig('contact_email') }}<br>@endif
    </div>
    
    {{-- ⬅️ تعريب 'User' و 'Order' --}}
    {{ __('general.user') . ': ' . auth()->user()->name}}<br>
    {{ __('orders.order') . ': #' . $order->id}}<br>
    <hr>
    
    <div class="row justify-content-between mx-auto">
      <div class="text-left">
        @if(readConfig('is_show_customer_invoice'))
        <address>
          {{-- ⬅️ تعريب 'Name', 'Address', 'Phone' --}}
          {{ __('common.name') }}: {{ $order->customer->name ?? 'N/A' }}<br>
          {{ __('customers.address') }}: {{ $order->customer->address ?? 'N/A' }}<br>
          {{ __('customers.phone') }}: {{ $order->customer->phone ?? 'N/A' }}
        </address>
        @endif
      </div>
      <div class="text-right">
        <address class="text-right">
          {{-- التاريخ والوقت --}}
          <p>{{ date('d-M-Y') }}</p>
          <p>{{ date('h:i:s A') }}</p>
        </address>
      </div>
    </div>
    <hr>
    
    <table style="width: 100%;">
      <thead>
        <tr>
          {{-- ⬅️ تعريب رؤوس الجدول: 'Product', 'Total' --}}
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
          {{-- ⬅️ تعريب 'Subtotal' --}}
          <td>{{ __('orders.subtotal') }}:</td>
          <td class="text-right">{{number_format($order->sub_total, 2) }}</td>
        </tr>
        <tr>
          {{-- ⬅️ تعريب 'Discount' --}}
          <td>{{ __('orders.discount') }}:</td>
          <td class="text-right">{{number_format($order->discount, 2) }}</td>
        </tr>
        <tr>
          {{-- ⬅️ تعريب 'Total' --}}
          <td><strong>{{ __('common.total') }}:</strong></td>
          <td class="text-right"><strong>{{number_format($order->total, 2) }}</strong></td>
        </tr>
        <tr>
          {{-- ⬅️ تعريب 'Paid' --}}
          <td>{{ __('orders.paid') }}:</td>
          <td class="text-right">{{number_format($order->paid, 2) }}</td>
        </tr>
        <tr>
          {{-- ⬅️ تعريب 'Due' --}}
          <td>{{ __('common.due') }}:</td>
          <td class="text-right">{{number_format($order->due, 2) }}</td>
        </tr>
      </table>
    </div>
    <hr>
    
    <div class="text-center">
      {{-- ملاحظة العميل (من الإعدادات) --}}
      <p class="text-muted" style="font-size: 12px;">@if(readConfig('is_show_note_invoice')){{ readConfig('note_to_customer_invoice') }}@endif</p>
    </div>
  </div>

  <div class="text-center mt-3 no-print pb-3">
    {{-- ⬅️ تعريب زر الطباعة: 'Print' -> 'طباعة' --}}
    <button type="button" onclick="window.print()" class="btn bg-gradient-primary text-white"><i class="fas fa-print"></i> {{ __('general.print') }}</button>
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
  }
</style>
@endpush

@push('script')
<script>
  window.print();
</script>
@endpush