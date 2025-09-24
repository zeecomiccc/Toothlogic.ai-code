<style>
    .shade-value {
        padding: 4px 12px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-weight: 500;
        color: #495057;
        display: inline-block;
        min-width: 100px;
        text-align: center;
    }
    
    .shade-value.no-data {
        background-color: #e9ecef;
        color: #6c757d;
        font-style: italic;
    }
    
    .shade-value.error {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    /* Media Files Display Styles */
    .media-file-item {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        transition: all 0.2s ease;
        height: 100%;
    }

    .media-file-item:hover {
        background: #e9ecef;
        border-color: #00C2CB;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .file-name {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-top: 8px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        line-height: 1.3;
        max-height: 3.9em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .file-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .file-actions {
        margin-top: 10px;
    }

    .file-actions .btn {
        font-size: 12px;
        padding: 4px 8px;
    }

    .media-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .media-title {
        margin: 0;
        color: #495057;
        font-weight: 600;
    }

    .media-count {
        background: #00C2CB;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .no-media-files {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .no-media-files i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #dee2e6;
    }
</style>

<!-- Show Lab Modal -->
<div class="offcanvas offcanvas-end offcanvas-w-40" tabindex="-1" id="showLabModal" aria-labelledby="showLabModalLabel">
    <div class="offcanvas-header">
        <div class="offcanvas-header border-bottom">
            <h4 class="offcanvas-title" id="showLabModalLabel">{{ __('lab.lab_report') }}</h4>
            <input type="hidden" id="form_mode" value="create">

        </div>
        <button type="button" data-bs-dismiss="offcanvas" aria-label="Close" class="btn-close-offcanvas">
            <i class="ph ph-x-circle"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <!-- Patient Information Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="mb-3"><i class="ph ph-user me-2"></i>{{ __('lab.patient_information') }}</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <img id="show-patient-avatar" src="{{ asset('public/images/default-avatar.png') }}"
                                    class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1" id="show-patient-name">Patient Name</h5>
                                <div class="text-muted mb-2">
                                    <i class="ph ph-envelope me-1"></i>
                                    <span id="show-patient-email">patient@email.com</span>
                                </div>
                                <div class="text-primary mb-2">
                                    <i class="ph ph-phone me-1"></i>
                                    <span id="show-patient-phone">+1234567890</span>
                                </div>
                                <div class="d-flex gap-4">
                                    <div class="text-center">
                                        <div class="fw-bold text-primary" id="show-total-appointments">0</div>
                                        <small class="text-muted">{{ __('lab.total_appointments') }}</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="fw-bold text-primary" id="show-total-labs">0</div>
                                        <small class="text-muted">{{ __('lab.lab_report') }}s</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lab Information Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-info me-2"></i>{{ __('lab.basic_information') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-4 fw-bold">{{ __('lab.report_id') }}:</div>
                            <div class="col-8" id="show-lab-id"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold">{{ __('lab.doctor') }}:</div>
                            <div class="col-8" id="show-doctor-name"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold">{{ __('lab.case_type') }}:</div>
                            <div class="col-8" id="show-case-type"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold">{{ __('lab.case_status') }}:</div>
                            <div class="col-8" id="show-case-status"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold">{{ __('lab.date') }}:</div>
                            <div class="col-8" id="show-created-at"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold">{{ __('lab.delivery_date_estimate') }}:</div>
                            <div class="col-8" id="show-delivery-date"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Treatment Plan -->
            <div class="col-md-6">
                {{-- <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-clipboard-text me-2"></i>{{ __('lab.treatment_plan') }}</h6>
                    </div>
                    <div class="card-body">
                        <div id="show-treatment-plan" class="text-muted"></div>
                    </div>
                </div> --}}

                <!-- Rx Instructions -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-prescription me-2"></i>{{ __('lab.rx_instructions') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6 fw-bold">{{ __('lab.margin_location') }}:</div>
                            <div class="col-6" id="show-rx-margin-location"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 fw-bold">{{ __('lab.contact_tightness') }}:</div>
                            <div class="col-6" id="show-rx-contact-tightness"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 fw-bold">{{ __('lab.occlusal_scheme') }}:</div>
                            <div class="col-6" id="show-rx-occlusal-scheme"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 fw-bold">{{ __('lab.temporary_placed') }}:</div>
                            <div class="col-6" id="show-rx-temporary-placed"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Notes Section - Full Width -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-note me-2"></i>{{ __('lab.notes') }}</h6>
                    </div>
                    <div class="card-body">
                        <div id="show-notes" class="text-muted"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teeth Treatment Type Section - Full Width -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-teeth me-2"></i>{{ __('lab.select_teeth_for_treatment') }}</h6>
                    </div>
                    <div class="card-body">
                        <div id="show-teeth-treatment-type-full" class="teeth-treatment-display-full">
                            <div class="row mb-2">
                                <div class="col-6 fw-bold">{{ __('lab.select_teeth_for_treatment') }}:</div>
                                <div class="col-6" id="show-rx-teeth-treatment-type">
                                    <div class="teeth-treatment-display"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shade Selection Section - Full Width -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-palette me-2"></i>{{ __('lab.shade_selection') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6 fw-bold">{{ __('lab.cervical_shade_selection') }}:</div>
                            <div class="col-6" id="show-cervical-shade"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 fw-bold">{{ __('lab.middle_shade_selection') }}:</div>
                            <div class="col-6" id="show-middle-shade"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 fw-bold">{{ __('lab.incisal_shade_selection') }}:</div>
                            <div class="col-6" id="show-incisal-shade"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Media Files -->
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3"><i class="ph ph-images me-2"></i>{{ __('lab.uploaded_files') }}</h5>
                <div class="show-media-section">
                    <!-- Media files will be populated here in 2 rows -->
                </div>
            </div>
        </div>
    </div>
</div>