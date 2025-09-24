<?php

namespace Modules\Appointment\Trait;

use Carbon\Carbon;
use Modules\Appointment\Models\BillingRecord;
use Modules\Appointment\Models\BillingItem;

trait BillingRecordTrait
{
    use AppointmentTrait;
    public function generateBillingRecord($encounter_details, $is_estimate = false)
    {

        $service_amount = optional($encounter_details->appointment)->service_amount;
        // $tax_data = [];
        // $taxes = json_decode(optional(optional($encounter_details->appointment)->appointmenttransaction)->tax_percentage);
        // foreach ($taxes as $tax) {
        //     $amount = 0;
        //     if ($tax->type == 'percent') {
        //         $amount = ($tax->value / 100) * $service_amount;
        //     } else {
        //         $amount = $tax->value ?? 0;
        //     }

        //     $tax_data[] = [
        //         'title' => $tax->title,
        //         'value' => $tax->value,
        //         'type' => $tax->type,
        //         'tax_type' => isset($tax->tax_type) ? $tax->tax_type : (isset($tax->tax_scope) ? $tax->tax_scope : null),
        //         'amount' => (float) number_format($amount, 2),
        //     ];
        // }
        $tax_data = []; // Tax calculation disabled

        $billing_record = [
            'encounter_id' => $encounter_details->id,
            'user_id' => $encounter_details->user_id,
            'clinic_id' => $encounter_details->clinic_id,
            'doctor_id' => $encounter_details->doctor_id,
            'service_id' => optional($encounter_details->appointment)->service_id,
            'total_amount' => optional($encounter_details->appointment)->total_amount ?? 0,
            'service_amount' => optional($encounter_details->appointment)->service_amount ?? 0,
            'discount_type' => optional(optional($encounter_details->appointment)->appointmenttransaction)->discount_type ?? null,
            'discount_value' => optional(optional($encounter_details->appointment)->appointmenttransaction)->discount_value ?? 0,
            'discount_amount' => optional(optional($encounter_details->appointment)->appointmenttransaction)->discount_amount ?? 0,
            'tax_data' => json_encode($tax_data),
            'date' => date('Y-m-d', strtotime($encounter_details->encounter_date)),
            'payment_status' => $is_estimate ? 0 : (optional(optional($encounter_details->appointment)->appointmenttransaction)->payment_status ?? 0),
            'is_estimate' => $is_estimate
        ];


        $billingrecord = BillingRecord::create($billing_record);

        return $billingrecord;
    }
    public function generateBillingItem($billing_record)
    {

        $service_amount = optional($billing_record->clinicservice->doctor_service->firstWhere('doctor_id', $billing_record->patientencounter->doctor_id))->charges;
        $discount_value = ($billing_record->discount_type == 'percentage') ? ($service_amount * $billing_record->discount_value) / 100 : $billing_record->discount_value;
        // $inclusive_tax_array = $this->calculate_inclusive_tax(optional($billing_record->clinicservice->doctor_service->firstWhere('doctor_id', $billing_record->patientencounter->doctor_id))->charges - $discount_value,$billing_record->clinicservice->inclusive_tax);
        // $inclusive_tax_amount = $inclusive_tax_array['total_inclusive_tax'];
        // $total_amount = $service_amount - $discount_value + $inclusive_tax_amount;
        $inclusive_tax_amount = 0; // Tax calculation disabled
        $total_amount = $service_amount - $discount_value; // Tax calculation disabled
        // dd($total_amount,$inclusive_tax_amount);
        $billing_item = [

            'billing_id' => $billing_record->id ?? null,
            'item_id' => $billing_record->service_id ?? null,
            'item_name' => optional($billing_record->clinicservice)->name ?? null,
            'quantity' => $billing_record->quantity ?? 1,
            'service_amount' => optional(optional($billing_record->patientencounter)->appointmentdetail)->service_price
                ?? $service_amount
                ?? 0,
            'total_amount' => optional(optional($billing_record->patientencounter)->appointmentdetail)->service_amount
                ?? $total_amount
                ?? 0,
            'discount_type' => $billing_record->discount_type ?? null,
            'discount_value' => $billing_record->discount_value ?? 0,
            'inclusive_tax_amount' => 0, // Tax calculation disabled
            // 'inclusive_tax_amount' => $billing_record->patientencounter->appointmentdetail->appointmenttransaction->inclusive_tax_price ?? $inclusive_tax_amount ?? 0,
            'inclusive_tax' => null, // Tax calculation disabled
            // 'inclusive_tax' =>$billing_record->patientencounter->appointmentdetail->appointmenttransaction->inclusive_tax ?? $billing_record->clinicservice->inclusive_tax ?? null,
        ];

        $billing_item = BillingItem::updateOrCreate(
            [
                'billing_id' => $billing_record->id,
                'item_id' => $billing_record->service_id,
            ],
            $billing_item
        );

        return $billing_item;
    }
}
