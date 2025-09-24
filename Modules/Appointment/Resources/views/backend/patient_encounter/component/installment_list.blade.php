<div class="card-body">
    <div class="table-responsive rounded">
        <table class="table table-lg m-0" id="service_list_table">
            <thead>
                <tr class="text-white">
                    <th>{{ __('appointment.sr_no') }}</th>
                    <th>{{ __('appointment.amount') }}</th>
                    <th>{{ __('clinic.lbl_payment_mode') }}</th>
                    <th>{{ __('appointment.date') }}</th>
                    <th>{{ __('appointment.invoice_detail') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $timezone = App\Models\Setting::where('name', 'default_time_zone')->value('val') ?? 'UTC';
                    $setting = App\Models\Setting::where('name', 'date_formate')->first();
                    $dateformate = $setting ? $setting->val : 'Y-m-d';
                    $setting = App\Models\Setting::where('name', 'time_formate')->first();
                    $timeformate = $setting ? $setting->val : 'h:i A';
                @endphp
                @foreach ($data as $index => $iteam)
                    <tr data-service-id="{{ $iteam['id'] }}">
                        <td>
                            <h6 class="text-primary">
                                {{ $index + 1 }}
                            </h6>
                        </td>
                        <td>
                            {{ Currency::format($iteam['amount']) }}
                        </td>
                        <td>
                            {{ $iteam['payment_mode'] }}
                        </td>
                        <td>
                            {{ isset($iteam['date'])
                                ? \Carbon\Carbon::parse($iteam['date'])->timezone($timezone)->format($dateformate)
                                : '--' }}
                        </td>
                        <td>
                            <div class="text-end d-flex gap-3 align-items-center">
                                <a href="{{ route('backend.billing-record.download.installment.pdf', $iteam['id']) }}" data-type="ajax"
                                    class="btn text-info p-0 fs-5" data-bs-toggle="tooltip" aria-label="Invoice Detail"
                                    data-bs-original-title="Invoice Detail">
                                    <i class="ph ph-file-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if (count($data) <= 0)
                    <tr>
                        <td colspan="7">
                            <div class="my-1 text-danger text-center">{{ __('appointment.no_installment_found') }}
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div id="service-error-message" class="alert alert-danger mt-2 d-none"></div>
    </div>
</div>
