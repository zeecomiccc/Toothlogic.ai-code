<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_record_id',
        'amount',
        'payment_mode',
        'date',
    ];

    /**
     * Relationship: Installment belongs to a BillingRecord
     */
    public function billingRecord()
    {
        return $this->belongsTo(BillingRecord::class);
    }
}
