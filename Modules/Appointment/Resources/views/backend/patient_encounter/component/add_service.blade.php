<div class="card bg-body">
    <div class="card-body border-top">
        <div class="row"> <input type="hidden" value={{ $encounter_id }} id="billing_encounter_id"
                name="billing_encounter_id"> <input type="hidden" value={{ $service_id }} id="billing_service_id"
                name="billing_service_id">
            <div class=" form-group col-md-3"> <label class="form-label m-0"
                    for="category-discount">{{ __('appointment.lbl_service') }} <span
                        class="text-danger">*</span></label>
                <div class=""> <select id="service_id" name="service_id" class="form-control select2"
                        placeholder="{{ __('appointment.select_service') }}" data-filter="select">
                        <option value="">{{ __('appointment.select_service') }}</option>
                    </select> </div>
            </div>
            <div class="form-group col-md-3"> <label class="form-label" for="clinic_id"> {{ __('clinic.price') }} <span
                        class="text-danger">*</span> </label>
                <div class="input-group"> <input type="number" name="charges" id="charges" class="form-control"
                        placeholder="{{ __('clinic.price') }}" readonly> </div>
                @if ($errors->has('charges'))
                    <span class="text-danger">{{ $errors->first('charges') }}</span>
                @endif
            </div>
            <div class="form-group col-md-3"> <label class="form-label"> {{ __('product.quantity') }} <span
                        class="text-danger">*</span> </label> <input type="number" name="quantity" id="quantity"
                    class="form-control" placeholder="{{ __('product.quantity') }}" value="{{ old('quantity') }}"
                    min="1">
                @if ($errors->has('quantity'))
                    <span class="text-danger">{{ $errors->first('quantity') }}</span>
                @endif
            </div>
            <div class="form-group col-md-3"> <label class="form-label"> {{ __('appointment.total') }} <span
                        class="text-danger">*</span> </label> <input type="text" name="total" id="total"
                    class="form-control" placeholder="{{ __('appointment.total') }}" value="{{ old('total') }}"
                    readonly>
                @if ($errors->has('total'))
                    <span class="text-danger">{{ $errors->first('total') }}</span>
                @endif
            </div> <input type="hidden" id="discount_value" name="discount_value"> <input type="hidden"
                id="service_amount" name=" service_amount"> <input type="hidden" id="discount_type"
                name="discount_type"> <input type="hidden"                 id="inclusive_tax_price" name="inclusive_tax_price" style="display: none;"> {{-- Tax calculation disabled --}} <input
                type="hidden" id="inclusive_tax" name="inclusive_tax" style="display: none;"> {{-- Tax calculation disabled --}} <input type="hidden" id="billing_id"
                name="billing_id" value="{{ $billing_id }}"> <input type="hidden" id="billing_item_id"
                name="billing_item_id" value="">
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex align-items-center justify-content-end gap-3"> <button class="btn btn-primary" type="button"
                id="saveServiceForm"> {{ __('appointment.save') }} </button> </div>
    </div>
