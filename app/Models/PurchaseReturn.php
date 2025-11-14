<?php
// D:\qpos\qpos\app\Models\PurchaseReturn.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * علاقة مع فاتورة الشراء الأصلية
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * علاقة مع المنتج المرتجع
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * علاقة مع المورد
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
