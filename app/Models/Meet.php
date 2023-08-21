<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meet extends Model
{
    use HasFactory;

    const PAYMENT_STATUS_1 = 'PROCESSING';
    const PAYMENT_STATUS_2 = 'PENDING';
    const PAYMENT_STATUS_3 = 'BILLED';
    const PAYMENT_STATUS_4 = 'DENIED';

    protected $table = 'meets';

    protected $fillable = [
        'user_id',
        'specialist_id',
        'service_id',
        'service_option_id',
        'duration',
        'discount_code_id',
        'date_meet',
        'price_calculated',
        'price',
        'discount',
        'discounted_price',
        'canceled',
        'canceled_reason',
        'create_link_meet',
        'link_meet',
        'create_payment',
        'payment_link',
        'reference_id',
        'payment_status',
        'payment_id',
        'survey_status',
        'rate',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function specialist()
    {
        return $this->belongsTo(Specialist::class, 'specialist_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function discountCode(){
        return $this->belongsTo(DiscountCode::class, 'discount_code_id');
    }
}
