<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Appointment\Models\BillingRecord;
use Modules\Appointment\Models\Installment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InstallmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('installments')->delete();

        // Get some BillingRecord IDs to use for seeding installments
        $billingRecords = BillingRecord::take(5)->pluck('id');
        $paymentModes = ['cash', 'card', 'online'];
        $today = Carbon::today();

        $installments = [];
        foreach ($billingRecords as $i => $billingRecordId) {
            $installments[] = [
                'billing_record_id' => $billingRecordId,
                'amount' => rand(100, 149),
                'payment_mode' => $paymentModes[$i % count($paymentModes)],
                'date' => $today->copy()->subDays($i)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Installment::insert($installments);
    }
}