</div>
@push('after-scripts')
    <script>
        $(document).ready(
            function() {
                // Get encounter_id from hidden input
                var encounterId = $('#billing_encounter_id').val();
                var serviceId = $('#billing_service_id').val();
                var baseUrl = '{{ url('/') }}';
                // Fetch the service list using AJAX
                $.ajax({
                    url: baseUrl + '/app/services/index_list?encounter_id=' + encounterId + '&service_id=' +
                        serviceId,
                    method: 'GET',
                    success: function(response) {
                        if (response) {
                            // Populate the dropdown
                            var serviceOptions =
                                '<option value="">{{ __('appointment.select_service') }}</option>';
                            response.forEach(function(service) {
                                serviceOptions +=
                                    `<option value="${service.id}">${service.name}</option>`;
                            });
                            $('#service_id').html(serviceOptions);
                        } else {
                            console.error('Failed to fetch services.');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching services:', error);
                    }
                });
                $('#service_id').on('change', function() {
                    var encounterId = $('#billing_encounter_id').val();
                    var selectedServiceId = $(this).val();
                    if (selectedServiceId) {
                        // Make an API call when the service is selected
                        $.ajax({
                            url: baseUrl + '/app/services/service-details?service_id=' +
                                selectedServiceId + '&encounter_id=' + encounterId,
                            method: 'GET',
                            success: function(serviceDetails) {
                                if (serviceDetails) {
                                    if (serviceDetails && serviceDetails.data) {
                                        // Fallback to use `charges` if `doctor_service` is not available
                                        $('#charges').val(serviceDetails.data.service_price_data
                                            .doctor_charge_with_discount + serviceDetails.data
                                            .service_price_data.final_inclusive_amount);
                                        $('#service_amount').val(serviceDetails.data.doctor_service[
                                            0].charges);

                                        // Check if we're in edit mode (billing_item_id exists)
                                        var billingItemId = $('#billing_item_id').val();
                                        if (!billingItemId || billingItemId === '') {
                                            // Only set quantity to 1 if adding new service
                                            $('#quantity').val(1);
                                        }
                                        // If editing, keep the existing quantity

                                        var quantity = parseFloat($('#quantity').val()) || 1;
                                        var total = (serviceDetails.data.service_price_data
                                                .doctor_charge_with_discount + serviceDetails.data
                                                .service_price_data.final_inclusive_amount) *
                                            quantity;
                                        $('#total').val(total.toFixed(2));
                                        $('#discount_value').val(serviceDetails.data
                                            .discount_value);
                                        $('#discount_type').val(serviceDetails.data.discount_type);
                                        // $('#inclusive_tax_price').val(serviceDetails.data
                                        //     .service_price_data.final_inclusive_amount); // Tax calculation disabled
                                        // $('#inclusive_tax').val(serviceDetails.data.inclusive_tax); // Tax calculation disabled
                                    } else {
                                        // Handle case where data is insufficient
                                        $('#charges').val('');
                                        $('#quantity').val('');
                                        $('#total').val('');
                                        $('#discount_value').val('');
                                        $('#discount_type').val('');
                                        $('#service_amount').val('');
                                        // $('#inclusive_tax_price').val(''); // Tax calculation disabled
                                        // $('#inclusive_tax').val(''); // Tax calculation disabled
                                    }
                                } else {
                                    console.error('Failed to fetch service details.');
                                }
                            },
                            error: function(error) {
                                console.error('Error fetching service details:', error);
                            }
                        });
                    } else {
                        // Reset fields if no service is selected
                        $('#charges').val('');
                        $('#quantity').val('');
                        $('#total').val('');
                        $('#discount_value').val('');
                        $('#discount_type').val('');
                        $('#inclusive_tax_price').val('');
                        $('#inclusive_tax').val('');
                    }
                });
                $('#quantity, #charges').on('input', function() {
                    var quantity = parseFloat($('#quantity').val()) || 0;
                    var charges = parseFloat($('#charges').val()) || 0;
                    var total = quantity * charges;
                    $('#total').val(total.toFixed(2));
                });
                $('#saveServiceForm').on('click', function() {
                    var encounterId = $('#billing_encounter_id').val();
                    var serviceId = $('#service_id').val();
                    var charges = $('#service_amount').val();
                    var quantity = $('#quantity').val();
                    var total = $('#total').val();
                    var discount_value = $('#discount_value').val();
                    var discount_type = $('#discount_type').val();
                    var billing_id = $('#billing_id').val();
                    var billing_item_id = $('#billing_item_id').val();
                    // var inclusive_tax_amount = $('#inclusive_tax_price').val(); // Tax calculation disabled
                    // var inclusive_tax = $('#inclusive_tax').val(); // Tax calculation disabled

                    // Perform basic validation
                    if (!serviceId || !charges || !quantity || !total) {
                        alert('{{ __('appointment.fill_required_fields') }}');
                        return;
                    }

                    // Check if this is an update operation
                    var isUpdate = billing_item_id && billing_item_id !== '';

                    if (isUpdate) {
                        // Get current paid amount and total amount
                        var currentPaidAmount = parseFloat($('#installment_total_paid').text().replace(
                            /[^0-9.-]+/g, '')) || 0;
                        var currentTotalAmount = parseFloat($('#total_payable_amount').text().replace(
                            /[^0-9.-]+/g, '')) || 0;

                        // Calculate the difference in total amount that this update would cause
                        var currentServiceTotal = parseFloat($('#total_service_amount').val()) || 0;
                        var newServiceTotal = parseFloat(total) || 0;
                        var totalDifference = newServiceTotal - currentServiceTotal;

                        // Calculate new total amount after update
                        var newTotalAmount = currentTotalAmount + totalDifference;

                    }

                    // Prepare the data for the API call
                    var formData = {
                        encounter_id: encounterId,
                        item_id: serviceId,
                        service_amount: charges,
                        quantity: quantity,
                        discount_value: discount_value,
                        discount_type: discount_type,
                        billing_id: billing_id,
                        total_amount: total,
                        // inclusive_tax_amount: inclusive_tax_amount, // Tax calculation disabled
                        // inclusive_tax: inclusive_tax, // Tax calculation disabled
                        type: 'encounter_details',
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    };

                    // Determine if this is an update or add operation
                    var url = isUpdate ?
                        baseUrl + '/app/billing-record/update-billing-item' :
                        baseUrl + '/app/billing-record/save-billing-items';

                    if (isUpdate) {
                        formData.billing_item_id = billing_item_id;
                    }

                    // Make the API call
                    $.ajax({
                        url: url,
                        method: 'post',
                        data: formData,
                        success: function(response) {
                            if (response) {
                                document.getElementById('Service_list').innerHTML = ''
                                document.getElementById('Service_list').innerHTML = response.html;

                                const button = document.getElementById('toggleButton');
                                const collapse = document.getElementById('collapseExample');

                                const bootstrapCollapse = new bootstrap.Collapse(collapse);
                                bootstrapCollapse.hide();

                                // Reset form
                                $('#service_id').val(null).trigger('change');
                                $('#charges').val('');
                                $('#quantity').val('');
                                $('#total').val('');
                                $('#discount_value').val('');
                                $('#discount_type').val('');
                                $('#billing_item_id').val('');

                                // Update button text
                                if (button) {
                                    button.textContent = 'Add Item';
                                }

                                $('#service_amount').text(currencyFormat(response.service_details
                                    .service_total));
                                // $('#tax_amount').text(currencyFormat(response.service_details
                                //     .total_tax)); // Tax calculation disabled
                                $('#total_payable_amount').text(currencyFormat(response
                                    .service_details.total_amount)).attr(
                                    'data-value', response.service_details.total_amount);
                                $('#total_service_amount').val(response.service_details
                                    .service_total);
                                // $('#total_tax_amount').val(response.service_details.total_tax); // Tax calculation disabled
                                $('#total_amount').val(response.service_details.total_amount);
                                $('#discount_amount').text(currencyFormat(response.service_details
                                    .final_discount_amount));
                                $('#installment_total_paid').text(currencyFormat(response
                                    .service_details.total_paid_amount));
                                $('#installment_remaining').text(currencyFormat(response
                                    .service_details
                                    .remaining_amount));

                                // Check for refund scenario and show note
                                checkAndShowRefundNote(response.service_details.total_paid_amount ||
                                    0, response.service_details.total_amount || 0);
                            } else {

                            }
                        },
                        error: function(error) {
                            console.error('Error saving billing details:', error);
                            alert('{{ __('appointment.saving_failed') }}');
                        }
                    });
                });
            });

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
                refundSection.addClass('d-none');
            }
        }
    </script>
@endpush
