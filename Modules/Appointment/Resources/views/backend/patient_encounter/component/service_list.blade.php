<div class="card-body m-2">
    <div class="table-responsive rounded">
        <table class="table table-lg m-0" id="service_list_table">
            @php
                $allPaidAmt = $data->installments->sum('amount');
                $totalAmt = $data->billingItem->sum('total_amount');

                // Calculate total amount with tax and discount using the same logic as other files
                $subtotal = $data->billingItem->sum('total_amount');
                $totalDiscount = 0;

                // Calculate total discount from billing items
                foreach ($data->billingItem as $item) {
                    if ($item->discount_value && $item->discount_type) {
                        if ($item->discount_type == 'fixed') {
                            $totalDiscount += $item->discount_value;
                        } else {
                            // Percentage discount
                            $itemTotal = $item->service_amount * $item->quantity;
                            $totalDiscount += ($itemTotal * $item->discount_value) / 100;
                        }
                    }
                }

                // Add final discount from billing record if exists
                if ($data->final_discount == 1 && $data->final_discount_value > 0) {
                    if ($data->final_discount_type == 'fixed') {
                        $totalDiscount += $data->final_discount_value;
                    } else {
                        $totalDiscount += ($subtotal * $data->final_discount_value) / 100;
                    }
                }

                // Calculate tax using the same method as other files
                // $taxDetails = getBookingTaxamount($subtotal - $totalDiscount, null); // Tax calculation disabled
                // $totalTax = $taxDetails['total_tax_amount'] ?? 0; // Tax calculation disabled
                $totalTax = 0; // Tax calculation disabled

                $totalAmountWithTaxAndDiscount = $subtotal - $totalDiscount; // Tax calculation disabled
                // $totalAmountWithTaxAndDiscount = $subtotal - $totalDiscount + $totalTax;
                // Check if paid amount is less than total amount for edit/delete conditions
                $canEditDelete = $allPaidAmt < $totalAmountWithTaxAndDiscount;
            @endphp
            <thead>
                <tr class="text-white">
                    <th>{{ __('appointment.sr_no') }}</th>
                    <th>{{ __('appointment.lbl_services') }}</th>
                    <th>{{ __('service.discount') }}</th>
                    <th>{{ __('product.quantity') }}</th>
                    <th>{{ __('appointment.price') }}</th>
                    {{-- <th>{{ __('service.inclusive_tax') }}</th> --}}
                    <th>{{ __('appointment.total') }}</th>
                    @if ($status == 1)
                        <th>{{ __('appointment.lbl_action') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($data['billingItem'] as $index => $iteam)
                    <tr data-service-id="{{ $iteam['id'] }}">
                        <td>
                            <h6 class="text-primary">
                                {{ $index + 1 }}
                            </h6>

                        </td>
                        <td>
                            <h6 class="text-primary">
                                {{ $iteam['item_name'] }}
                            </h6>

                        </td>
                        <td>
                            <p class="m-0">
                                @if ($iteam['discount_value'] == null)
                                    -
                                @else
                                    @if ($iteam['discount_type'] == 'fixed')
                                        <span>{{ Currency::format($iteam['discount_value']) }}</span>
                                    @else
                                        <span>{{ $iteam['discount_value'] }}(%) </span>
                                    @endif
                                @endif
                            </p>
                        </td>
                        <td>
                            {{ $iteam['quantity'] }}
                        </td>
                        <td>
                            {{ Currency::format($iteam['service_amount']) }}
                        </td>
                        {{-- <td>
                            {{ Currency::format($iteam['inclusive_tax_amount']) }}
                        </td> --}}
                        <td>
                            {{ Currency::format($iteam['total_amount']) }}
                        </td>

                        @if ($status == 1)
                            <td class="action">
                                <div class="d-flex align-items-center gap-3">
                                    @if ($index !== 0)
                                        <button type="button" class="btn text-primary p-0 fs-5"
                                            onclick="editServiceWithEncounterClose({{ $iteam['id'] }})"
                                            data-bs-toggle="tooltip" title="Edit Service">
                                            <i class="ph ph-pencil"></i>
                                        </button>
                                        <button type="button" class="btn text-danger p-0 fs-5"
                                            onclick="destroyServiceData({{ $iteam['id'] }}, 'Are you sure you want to delete it?')"
                                            data-bs-toggle="tooltip">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        @endif

                    </tr>
                @endforeach


                @if (count($data['billingItem']) <= 0)
                    <tr>
                        <td colspan="7">
                            <div class="my-1 text-danger text-center">{{ __('appointment.no_service_found') }}
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>

        </table>
        <div id="service-error-message" class="alert alert-danger mt-2 d-none"></div>

    </div>
</div>

@push('after-scripts')
    <script>
        // Add this function to check service count
        function checkServiceCount() {
            // Get all billing items that have actual services (those with an ID)
            const serviceRows = document.querySelectorAll('#service_list_table tbody tr[data-service-id]');
            return serviceRows.length;
        }

        function showError(message) {
            const errorDiv = document.getElementById('service-error-message');
            errorDiv.textContent = message;
            errorDiv.classList.remove('d-none');
            setTimeout(() => {
                errorDiv.classList.add('d-none');
            }, 3000);
        }

        // Function to edit service data when closing encounter
        function editServiceData(id, message) {
            if (confirm(message)) {
                // Check if billing modal exists and trigger it
                const billingModal = document.getElementById('Billing_Modal');
                if (billingModal) {
                    // Show the billing modal
                    const modal = new bootstrap.Modal(billingModal);
                    modal.show();

                    // Wait a bit for the modal to fully load, then trigger edit
                    setTimeout(() => {
                        // Trigger the edit functionality in the Vue component
                        if (window.editBillingItem) {
                            window.editBillingItem(id);
                        } else {
                            // Fallback: try to trigger the edit via AJAX
                            editServiceViaAjax(id);
                        }
                    }, 300);
                } else {
                    // Fallback if modal doesn't exist
                    editServiceViaAjax(id);
                }
            }
        }

        // Enhanced function for editing service data with encounter closing awareness
        function editServiceWithEncounterClose(id) {
            const billingModal = document.getElementById('Billing_Modal');
            const isEncounterClosing = billingModal && billingModal.classList.contains('show');

            if (isEncounterClosing) {
                // We're already in encounter closing mode, just edit the service
                setTimeout(() => {
                    editServiceUsingExistingForm(id);
                }, 100);
            } else {
                // Open billing modal for encounter closing
                if (billingModal) {
                    const modal = new bootstrap.Modal(billingModal);
                    modal.show();

                    setTimeout(() => {
                        editServiceUsingExistingForm(id);
                    }, 300);
                } else {
                    editServiceUsingExistingForm(id);
                }
            }
        }

        // Function to edit service using the existing add_service form
        function editServiceUsingExistingForm(billingItemId) {
            var baseUrl = '{{ url('/') }}';

            // First, fetch the service list to populate the dropdown
            var encounterId = $('#billing_encounter_id').val();
            var serviceId = $('#billing_service_id').val();

            $.ajax({
                url: baseUrl + '/app/services/index_list?encounter_id=' + encounterId + '&service_id=' + serviceId,
                method: 'GET',
                success: function(servicesResponse) {
                    if (servicesResponse) {
                        // Populate the dropdown
                        var serviceOptions =
                            '<option value="">{{ __('appointment.select_service') }}</option>';
                        servicesResponse.forEach(function(service) {
                            serviceOptions += `<option value="${service.id}">${service.name}</option>`;
                        });
                        $('#service_id').html(serviceOptions);

                        // Now fetch the billing item data and populate the form
                        fetch(`${baseUrl}/app/billing-record/edit-billing-item/${billingItemId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.status && data.data) {
                                    const billingItem = data.data;

                                    // Populate the form with exact data
                                    $('#service_id').val(billingItem.item_id).trigger('change');
                                    $('#charges').val(billingItem.service_amount);
                                    $('#quantity').val(billingItem.quantity);
                                    $('#total').val(billingItem.total_amount);
                                    $('#discount_value').val(billingItem.discount_value || '');
                                    $('#discount_type').val(billingItem.discount_type || '');
                                    $('#service_amount').val(billingItem.service_amount);
                                    // $('#inclusive_tax_price').val(billingItem.inclusive_tax_amount || ''); // Tax calculation disabled
                                    // $('#inclusive_tax').val(billingItem.inclusive_tax || ''); // Tax calculation disabled

                                    // Store the billing item ID for update
                                    $('#billing_item_id').val(billingItem.id);

                                    // Show the collapse section
                                    const collapseExample = document.getElementById('collapseExample');
                                    const bootstrapCollapse = new bootstrap.Collapse(collapseExample);
                                    bootstrapCollapse.show();

                                    // Update button text to indicate editing
                                    const toggleButton = document.getElementById('toggleButton');
                                    toggleButton.textContent = 'Edit Service';

                                    console.log('Form populated with billing item data:', billingItem);
                                } else {
                                    console.error('Failed to fetch billing item data');
                                    alert('Failed to fetch service data for editing');
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching billing item data:', error);
                                alert('Error fetching service data for editing');
                            });
                    } else {
                        console.error('Failed to fetch services.');
                        alert('Failed to load service list');
                    }
                },
                error: function(error) {
                    console.error('Error fetching services:', error);
                    alert('Error loading service list');
                }
            });
        }

        // Function to show and populate the existing add_service form
        function showAddServiceForm(serviceData, billingItemId) {
            // Show the collapse section
            const collapseExample = document.getElementById('collapseExample');
            const bootstrapCollapse = new bootstrap.Collapse(collapseExample);
            bootstrapCollapse.show();

            // Update the button text
            const toggleButton = document.getElementById('toggleButton');
            toggleButton.textContent = 'Edit Service';

            // Populate the form with service data
            populateAddServiceForm(serviceData, billingItemId);

            // Scroll to the form
            setTimeout(() => {
                document.getElementById('extra-service-list').scrollIntoView({
                    behavior: 'smooth'
                });
            }, 300);
        }

        // Function to populate the add_service form with existing data
        function populateAddServiceForm(serviceData, billingItemId) {
            // Set form values
            $('#service_id').val(serviceData.item_id).trigger('change');
            $('#charges').val(serviceData.service_amount);
            $('#quantity').val(serviceData.quantity);
            $('#total').val(serviceData.total_amount);
            $('#discount_value').val(serviceData.discount_value || '');
            $('#discount_type').val(serviceData.discount_type || '');
            $('#service_amount').val(serviceData.service_amount);
            // $('#inclusive_tax_price').val(serviceData.inclusive_tax_amount || ''); // Tax calculation disabled
            // $('#inclusive_tax').val(serviceData.inclusive_tax || ''); // Tax calculation disabled

            // Update the save button to handle edit mode
            $('#saveServiceForm').off('click').on('click', function() {
                saveEditedService(billingItemId);
            });

            // Update button text
            $('#saveServiceForm').text('Update Service');
        }

        // Function to reset the add service form
        function resetAddServiceForm() {
            // Clear all form fields
            $('#service_id').val('').trigger('change');
            $('#charges').val('');
            $('#quantity').val('1');
            $('#total').val('');
            $('#discount_value').val('');
            $('#discount_type').val('');
            $('#service_amount').val('');
            // $('#inclusive_tax_price').val(''); // Tax calculation disabled
            // $('#inclusive_tax').val(''); // Tax calculation disabled
            $('#billing_item_id').val('');

            // Reset form action
            $('#add_service_form').attr('data-action', 'add');
            $('#add_service_form').removeAttr('data-billing-item-id');

            // Update button text
            const toggleButton = document.getElementById('toggleButton');
            if (toggleButton) {
                toggleButton.textContent = 'Add Item';
            }
        }

        // Function to handle toggle button click for adding new service
        function handleAddNewService() {
            // Reset the form completely
            resetAddServiceForm();

            // Clear any stored billing item ID
            $('#billing_item_id').val('');

            // Update button text
            const toggleButton = document.getElementById('toggleButton');
            toggleButton.textContent = 'Add Item';

            // Reset form action to add
            $('#add_service_form').attr('data-action', 'add');
            $('#add_service_form').removeAttr('data-billing-item-id');

            // Show the collapse section
            const collapseExample = document.getElementById('collapseExample');
            const bootstrapCollapse = new bootstrap.Collapse(collapseExample);
            bootstrapCollapse.show();
        }

        // Function to toggle the add service form (open/close)
        function toggleAddServiceForm() {
            const toggleButton = document.getElementById('toggleButton');
            const collapseExample = document.getElementById('collapseExample');
            const isExpanded = collapseExample.classList.contains('show');

            if (isExpanded) {
                // Form is open, so close it
                const bootstrapCollapse = new bootstrap.Collapse(collapseExample);
                bootstrapCollapse.hide();
            } else {
                // Form is closed, so open it for adding new service
                handleAddNewService();
            }
        }

        // Add event listeners for collapse events
        document.addEventListener('DOMContentLoaded', function() {
            const collapseExample = document.getElementById('collapseExample');
            if (collapseExample) {
                collapseExample.addEventListener('hidden.bs.collapse', function() {
                    // Reset form when collapse is hidden
                    resetAddServiceForm();

                    // Reset button text
                    const toggleButton = document.getElementById('toggleButton');
                    if (toggleButton) {
                        toggleButton.textContent = 'Add Item';
                    }
                });

                collapseExample.addEventListener('shown.bs.collapse', function() {
                    // Update button text when form is shown
                    const toggleButton = document.getElementById('toggleButton');
                    if (toggleButton) {
                        // Check if we're in edit mode
                        const billingItemId = $('#billing_item_id').val();
                        if (billingItemId && billingItemId !== '') {
                            toggleButton.textContent = 'Edit Service';
                        } else {
                            toggleButton.textContent = 'Close';
                        }
                    }
                });
            }
        });

        // Function to save edited service
        function saveEditedService(billingItemId) {
            var baseUrl = '{{ url('/') }}';
            var encounterId = $('#billing_encounter_id').val();
            var serviceId = $('#service_id').val();
            var charges = $('#service_amount').val();
            var quantity = $('#quantity').val();
            var total = $('#total').val();
            var discount_value = $('#discount_value').val();
            var discount_type = $('#discount_type').val();
            var billing_id = $('#billing_id').val();
            // var inclusive_tax_amount = $('#inclusive_tax_price').val(); // Tax calculation disabled
            // var inclusive_tax = $('#inclusive_tax').val(); // Tax calculation disabled

            // Perform basic validation
            if (!serviceId || !charges || !quantity || !total) {
                alert('{{ __('appointment.fill_required_fields') }}');
                return;
            }

            // Prepare the data for the API call
            var formData = {
                billing_item_id: billingItemId,
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
                _token: '{{ csrf_token() }}'
            };

            // Make the API call to update the billing item
            $.ajax({
                url: baseUrl + '/app/billing-record/update-billing-item',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response && response.status) {
                        // Update the service list
                        document.getElementById('Service_list').innerHTML = '';
                        document.getElementById('Service_list').innerHTML = response.html;

                        // Update totals
                        $('#service_amount').text(currencyFormat(response.service_details.service_total));
                        // $('#tax_amount').text(currencyFormat(response.service_details.total_tax)); // Tax calculation disabled
                        $('#total_payable_amount').text(currencyFormat(response.service_details.total_amount));
                        $('#total_service_amount').val(response.service_details.service_total);
                        // $('#total_tax_amount').val(response.service_details.total_tax); // Tax calculation disabled
                        $('#total_amount').val(response.service_details.total_amount);
                        $('#discount_amount').text(currencyFormat(response.service_details
                            .final_discount_amount));

                        // Check for refund scenario and show note
                        checkAndShowRefundNote(response.service_details.total_paid_amount || 0, response
                            .service_details.total_amount || 0);

                        // Hide the collapse and reset form
                        const collapseExample = document.getElementById('collapseExample');
                        const bootstrapCollapse = bootstrap.Collapse.getInstance(collapseExample);
                        if (bootstrapCollapse) {
                            bootstrapCollapse.hide();
                        }

                        // Reset form
                        resetAddServiceForm();

                        window.successSnackbar('Service updated successfully');
                    } else {
                        window.errorSnackbar('Failed to update service');
                    }
                },
                error: function(error) {
                    console.error('Error updating service:', error);
                    window.errorSnackbar('Error updating service');
                }
            });
        }

        // Fallback function to edit service via AJAX
        function editServiceViaAjax(id) {
            var baseUrl = '{{ url('/') }}';

            $.ajax({
                url: baseUrl + '/app/billing-record/edit-billing-item/' + id,
                method: 'GET',
                success: function(response) {
                    if (response && response.status) {
                        // Update form fields with the service data
                        $('#service_id').val(response.data.item_id).trigger('change');
                        $('#charges').val(response.data.service_amount);
                        $('#quantity').val(response.data.quantity);
                        $('#total').val(response.data.total_amount);
                        $('#discount_type').val(response.data.discount_type);
                        $('#discount_value').val(response.data.discount_value);

                        // Show success message
                        window.successSnackbar('Service data loaded for editing');
                    } else {
                        window.errorSnackbar('Failed to load service data');
                    }
                },
                error: function(error) {
                    console.error('Error loading service data:', error);
                    window.errorSnackbar('Error loading service data');
                }
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
                refundSection.addClass('d-none');
            }
        }

        function destroyServiceData(id) {
            const serviceCount = checkServiceCount();
            console.log(serviceCount)
            if (serviceCount <= 1) {
                showError('At least one service is required. Cannot delete the All service.');
                return;
            }

            var baseUrl = '{{ url('/') }}';

            $.ajax({
                url: baseUrl + '/app/billing-record/delete-billing-item/' + id,
                method: 'GET',

                success: function(response) {

                    if (response) {

                        document.getElementById('Service_list').innerHTML = ''

                        document.getElementById('Service_list').innerHTML = response.html;

                        $('#service_id').val(null).trigger('change');
                        $('#charges').val('');
                        $('#quantity').val('');
                        $('#total').val('');
                        $('#discount_value').val('');
                        $('#discount_type').val('');
                        $('#service_amount').text(currencyFormat(response.service_details.service_total));
                        // $('#tax_amount').text(currencyFormat(response.service_details.total_tax)); // Tax calculation disabled
                        $('#total_payable_amount').text(currencyFormat(response.service_details.total_amount));
                        $('#total_service_amount').val(response.service_details.service_total);
                        // $('#total_tax_amount').val(response.service_details.total_tax); // Tax calculation disabled
                        $('#total_amount').val(response.service_details.total_amount);
                        $('#discount_amount').text(currencyFormat(response.service_details
                            .final_discount_amount));

                        // Check for refund scenario and show note
                        checkAndShowRefundNote(response.service_details.total_paid_amount || 0, response
                            .service_details.total_amount || 0);

                        // After updating the service list, recheck the count
                        const updatedCount = checkServiceCount();

                        // Get all delete buttons and update their state
                        const deleteButtons = document.querySelectorAll('.delete-service-btn');
                        deleteButtons.forEach(btn => {
                            if (updatedCount <= 1) {
                                btn.setAttribute('disabled', 'disabled');
                                btn.setAttribute('title',
                                    '{{ __('appointment.cannot_delete_last_service') }}');
                            } else {
                                btn.removeAttribute('disabled');
                                btn.removeAttribute('title');
                            }
                        });

                    } else {

                        console.error('Failed to fetch services.');
                    }
                },
                error: function(error) {
                    console.error('Error fetching services:', error);
                }
            });

        }
    </script>
@endpush
