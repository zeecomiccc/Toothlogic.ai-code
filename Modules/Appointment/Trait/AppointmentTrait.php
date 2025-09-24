<?php

namespace Modules\Appointment\Trait;

use Modules\Clinic\Models\ClinicsService;
use Modules\Tax\Models\Tax;
use Modules\Appointment\Models\Appointment;
use App\Models\User;
use Modules\Commission\Models\Commission;
use App\Jobs\BulkNotification;
use Modules\Appointment\Models\PatientEncounter;
use Modules\Appointment\Models\AppointmentTransaction;
use Modules\Appointment\Models\BillingRecord;
use Modules\Tax\Models\TaxService;

trait AppointmentTrait
{

    public function getServiceAmount($service_id, $doctor_id, $clinic_id, $filling = 1)
    {
        $data = ClinicsService::where('id', $service_id)
            ->with([
                'ClinicServiceMapping' => function ($query) use ($clinic_id, $service_id) {
                    $query->where('clinic_id', $clinic_id)->where('service_id', $service_id);
                },
                'doctor_service' => function ($query) use ($doctor_id, $service_id) {
                    $query->where('doctor_id', $doctor_id)->where('service_id', $service_id);
                }
            ])->first();

        if (!$data) {
            return ['service_price' => 0, 'service_amount' => 0, 'total_amount' => 0, 'duration' => 0, 'discount_type' => null, 'discount_value' => 0, 'discount_amount' => 0];
        }

        // Get the doctor service charges
        $doctorService = $data->doctor_service->first();
        if (!$doctorService) {
            return ['service_price' => 0, 'service_amount' => 0, 'total_amount' => 0, 'duration' => 0, 'discount_type' => null, 'discount_value' => 0, 'discount_amount' => 0];
        }

        $service_price = $doctorService->charges ?? 0;
        
        // Calculate service amount with filling quantity
        $service_amount = $service_price * $filling;
        $discount_amount = 0;

        // Apply discount if enabled
        if ($data->discount == 1) {
            if ($data->discount_type == 'percentage') {
                $discount_amount = $service_amount * ($data->discount_value / 100);
            } else {
                $discount_amount = $data->discount_value;
            }
            $service_amount = $service_amount - $discount_amount;
        }

        // Total amount is service amount (no tax)
        $total_amount = $service_amount;

        return [
            'service_price' => $service_price,
            'service_amount' => $service_amount,
            'total_amount' => $total_amount,
            'duration' => $data->duration_min ?? 0,
            'discount_type' => $data->discount_type,
            'discount_value' => $data->discount_value,
            'discount_amount' => $discount_amount,
            'service_name' => $data->name ?? null,
            'total_inclusive_tax' => 0,
            'total_exclusive_tax' => 0,
            'service_inclusive_tax' => 0,
            'inclusive_tax_data' => [],
        ];
    }

    // function TaxCalculation($amount)
    // {

    //     $taxes = Tax::active()->whereNull('module_type')->orWhere('module_type', 'services')->where('tax_type', 'exclusive')->where('status', 1)->get();

    //     $totalTaxAmount = 0;

    //     foreach ($taxes as $tax) {
    //         if ($tax->type === 'percent') {
    //             $percentageAmount = ($tax->value / 100) * $amount;
    //             $totalTaxAmount += $percentageAmount;
    //         } elseif ($tax->type === 'fixed') {
    //             $totalTaxAmount += $tax->value;
    //         }
    //     }

    //     return $totalTaxAmount;
    // }
    function TaxCalculation($amount)
    {
        // Tax calculation disabled - return 0
        return 0;
    }

    public function commissionData($data)
    {
        $appointment = Appointment::where('id', $data['appointment_id'])
            ->with('doctor.commissionData')
            ->first();

        if (!$appointment || !$appointment->doctor) {
            return null;
        }
        $billing_record = BillingRecord::where('doctor_id', $appointment->doctor_id)->orderBy('updated_at', 'desc')->first();
        $doctor = $billing_record->doctor;
        $total_service_amount = $billing_record->final_total_amount;
        $commission_amount = $this->calculateCommission($doctor, $total_service_amount);

        $commission_list = $doctor->commissionData
            ->pluck('commission_id')
            ->toArray();

        $commission_list = Commission::whereIn('id', $commission_list)->get();

        $commissionStatus = $data->payment_status === 1 ? 'unpaid' : 'pending';

        $commission_data = [
            'employee_id' => $billing_record->doctor_id,
            'commission_amount' => $commission_amount,
            'commission_status' => $commissionStatus,
            'commissions' => $commission_list->isNotEmpty() ? $commission_list->toJson() : null,
            'payment_date' => null,
        ];

        $tip_data = [
            'employee_id' => $billing_record->doctor_id,
            'tip_amount' => $data['tip_amount'],
            'tip_status' => 'pending',
            'payment_date' => null,
        ];

        return [
            'commission_data' => $commission_data,
            'tip_data' => $tip_data,
        ];
    }



    public function AdminEarningData($data)
    {

        $total_amount = $data['total_amount'];
        $commissions = Commission::where('type', 'admin_fees')->get();
        $admin_commission = $this->calculateAdminfees($commissions, $total_amount);
        $commissionStatus = $data->payment_status === 1 ? 'unpaid' : 'pending';
        $user = User::where('user_type', 'admin')->first();

        return [

            'employee_id' => $user->id,
            'commission_amount' => $admin_commission,
            'commission_status' => $commissionStatus,
            'commissions' => $commissions->isNotEmpty() ? $commissions->toJson() : null,
            'payment_date' => null,
        ];
    }

