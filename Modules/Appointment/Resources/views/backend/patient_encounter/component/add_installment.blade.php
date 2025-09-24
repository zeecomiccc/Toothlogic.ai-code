<form id="installmentForm">
    @csrf
    <div class="card bg-body">
        <div class="card-body border-top">
            <div class="row">
                <input type="hidden" id="billing_record_id" name="billing_record_id"
                    value="{{ $data['billingrecord']->id }}">

                <div class="form-group col-md-4">
                    <label class="form-label" for="clinic_id">
                        {{ __('appointment.amount') }} <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" name="amount" id="amount" class="form-control"
                            placeholder="{{ __('appointment.amount') }}">
                    </div>
                </div>

                <div class=" form-group col-md-4">
                    <label class="form-label" for="category-discount">{{ __('clinic.lbl_payment_mode') }}
                        <span class="text-danger">*</span></label>
                    <select id="payment_mode" name="payment_mode" class="form-control select2">
                        <option value="cash">{{ __('appointment.cash') }}</option>
                        <option value="card">{{ __('appointment.card') }}</option>
                        <option value="online">{{ __('appointment.online') }}</option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label class="form-label" for="installment_date">
                        {{ __('appointment.date') }} <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="text" name="installment_date" id="installment_date" class="form-control" placeholder="{{ __('appointment.date') }}" autocomplete="off" required>
                    </div>
                    <span class="text-danger error-installment_date"></span>
                </div>

                <span class="text-danger error-amount"></span>
            </div>
        </div>
        <div class="card-footer border-top">
            <div class="d-flex align-items-center justify-content-end gap-3">
                <button class="btn btn-primary" type="button" id="saveInstallmentForm">
                    {{ __('appointment.save') }}
                </button>
            </div>
        </div>
    </div>
</form>

@push('after-scripts')
    <script>
        $(document).ready(function() {
            $('#payment_mode').select2();

            // Initialize flatpickr for the date picker
            flatpickr('#installment_date', {
                dateFormat: 'Y-m-d',
                allowInput: true,
                defaultDate: new Date()
            });

            // 🔁 Important: Intercept button click
            $('#saveInstallmentForm').on('click', function(e) {
                e.preventDefault(); // Prevent native submit

                let url = "{{ route('backend.billing-record.installments.store') }}";

                $('.error-amount').html('');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        billing_record_id: $('#billing_record_id').val(),
                        amount: $('#amount').val(),
                        payment_mode: $('#payment_mode').val(),
                        total_payable_amount: $('#total_payable_amount').attr('data-value'),
                        installment_date: $('#installment_date').val(),
                    },
                    success: function(response) {
                        if (response.success) {
                            const collapseIns = document.getElementById(
                                'addInstallmentcollapse');

                            const bootstrapCollapseIns = new bootstrap.Collapse(collapseIns);
                            bootstrapCollapseIns.hide();

                            $('#installment_list').html(response.html);
                            $('#amount').val(null);
                            $('#payment_mode').val('cash').trigger('change');
                            $('#installment_total_paid').text(response.total_paid);
                            $('#installment_remaining').text(response.remaining_amount);
                            window.successSnackbar(response.message);
                            $('#installment_date').val(flatpickr.formatDate(new Date(), 'Y-m-d'));

                            if (response.save_billing === true) {
                                $('#payment_status').val('1');
                                $('#billingForm').submit();
                            }
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.amount) {
                                $('.error-amount').html(errors.amount[0]);
                            }
                            if (errors.payment_mode) {
                                $('.error-payment_mode').html(errors.payment_mode[0]);
                            }
                            if (errors.installment_date) {
                                $('.error-installment_date').html(errors.installment_date[0]);
                            }
                        } else {
                            toastr.error("Something went wrong!");
                        }
                    }
                });
            });
        });
    </script>
@endpush
