@extends('backend.master')
{{-- ⬅️ تعريب العنوان: 'Invoice_#...' -> 'الفاتورة_رقم_#' --}}
@section('title', __('invoices.invoice_title') . '_' . $order->id) 

@section('content')
<div class="card">
  <div class="card-body">
    <section class="invoice">
      <div class="row mb-4">
        <div class="col-4">
          <h2 class="page-header">
            @if(readConfig('is_show_logo_invoice'))
            <img src="{{ assetImage(readconfig('site_logo')) }}" height="40" width="40" alt="{{ __('general.site_logo') }}"
              class="brand-image img-circle elevation-3" style="opacity: .8">
            @endif
            @if(readConfig('is_show_site_invoice')){{ readConfig('site_name') }} @endif
          </h2>
        </div>
        <div class="col-4">
          {{-- ⬅️ تعريب عنوان الفاتورة --}}
          <h4 class="page-header">{{ __('invoices.invoice') }}</h4>
        </div>
        <div class="col-4">
          {{-- ⬅️ تعريب كلمة 'Date' --}}
          <small class="float-right text-small">{{ __('general.date') }}: {{date('d/m/Y')}}</small>
        </div>
        </div>
      <div class="row invoice-info">
        <div class="col-sm-5 invoice-col">
          @if(readConfig('is_show_customer_invoice'))
          {{-- ⬅️ تعريب كلمة 'To' --}}
          {{ __('invoices.to') }}
          <address>
            {{-- ⬅️ تعريب 'Name', 'Address', 'Phone' --}}
            <strong>{{ __('common.name') }}: {{$order->customer->name??"N/A"}}</strong><br>
            {{ __('customers.address') }}: {{$order->customer->address??"N/A"}}<br>
            {{ __('customers.phone') }}: {{$order->customer->phone??"N/A"}}<br>
          </address>
          @endif
        </div>
        <div class="col-sm-4 invoice-col">
          {{-- ⬅️ تعريب كلمة 'From' --}}
          {{ __('invoices.from') }}
          <address>
            {{-- ⬅️ تعريب 'Name', 'Address', 'Phone', 'Email' --}}
            @if(readConfig('is_show_site_invoice'))<strong>{{ __('common.name') }}:{{ readConfig('site_name') }}</strong><br> @endif
            @if(readConfig('is_show_address_invoice')){{ __('customers.address') }}: {{ readConfig('contact_address') }}<br>@endif
            @if(readConfig('is_show_phone_invoice')){{ __('customers.phone') }}: {{ readConfig('contact_phone') }}<br>@endif
            @if(readConfig('is_show_email_invoice')){{ __('general.email') }}: {{ readConfig('contact_email') }}<br>@endif
          </address>
        </div>
        <div class="col-sm-3 invoice-col">
          {{-- ⬅️ تعريب 'Info' --}}
          {{ __('invoices.info') }} <br>
          {{-- ⬅️ تعريب 'Sale ID' --}}
          {{ __('orders.sale_id') }} #{{$order->id}}<br>
          {{-- ⬅️ تعريب 'Sale Date' --}}
          {{ __('orders.sale_date') }}: {{date('d/m/Y', strtotime($order->created_at))}}<br>
        </div>
        </div>
      <div class="row">
        <div class="col-12 table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                {{-- ⬅️ تعريب رؤوس الجدول --}}
                <th>{{ __('general.sn') }}</th>
                <th>{{ __('products.product') }}</th>
                <th>{{ __('common.quantity') }}</th>
                <th>{{ __('common.price') }} {{currency()->symbol??''}}</th>
                <th>{{ __('orders.subtotal') }} {{currency()->symbol??''}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->products as $item )
              <tr>
                <td>{{$loop->index + 1}}</td>
                <td>{{$item->product->name}}</td>
                <td>{{$item->quantity}} {{optional($item->product->unit)->short_name}}</td>
                <td>
                  {{$item->discounted_price }}
                  @if ($item->price>$item->discounted_price)
                  <br><del>{{ $item->price }}</del>
                  @endif
                </td>
                <td>{{$item->total}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        </div>
      <div class="row">
        <div class="col-6">
          <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
            {{-- ملاحظة العميل (من الإعدادات) --}}
            @if(readConfig('is_show_note_invoice')){{ readConfig('note_to_customer_invoice') }}@endif
          </p>
        </div>
        <div class="col-6">
          <div class="table-responsive">
            <table class="table">
              <tr>
                {{-- ⬅️ تعريب 'Subtotal' --}}
                <th style="width:50%">{{ __('orders.subtotal') }}:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($order->sub_total,2,'.',',')}}</td>
              </tr>
              <tr>
                {{-- ⬅️ تعريب 'Discount' --}}
                <th>{{ __('orders.discount') }}:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($order->discount,2,'.',',')}}</td>
              </tr>
              <tr>
                {{-- ⬅️ تعريب 'Total' --}}
                <th>{{ __('common.total') }}:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($order->total,2,'.',',')}}</td>
              </tr>
              <tr>
                {{-- ⬅️ تعريب 'Paid' --}}
                <th>{{ __('orders.paid') }}:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($order->paid,2,'.',',')}}</td>
              </tr>
              <tr>
                {{-- ⬅️ تعريب 'Due' --}}
                <th>{{ __('common.due') }}:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($order->due,2,'.',',')}}</td>
              </tr>
            </table>
          </div>
        </div>
        </div>
      <div class="row no-print">
        <div class="col-12">
          {{-- ⬅️ تعريب زر الطباعة: 'Print' -> 'طباعة' --}}
          <button type="button" onclick="window.print()" class="btn btn-success float-right"><i class="fas fa-print"></i> {{ __('general.print') }}</a>
          </button>
        </div>
      </div>
      </section>
    </div>
</div>
@endsection

@push('style')
<style>
  .invoice {
    border: none !important;
  }
</style>
@endpush
@push('script')
<script>
  window.addEventListener("load", window.print());
</script>
@endpush