    private function calculateAdminfees($commissions, $total_amount)
    {
        $commission_amount = 0;

        if ($commissions->isNotEmpty()) {

            foreach ($commissions as $commission) {

                if ($commission->commission_type == 'fixed') {

                    $commission_amount += $commission->commission_value;
                } else {
                    $commission_amount += $commission->commission_value * $total_amount / 100;
                }
            }
        }

        return $commission_amount;
    }



    private function calculateCommission($employee, $total_service_amount)
    {
        $commission_amount = 0;

        if (isset($employee->commissionData) && $employee->commissionData->isNotEmpty()) {
            foreach ($employee->commissionData as $commission) {
                if ($commission->mainCommission) {
                    $commission_value = $commission->mainCommission->commission_value;
                    $commission_type = $commission->mainCommission->commission_type;

                    if ($commission_type == 'fixed') {
                        $commission_amount += $commission_value;
                    } else {
                        $commission_amount += $commission_value * $total_service_amount / 100;
                    }
                }
            }
        }
        return $commission_amount;
    }

    protected function sendNotificationOnBookingUpdate($type, $appointment)
    {
        $data = mail_footer($type, $appointment);

        // $address = [
        //     'address_line_1' => $booking->branch->address->address_line_1,
        //     'address_line_2' => $booking->branch->address->address_line_2,
        //     'city' => $booking->branch->address->city,
        //     'state' => $booking->branch->address->state,
        //     'country' => $booking->branch->address->country,
        //     'postal_code' => $booking->branch->address->postal_code,
        // ];

        if ($type == 'wallet_refund') {
            $data['wallet'] = $appointment;
        } else if ($type == 'resend_user_credentials') {
            $data['resend_user_data'] = $appointment;
        } else {
            $data['appointment'] = $appointment;
        }

        BulkNotification::dispatch($data);
    }

    // function calculateTaxAmounts($taxData, $totalAmount)
    // {
    //     $result = [];
    //     // dd($taxData);
    //     if ($taxData != null) {
    //         $taxes = json_decode($taxData);
    //     } else {

    //         $taxes = Tax::active()->whereNull('module_type')->orWhere('module_type', 'services')->where('tax_type', 'exclusive')->where('status', 1)->get();
    //     }
    //     // dd($taxes);
    //     foreach ($taxes as $tax) {
    //         $amount = 0;
    //         if ($tax->type == 'percent') {
    //         $amount = ($tax->value / 100) * $totalAmount;
    //         } else {
    //             $amount = $tax->value ?? 0;
    //         }
    //         $result[] = [
    //             'title' => $tax->title ?? 0,
    //             'type' => $tax->type,
    //             'value' => $tax->value,
    //             'amount' => (float) number_format($amount, 2, '.', ''),
    //             'tax_scope' => $tax->tax_scope ?? $tax->tax_type ?? '',

    //         ];
    //     }

    //     return $result;
    // }

    function calculateTaxAmounts($taxData, $totalAmount)
    {
        // Tax calculation disabled - return empty array with proper structure
        return [];
    }

    // function calculateTaxdata($service_amount)
    // {
    //     $result = [];


    //     $taxes = Tax::active()->whereNull('module_type')->orWhere('module_type', 'services')->where('tax_type', 'exclusive')->where('status', 1)->get();

    //     if ($taxes) {

    //         foreach ($taxes as $tax) {
    //             $amount = 0;
    //         if ($tax->type == 'percent') {
    //             $amount = ($tax->value / 100) * $service_amount;
    //         } else {
    //             $amount = $tax->value ?? 0;
    //         }
    //         $result[] = [
    //             'title' => $tax->title,
    //             'value' => $tax->value,
    //             'type' => $tax->type,
    //             'amount' => (float) number_format($amount, 2, '.', ''),
    //             'tax_type' => $tax->tax_type,

    //         ];
    //         }
    //     }



    //     return $result;
    // }
    function calculateTaxdata($service_amount)
    {
        // Tax calculation disabled - return empty array
        return [];
    }

    public function ServiceDetails($encounter_id, $service_id)
    {

        $serviceDetails = [];


        if ($encounter_id != null && $service_id != null) {

            $encounterDetails = PatientEncounter::where('id', $encounter_id)->first();

            $doctor_id = $encounterDetails->doctor_id;
            $clinic_id = $encounterDetails->clinic_id;
            $appointment_id = $encounterDetails->appointment_id;
            $appointment = Appointment::with('appointmenttransaction')->where('id', $appointment_id)->first();
            $taxData = $appointment->appointmenttransaction ? $appointment->appointmenttransaction->tax_percentage : null;
            $serviceDetails = ClinicsService::where('id', $service_id)
                ->whereHas('doctor_service', function ($query) use ($doctor_id, $clinic_id) {
                    $query->where('doctor_id', $doctor_id)
                        ->where('clinic_id', $clinic_id);
                })->with('doctor_service')

                ->first();

            $servicePricedata = $this->getServiceAmount($service_id, $doctor_id, $clinic_id);

            $serviceDetails['service_price_data'] = $servicePricedata;

            $serviceDetails['tax_data'] = null; // Tax calculation disabled
            // $serviceDetails['tax_data'] = $servicePricedata['service_amount'] > 0 ? $this->calculateTaxAmounts($taxData, $servicePricedata['service_amount']) : null;
        }

        return $serviceDetails;
    }

    public function calculate_inclusive_tax($service_amount, $inclusive_tax)
    {
        // Tax calculation disabled - return no tax
        return [
            'taxes' => [],
            'total_inclusive_tax' => 0,
        ];
    }
}
