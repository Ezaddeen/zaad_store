<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();

            // --- معلومات الربط الأساسية ---
            // تربط هذا المرتجع بفاتورة الشراء الأصلية
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            
            // تربط هذا المرتجع بالمنتج المحدد الذي تم إرجاعه
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // تربط هذا المرتجع بالمورد
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');

            // --- تفاصيل عملية الإرجاع ---
            // الكمية التي تم إرجاعها من هذا المنتج
            $table->integer('quantity');
            
            // سعر شراء القطعة الواحدة وقت الإرجاع (مهم للحسابات الدقيقة)
            $table->decimal('purchase_price', 10, 2);
            
            // المبلغ الإجمالي للمنتج المرتجع (الكمية * سعر الشراء)
            $table->decimal('total_amount', 10, 2);
            
            // حقل اختياري لإضافة أي ملاحظات حول سبب الإرجاع
            $table->text('notes')->nullable();

            $table->timestamps(); // تضيف حقلي created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
