<div class="modal modal-xl fade" id="generate_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <form id="billingForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('clinic.lbl_generate_invoice') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="billing_encounter_id" value="{{ $data['id'] }}" />
                    <input type="hidden" id="final_total_amount" value="">

                    <!-- Estimate Checkbox - Always Visible at Top -->
                    <div class="form-group mb-4 p-3 border rounded bg-light">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_estimate" name="is_estimate" value="1" {{ old('is_estimate', $data['billingrecord']['is_estimate'] ?? false) ? 'checked' : '' }} onchange="toggleEstimateBehavior()">
                            <label class="form-check-label" for="is_estimate">
                                <strong>{{ __('appointment.estimate') }}</strong>
                            </label>
                        </div>
                    </div>

                    <p class="d-inline-flex gap-1">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <h4>
                            Add Item In Billing
                        </h4>
                        <button class="btn btn-primary" type="button" id="toggleButton" aria-expanded="false"
                            aria-controls="collapseExample" onclick="toggleAddServiceForm()">
                            Add Item
                        </button>
                    </div>
                    </p>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body" id="extra-service-list">
                            @include('appointment::backend.patient_encounter.component.add_service', [
                                'encounter_id' => $data['id'],
                                'billing_id' => $data['billingrecord']['id'],
                                'service_id' => $data['billingrecord']['service_id'],
                            ])
                        </div>
                    </div>

                    <div id="Service_list">
                        @include('appointment::backend.patient_encounter.component.service_list', [
                            'data' => $data['billingrecord'],
                            'status' => $data['status'],
                        ])
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-4 pb-0 pt-0">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center form-control">
                                    <label class="form-label m-0"
                                        for="category-discount">{{ __('service.discount') }}</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="final_discount" id="category-discount"
                                            type="checkbox"
                                            {{ old('final_discount', $data['final_discount'] ?? false) ? 'checked' : '' }}
                                            onchange="toggleDiscountSection()" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row d-none" id="final_discount_section">
                            <div class="col-md-6">
                                <label class="form-label m-0"
                                    for="category-discount">{{ __('service.lbl_discount_type') }}
                                    <span class="text-danger">*</span></label>
                                <select id="final_discount_type" name="final_discount_type" class="form-control select2"
                                    placeholder="{{ __('service.lbl_discount_type') }}" data-filter="select"
                                    onchange="updateDiscount()">
                                    <option value="percentage"
                                        {{ old('final_discount_type', $data['final_discount_type'] ?? '') === 'percentage' ? 'selected' : '' }}>
                                        {{ __('appointment.percentage') }}
                                    </option>
                                    <option value="fixed"
                                        {{ old('final_discount_type', $data['final_discount_type'] ?? '') === 'fixed' ? 'selected' : '' }}>
                                        {{ __('appointment.fixed') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label m-0">{{ __('service.lbl_discount_value') }} <span
                                        class="text-danger">*</span> </label>
                                <input type="number" name="final_discount_value" id="final_discount_value"
                                    class="form-control" placeholder="{{ __('service.lbl_discount_value') }}"
                                    step="0.01"
                                    value="{{ old('final_discount_value', $data['final_discount_value'] ?? 0) }}"
                                    oninput="validateDiscount(this)" required />

                                <span id="discount_amount_error" class="text-danger"></span>
                            </div>
                        </div>

                        <div id="tax_list">
                            @include('appointment::backend.patient_encounter.component.tax_list', [
                                'data' => $data['billingrecord'],
                            ])
                        </div>

                        <p class="d-inline-flex gap-1">
                        <div class="d-flex align-items-center justify-content-between gap-3 p-4 pt-0 pb-0">
                            <h4>
                                Add Installment
                            </h4>
                            <button class="btn btn-primary" type="button" id="toggleButton" data-bs-toggle="collapse"
                                data-bs-target="#addInstallmentcollapse" aria-expanded="false"
                                aria-controls="addInstallmentcollapse">
                                Add Installment
                            </button>
                        </div>
                        </p>
                        <div class="collapse p-4 pb-0 pt-0" id="addInstallmentcollapse">
                            <div class="card card-body" id="extra-installment-list">
                                @include('appointment::backend.patient_encounter.component.add_installment')
                            </div>
                        </div>

                        <div id="installment_list" class="p-4 pt-0 pb-0">
                            @include('appointment::backend.patient_encounter.component.installment_list', [
                                'data' => $data['billingrecord']['installments'],
                            ])
                        </div>

                        <div class="p-4 pb-0 pt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center form-control">
                                        <label class="form-label m-0">{{ __('appointment.service_amount') }}</label>
                                        <div class="form-check" id="service_amount">

                                            <input type="hidden" id="total_service_amount"
                                                value={{ $data['billingrecord']['service_amount'] }}>

                                            {{ Currency::format($data['billingrecord']['service_amount']) ?? Currency::format(0) }}

                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center form-control d-none"
                                        id="discount_section">
                                        <label class="form-label m-0">{{ __('appointment.discount_amount') }}</label>
                                        <div class="form-check" id="discount_amount">

                                            <input type="hidden" id="final_discount_amount" value="">

                                            {{ Currency::format($data['billingrecord']['discount_amount']) ?? Currency::format(0) }}

                                        </div>
                                    </div>

                                    @if ($data['billingrecord']['final_discount'] > 0)
                                        <div
                                            class="d-flex justify-content-between align-items-center form-control d-none">
                                            <label
                                                class="form-label m-0">{{ __('appointment.discount_amount') }}</label>
                                            <div class="form-check" id="discount_amount">

                                                <input type="hidden" id="final_discount_amount" value="">

                                                {{ Currency::format($data['billingrecord']['service_amount']) ?? Currency::format(0) }}

                                            </div>
                                        </div>
                                    @endif

                                    {{-- <div class="d-flex justify-content-between align-items-center form-control">
                                        <label class="form-label m-0">{{ __('appointment.tax') }}</label>
                                        <div class="form-check" id="tax_amount">

                                            <input type="hidden" id="total_tax_amount" value="">

                                            {{ Currency::format($data['billingrecord']['final_tax_amount']) ?? Currency::format(0) }}

                                        </div>
                                    </div> --}}

                                    <div class="d-flex justify-content-between align-items-center form-control">
                                        <label
                                            class="form-label m-0">{{ __('appointment.total_payable_amount') }}</label>
                                        <div class="form-check" id="total_payable_amount" data-value="">

                                            <input type="hidden" id="total_amount" value="">

                                            {{ Currency::format($data['billingrecord']['final_total_amount']) ?? Currency::format(0) }}

                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center form-control">
                                        <label class="form-label m-0">{{ __('appointment.paid_amount') }}</label>
                                        <div class="form-check" id="installment_total_paid">
                                            {{ Currency::format($data['billingrecord']->total_paid) ?? Currency::format(0) }}
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center form-control">
                                        <label class="form-label m-0">{{ __('appointment.remaining_amount') }}</label>
                                        <div class="form-check" id="installment_remaining">
                                            {{ Currency::format($data['billingrecord']->remaining_amount) ?? Currency::format(0) }}
                                        </div>
                                    </div>

                                    <!-- Refund Note Section -->
                                    <div class="d-flex justify-content-between align-items-center form-control d-none"
                                        id="refund_note_section">
                                        <label
                                            class="form-label m-0 text-danger">{{ __('appointment.refund_note') }}</label>
                                        <div class="form-check text-danger" id="refund_note">
                                            <!-- Refund note will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 pb-3 pt-0">
                            <div class="col-md-12">
                                <!-- Notes Section -->
                                <div class="form-group mb-3">
                                    <label for="notes" class="form-label">{{ __('appointment.note') }}</label>
                                    <textarea class="form-control h-auto" rows="3" placeholder="Enter Notes" name="notes" id="notes"
                                        style="min-height: max-content">{{ old('notes', $data['billingrecord']['notes'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="payment_status" value="{{ old('payment_status', $data['billingrecord']['payment_status'] ?? 0) }}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    
                    <!-- Estimate Save Button (only visible when estimate is checked) -->
                    <button type="button" id="save-estimate-button" class="btn btn-info d-none">
                        <i class="ph ph-file-pdf me-2"></i>
                        Save Estimate
                    </button>
                    
                    <!-- Normal Save Button (only visible when estimate is unchecked) -->
                    <button type="submit" id="save-button" class="btn btn-primary">
                        <span id="save-button-text">Save</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('toggleButton');
            const collapse = document.getElementById('collapseExample');
            const installmentCollapse = document.getElementById('addInstallmentcollapse');

            collapse.addEventListener('shown.bs.collapse', () => {
                button.textContent = 'Close';
            });

            collapse.addEventListener('hidden.bs.collapse', () => {
                button.textContent = 'Add Item';
            });
        });

        function toggleDiscountSection() {
            const isChecked = document.getElementById('category-discount').checked;
            const discountSection = document.getElementById('final_discount_section');

            if (isChecked) {
                discountSection.classList.remove('d-none'); // Correct method
            } else {
                discountSection.classList.add('d-none'); // Correct method

                removeDiscountValue();

            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleDiscountSection();

            const billingId = "{{ $data['billingrecord']['id'] }}";
            getTotalAmount(billingId);


        });

        function removeDiscountValue() {

            $('#final_discount_value').val(0);
            $('#final_discount_type').val('percentage');

            $('#discount_section').addClass('d-none');

            updateDiscount()

        }

        function getTotalAmount(billingId) {
            var baseUrl = '{{ url('/') }}';

            var url = `${baseUrl}/app/billing-record/get-billing-record/${billingId}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {

                    $('#service_amount').text(currencyFormat(data.service_details.service_total));
                    // $('#tax_amount').text(currencyFormat(data.service_details.total_tax)); // Tax calculation disabled
                    $('#total_payable_amount').text(currencyFormat(data.service_details.total_amount)).attr(
                        'data-value', data.service_details.total_amount);

                    $('#final_total_amount').val(data.service_details.total_amount);
                    $('#total_service_amount').val(data.service_details.service_total);
                    // $('#total_tax_amount').val(data.service_details.total_tax); // Tax calculation disabled
                    $('#total_amount').val(data.service_details.total_amount);
                    $('#installment_total_paid').text(currencyFormat(data.service_details.total_paid_amount || 0));
                    $('#installment_remaining').text(currencyFormat(data.service_details.remaining_amount || 0));
                    $('#discount_amount').text(currencyFormat(data.service_details.final_discount_amount));

                    // Check for refund scenario and show note
                    checkAndShowRefundNote(data.service_details.total_paid_amount || 0, data.service_details
                        .total_amount || 0);

                    if (data.service_details.final_discount_amount > 0) {

                        $('#discount_section').removeClass('d-none');
                        $('#final_discount_section').removeClass('d-none');
                        document.getElementById('category-discount').checked = true;
                        $('#final_discount_value').val(data.service_details.final_discount_value);
                        $('#final_discount_type').val(data.service_details.final_discount_type);

                        $('#discount_amount').text(currencyFormat(data.service_details.final_discount_amount));

                        $('#final_total_amount').val(data.service_details.total_amount);

                    }

                    // Check for refund scenario on initial load
                    checkAndShowRefundNote(data.service_details.total_paid_amount || 0, data.service_details
                        .total_amount || 0);

                })
                .catch(error => console.error('Error fetching total amount:', error));
        }

        function validateDiscount(input) {
            let maxAmount = 100;
            const discountType_value = document.querySelector('#final_discount_type').value;
            // Get numeric value from service_amount text content
            const totalServiceAmount = parseFloat(document.querySelector('#service_amount').innerText.replace(/[^0-9.-]+/g,
                ''));
            const discountValue = parseFloat(input.value);

            if (discountType_value === 'percentage' && input.value > maxAmount) {
                $('#discount_amount_error').text('Discount value should be less than 100');
                input.value = 0;
                updateDiscount();
            } else {
                if (discountValue > totalServiceAmount) {
                    $('#discount_amount_error').text('Discount amount cannot be greater than total service amount');
                    input.value = 0;
                } else {
                    $('#discount_amount_error').text('');
                }
                updateDiscount();
            }
        }

        function updateDiscount() {

            const discountValue = document.querySelector('input[name="final_discount_value"]').value;
            const discountType = document.querySelector('#final_discount_type').value;



            if (discountType && discountValue) {

                calculateFinalAmount(discountValue, discountType);
            }

        }

        function calculateFinalAmount(discountValue, discountType) {
            const billingId = "{{ $data['billingrecord']['id'] }}"; // Replace with dynamic billing ID as needed
            const baseUrl = '{{ url('/') }}'; // Base URL of your application

            // Prepare the data to send in the POST request
            const data = {
                discount_value: discountValue,
                discount_type: discountType,
                billing_id: billingId
            };

            // Make a POST request to the server to calculate the final amount
            fetch(`${baseUrl}/app/billing-record/calculate-discount-record`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token for Laravel
                    },
                    body: JSON.stringify(data), // Send the data as JSON
                })
                .then(response => response.json())
                .then(data => {

                    $('#total_service_amount').val(data.service_details.service_total);
                    // $('#total_tax_amount').val(data.service_details.total_tax); // Tax calculation disabled
                    $('#total_amount').val(data.service_details.total_amount);

                    $('#service_amount').text(currencyFormat(data.service_details.service_total));
                    // $('#tax_amount').text(currencyFormat(data.service_details.total_tax)); // Tax calculation disabled
                    $('#total_payable_amount').text(currencyFormat(data.service_details.total_amount)).attr(
                        'data-value', data.service_details.total_amount);

                    $('#discount_amount').text(currencyFormat(data.service_details.final_discount_amount));
                    $('#installment_total_paid').text(currencyFormat(data
                        .service_details.total_paid_amount || 0));
                    $('#installment_remaining').text(currencyFormat(data
                        .service_details
                        .remaining_amount || 0));

                    // Check for refund scenario and show note
                    checkAndShowRefundNote(data.service_details.total_paid_amount || 0, data.service_details
                        .total_amount || 0);
                    if (data.service_details.final_discount_amount > 0) {
                        $('#discount_section').removeClass('d-none');
                    }

                })
                .catch(error => {
                    console.error('Error fetching billing data:', error);
                });
        }

        function checkAndShowRefundNote(paidAmount, totalAmount) {
            const refundSection = $('#refund_note_section');
            const refundNote = $('#refund_note');

            if (paidAmount > totalAmount) {
                const refundAmount = paidAmount - totalAmount;
                const message =
                    `Patient has paid ${currencyFormat(paidAmount)} and total amount is ${currencyFormat(totalAmount)}, so you will pay ${currencyFormat(refundAmount)} as a refundable balance.`;

                refundNote.text(message);
                refundSection.removeClass('d-none');
            } else {
                refundSection.removeClass('d-none');
            }
        }

        // Store the original payment status value when page loads
        let originalPaymentStatus = {{ $data['billingrecord']['payment_status'] ?? 0 }};

        function toggleEstimateBehavior() {
            const isEstimate = $('#is_estimate').is(':checked');
            console.log('Estimate checkbox checked:', isEstimate);
            
            if (isEstimate) {
                // Change button text to "Save Estimate" for estimates
                $('#save-button-text').text('Save Estimate');
                
                // Show estimate notice
                if (!$('#estimate_notice').length) {
                    const notice = `
                        <div id="estimate_notice" class="alert alert-info mt-3">
                            <i class="ph ph-info-circle me-2"></i>
                            <strong>Estimate Mode:</strong> This encounter will be saved as an estimate. No payment is required and the encounter will remain open for viewing the estimate.
                        </div>
                    `;
                    $('#is_estimate').closest('.form-group').after(notice);
                }
            } else {
                // Reset button text for regular invoices
                $('#save-button-text').text('Save & Close Encounter');
                
                // Remove estimate notice
                $('#estimate_notice').remove();
            }
        }

        // Initialize estimate behavior on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking estimate checkbox...');
            const estimateCheckbox = document.getElementById('is_estimate');
            if (estimateCheckbox) {
                toggleEstimateBehavior();
            } else {
                console.error('Estimate checkbox not found!');
            }
        });

        // Handle estimate save button click
        $('#save-estimate-button').on('click', function() {
            console.log('Estimate save button clicked');
            
            const baseUrl = '{{ url('/') }}';
            const formData = {
                encounter_id: $('#billing_encounter_id').val(),
                final_discount_value: $('#final_discount_value').val(),
                final_discount_type: $('#final_discount_type').val(),
                payment_status: $('#payment_status').val(),
                notes: $('#billingForm #notes').val(),
                final_discount: $('#category-discount').is(':checked') ? 1 : 0,
                final_total_amount: $('#final_total_amount').val(),
                is_estimate: 1, // Force estimate mode
                _token: '{{ csrf_token() }}'
            };

            console.log('Estimate form data:', formData);

            // Make AJAX request to save estimate
            $.ajax({
                url: `${baseUrl}/app/billing-record/save-billing-detail-data`,
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#generate_invoice').modal('hide');
                    
                    // Reload page to show estimate PDF button
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    console.error('Error saving estimate:', xhr.responseJSON || xhr.responseText || error);
                    alert('Error saving estimate. Please try again.');
                }
            });
        });

        $('#billingForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            const isEstimate = $('#is_estimate').is(':checked');
            console.log('Is estimate:', isEstimate);

            const baseUrl = '{{ url('/') }}';
            const formData = {
                encounter_id: $('#billing_encounter_id').val(),
                final_discount_value: $('#final_discount_value').val(),
                final_discount_type: $('#final_discount_type').val(),
                payment_status: $('#payment_status').val(),
                notes: $('#billingForm #notes').val(),
                final_discount: $('#category-discount').is(':checked') ? 1 : 0,
                final_total_amount: $('#final_total_amount').val(),
                is_estimate: isEstimate ? 1 : 0,
                _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
            };

            // Debug: Log the form data
            console.log('Form data being sent:', formData);
            console.log('Encounter ID value:', $('#billing_encounter_id').val());

            // Make AJAX request
            $.ajax({
                url: `${baseUrl}/app/billing-record/save-billing-detail-data`,
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (isEstimate) {
                        // For estimates: close modal but don't close encounter
                        $('#generate_invoice').modal('hide');
                        
                        // Reload page after a delay to show the estimate PDF section
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        // For regular invoices: close modal and reload page (encounter closes)
                        $('#generate_invoice').modal('hide');
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseJSON || xhr.responseText || error);
                    alert('Error saving billing details. Please try again.');
                }
            });
        });
    </script>
@endpush
