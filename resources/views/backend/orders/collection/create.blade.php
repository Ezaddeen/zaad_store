@extends('backend.master')

{{-- ⬅️ تعريب العنوان: 'Collection' -> 'تحصيل المستحقات' --}}
@section('title', __('orders.collection')) 

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.due.collection',$order->id) }}" method="post" class="accountForm">
      @csrf
      <div class="card-body">
        <div class="row">
          
          {{-- ⬅️ اسم العميل: يستخدم مفتاح 'name' من common.php --}}
          <div class="mb-3 col-md-3">
            <label for="title" class="form-label">
              {{ __('common.name') }}
            </label>
            <p>{{$order->customer->name}}</p>
          </div>
          
          {{-- ⬅️ رقم الطلب: يستخدم مفتاح 'order' من orders.php --}}
          <div class="mb-3 col-md-3">
            <label for="title" class="form-label">
              {{ __('orders.order') }}
            </label>
            <p># {{$order->id}}</p>
          </div>
          
          {{-- ⬅️ الإجمالي: يستخدم مفتاح 'total' من orders.php --}}
          <div class="mb-3 col-md-3">
            <label for="title" class="form-label">
              {{ __('orders.total') }}
            </label>
            <p>{{$order->total}}</p>
          </div>
          
          {{-- ⬅️ المستحق (المتبقي): يستخدم مفتاح 'due' من orders.php --}}
          <div class="mb-3 col-md-3">
            <label for="title" class="form-label">
              {{ __('orders.due') }}
            </label>
            <p>{{$order->due}}</p>
          </div>
          
          {{-- ⬅️ مبلغ التحصيل (المدفوع الآن) --}}
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              {{ __('orders.collection_amount') }} <span class="text-danger">*</span>
            </label>
            <input type="number" class="form-control" 
              placeholder="{{ __('orders.placeholder_amount') }}" 
              value="{{$order->due}}" name="amount" required min="1" max="{{$order->due}}">
          </div>
          
        </div>
        <div class="row">
          <div class="col-md-6">
            {{-- ⬅️ زر الإرسال: يستخدم مفتاح 'submit' من common.php --}}
            <button type="submit" class="btn bg-gradient-primary">{{ __('common.submit') }}</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@push('script')
<script>
</script>
@endpush