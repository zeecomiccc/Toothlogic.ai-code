<style>
    .teeth-title {
        color: #00C2CB;
        padding: 4px;
        font-size: 29px;
        font-weight: bold;
        margin-bottom: 0;
    }

    .teeth-diagram {
        padding: 30px;
        position: relative;
        background: white;
        height: 200px;
    }

    .teeth-cross {
        position: relative;
        width: 100%;
        height: 75%;
    }

    /* Select2 Multi-select improvements */
    .select2-container--default .select2-selection--multiple {
        overflow-y: auto !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        border: none !important;
        padding: 4px 12px !important;
        font-size: 13px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white !important;
        margin-right: 8px !important;
        font-weight: bold !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #f8f9fa !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        padding: 8px 12px !important;
    }

    /* Teeth Button Styles */
    .teeth-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 2px solid #ddd;
        background-color: #f8f9fa;
        color: #495057;
        font-weight: bold;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        outline: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .teeth-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border-color: #00C2CB;
    }

    .teeth-btn.selected {
        border-color: #00C2CB;
        border-width: 3px;
        background-color: #00C2CB;
        color: white;
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .teeth-btn.selected::before {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-weight: bold;
        font-size: 14px;
        text-shadow: 0 0 3px rgba(0, 0, 0, 0.8);
    }

    /* Responsive Teeth Layout - Always keep in single line */
    .teeth-treatment-container {
        display: flex;
        flex-wrap: nowrap;
        gap: 15px;
        width: 100%;
        min-width: 0;
    }

    .teeth-quadrant {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .teeth-buttons-row {
        display: flex;
        flex-wrap: nowrap;
        gap: 4px;
        justify-content: center;
        margin-bottom: 15px;
        overflow-x: auto;
        padding: 5px 0;
    }

    .teeth-buttons-row::-webkit-scrollbar {
        height: 2px;
    }

    .teeth-buttons-row::-webkit-scrollbar-track {
        background: transparent;
    }

    .teeth-buttons-row::-webkit-scrollbar-thumb {
        background: rgba(0, 194, 203, 0.3);
        border-radius: 1px;
    }

    .teeth-buttons-row::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 194, 203, 0.5);
    }

    /* Hide scrollbar when not needed */
    .teeth-buttons-row:not(:hover)::-webkit-scrollbar {
        height: 0px;
    }

    /* Responsive teeth button sizing */
    @media (max-width: 1200px) {
        .teeth-btn {
            width: 30px;
            height: 30px;
            font-size: 11px;
        }
    }

    @media (max-width: 992px) {
        .teeth-btn {
            width: 28px;
            height: 28px;
            font-size: 10px;
        }
        
        .teeth-treatment-container {
            gap: 10px;
        }
    }

    @media (max-width: 768px) {
        .teeth-btn {
            width: 25px;
            height: 25px;
            font-size: 9px;
        }
        
        .teeth-treatment-container {
            gap: 8px;
        }
        
        .teeth-buttons-row {
            gap: 2px;
        }
    }

    @media (max-width: 576px) {
        .teeth-btn {
            width: 22px;
            height: 22px;
            font-size: 8px;
        }
        
        .teeth-treatment-container {
            gap: 5px;
        }
        
        .teeth-buttons-row {
            gap: 1px;
        }
    }

    /* Ensure select dropdowns don't break layout */
    .teeth-quadrant .form-select {
        min-width: 0;
        width: 100% !important;
    }

    /* Force Select2 dropdowns to take full width */
    .teeth-quadrant .select2-container {
        width: 100% !important;
    }

    .teeth-quadrant .select2-container .select2-selection--multiple {
        width: 100% !important;
        min-height: 80px !important;
    }

    .teeth-quadrant .select2-container .select2-selection--multiple .select2-selection__rendered {
        width: 100% !important;
        padding: 8px 12px !important;
    }

    /* Quadrant labels */
    .teeth-quadrant h6 {
        font-size: 14px;
        margin-bottom: 8px;
        text-align: center;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .teeth-quadrant h6 {
            font-size: 12px;
        }
    }

    @media (max-width: 576px) {
        .teeth-quadrant h6 {
            font-size: 11px;
        }
    }

    /* Custom offcanvas width for this form only */
    .offcanvas.offcanvas-w-50 {
        width: 50%;
    }
</style>

<div class="offcanvas offcanvas-end offcanvas-w-50" tabindex="-1" id="addLabModel" aria-labelledby="addLabModelLabel">
    <div class="offcanvas-header">
        <div class="offcanvas-header border-bottom">
            <h4 class="offcanvas-title" id="addLabModelLabel">{{ __('lab.create_lab_report') }}</h4>
            <input type="hidden" id="form_mode" value="create">
        </div>
        <button type="button" data-bs-dismiss="offcanvas" aria-label="Close" class="btn-close-offcanvas">
            <i class="ph ph-x-circle"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <form id="lab-form-submit" method="POST" action="{{ route('backend.lab.store') }}"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="lab_id" id="lab_id" value="">
            <div class="row">
                <div class="col-12">
                    <h5>{{ __('lab.basic_information') }}</h5>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">{{ __('lab.patient') }}</label>
                    <select name="patient_id" class="form-select select2" required>
                        <option value="">{{ __('lab.select_patient') }}</option>
                        @foreach($patients ?? [] as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">{{ __('lab.doctor') }}</label>
                    <select name="doctor_id" class="form-select select2" required>
                        <option value="">{{ __('lab.select_doctor') }}</option>
                        @foreach($doctors ?? [] as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.case_type') }}</label>
                    <select name="case_type" class="form-select select2">
                        <option value="">{{ __('lab.select_type') }}</option>
                        {{-- <option value="clear_aligner">{{ __('lab.clear_aligner') }}</option> --}}
                        <option value="crown">{{ __('lab.crown') }}</option>
                        <option value="bridge">{{ __('lab.bridge') }}</option>
                        <option value="veneers">{{ __('lab.veneers') }}</option>
                        <option value="partial_denture">{{ __('lab.partial_denture') }}</option>
                        <option value="complete_denture">{{ __('lab.complete_denture') }}</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.case_status') }}</label>
                    <select name="case_status" class="form-select select2">
                        <option value="created">{{ __('lab.created') }}</option>
                        <option value="in_progress">{{ __('lab.in_progress') }}</option>
                        <option value="sent_to_lab">{{ __('lab.sent_to_lab') }}</option>
                        <option value="delivered">{{ __('lab.delivered') }}</option>
                        <option value="seated">{{ __('lab.seated') }}</option>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.delivery_date_estimate') }}</label>
                    <input name="delivery_date_estimate" type="date" class="form-control" />
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>
                {{-- <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.treatment_plan_link') }}</label>
                    <input type="text" name="treatment_plan_link" class="form-control"
                        placeholder="{{ __('lab.treatment_plan_link') }}">
                </div> --}}
            </div>

            <!-- Impression Types Section -->
            <div class="row">
                <div class="col-12">
                    <h5 class="section-title">{{ __('lab.impression_types') }}</h5>
                </div>
            </div>

            <div class="row mb-4">
                {{-- <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.clear_aligner_impression_type') }}</label>
                    <select name="clear_aligner_impression_type" class="form-select select2">
                        <option value="">{{ __('lab.select_type') }}</option>
                        <option value="physical">{{ __('lab.physical') }}</option>
                        <option value="digital">{{ __('lab.digital') }}</option>
                    </select>
                </div> --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.prosthodontic_impression_type') }}</label>
                    <select name="prosthodontic_impression_type" class="form-select select2">
                        <option value="">{{ __('lab.select_type') }}</option>
                        <option value="physical">{{ __('lab.physical') }}</option>
                        <option value="digital">{{ __('lab.digital') }}</option>
                    </select>
                </div>
            </div>

            <!-- Clear Aligner Uploads Section -->
            {{-- <div class="row">
                <div class="col-12">
                    <h5 class="section-title">{{ __('lab.clear_aligner_uploads') }}</h5>
                </div>
            </div> --}}

            {{-- <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.intraoral_scans') }}</label>
                    <input type="file" name="clear_aligner_intraoral[]" class="form-control" multiple
                        accept=".stl,.obj,.pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom"
                        onchange="previewFiles(this, 'preview-intraoral')">
                    <div id="preview-intraoral" class="file-preview mt-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.pics_extra_intraoral') }}</label>
                    <input type="file" name="clear_aligner_pics[]" class="form-control" multiple
                        accept=".stl,.obj,.pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom"
                        onchange="previewFiles(this, 'preview-pics')">
                    <div id="preview-pics" class="file-preview mt-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.other_attachments_clear_aligner') }}</label>
                    <input type="file" name="clear_aligner_others[]" class="form-control" multiple
                        accept=".stl,.obj,.pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom,.zip,.rar,.7z"
                        onchange="previewFiles(this, 'preview-aligner-others')">
                    <div id="preview-aligner-others" class="file-preview mt-2"></div>
                </div>
            </div> --}}
            
            <!-- Prosthodontic Uploads -->
            <div class="row mt-4">
                <div class="col-12">
                    <h5>{{ __('lab.prosthodontic_uploads') }}</h5>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.prep_scans') }}</label>
                    <input type="file" name="prostho_prep_scans[]" class="form-control" multiple
                        accept=".stl,.obj,.pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom"
                        onchange="previewFiles(this, 'preview-prep-scans')">
                    <div id="preview-prep-scans" class="file-preview mt-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.bite_scans_prostho') }}</label>
                    <input type="file" name="prostho_bite_scans[]" class="form-control" multiple
                        accept=".stl,.obj,.pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom"
                        onchange="previewFiles(this, 'preview-bite-scans')">
                    <div id="preview-bite-scans" class="file-preview mt-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.preop_images_pictures') }}</label>
                    <input type="file" name="prostho_preop_pictures[]" class="form-control" multiple
                        accept=".stl,.obj,.pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom"
                        onchange="previewFiles(this, 'preview-preop-pictures')">
                    <div id="preview-preop-pictures" class="file-preview mt-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.other_attachments_prostho') }}</label>
                    <input type="file" name="prostho_others[]" class="form-control" multiple
                        accept=".stl,.obj,.pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom,.zip,.rar,.7z"
                        onchange="previewFiles(this, 'preview-prostho-others')">
                    <div id="preview-prostho-others" class="file-preview mt-2"></div>
                </div>
            </div>
            <!-- Rx Uploads -->
            {{-- <div class="row mt-4">
                <div class="col-12">
                    <h5>{{ __('lab.rx_uploads') }}</h5>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.prep_scan_or_impression_rx') }}</label>
                    <input type="file" name="rx_prep_scan[]" class="form-control" multiple
                        accept=".stl,.obj,.pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.dicom"
                        onchange="previewFiles(this, 'preview-rx-prep-scan')">
                    <div id="preview-rx-prep-scan" class="file-preview mt-2"></div>
                </div> --}}
                {{-- <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.bite_scan_rx') }}</label>
                    <input type="file" name="media_rx_bite_scan[]" class="form-control" multiple
                        onchange="previewFiles(this, 'preview-rx-bite-scan')">
                    <div id="preview-rx-bite-scan" class="file-preview mt-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.preop_images_rx') }}</label>
                    <input type="file" name="media_rx_preop_images[]" class="form-control" multiple
                        onchange="previewFiles(this, 'preview-rx-preop-images')">
                    <div id="preview-rx-preop-images" class="file-preview mt-2"></div>
                </div> --}}
                {{--
            </div> --}}

            <!-- Rx Instructions Section -->
            <div class="row">
                <div class="col-12">
                    <h5 class="section-title">{{ __('lab.rx_instructions') }}</h5>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="d-flex justify-content-center">
                <label class="teeth-title">{{ __('lab.select_teeth_numbers_and_treatment') }}</label>
            </div>
                <br>
                <!-- Upper Row: UR and UL -->
                <div class="teeth-treatment-container mb-3">
                    <div class="teeth-quadrant">
                        <h6 class="text-center text-primary">UR</h6>
                        <div class="teeth-buttons-row">
                            <button type="button" class="teeth-btn" data-teeth="18" data-quadrant="ur">18</button>
                            <button type="button" class="teeth-btn" data-teeth="17" data-quadrant="ur">17</button>
                            <button type="button" class="teeth-btn" data-teeth="16" data-quadrant="ur">16</button>
                            <button type="button" class="teeth-btn" data-teeth="15" data-quadrant="ur">15</button>
                            <button type="button" class="teeth-btn" data-teeth="14" data-quadrant="ur">14</button>
                            <button type="button" class="teeth-btn" data-teeth="13" data-quadrant="ur">13</button>
                            <button type="button" class="teeth-btn" data-teeth="12" data-quadrant="ur">12</button>
                            <button type="button" class="teeth-btn" data-teeth="11" data-quadrant="ur">11</button>
                        </div>
                        <label class="form-label">{{ __('lab.select_teeth_for_treatment') }}</label>
                        <select name="ur_treatment_type" class="form-select select2" multiple style="min-height: 80px;">
                            <option value="zirconia_crown_bridge">{{ __('lab.zirconia_crown_bridge') }}</option>
                            <option value="pfm_crown_bridge">{{ __('lab.pfm_crown_bridge') }}</option>
                            <option value="lithium_disilicate_crown_bridge">{{ __('lab.lithium_disilicate_crown_bridge') }}</option>
                            <option value="temporary_crown_pmma">{{ __('lab.temporary_crown_pmma') }}</option>
                            <option value="veneer_zirconia_lithium_disilicate">{{ __('lab.veneer_zirconia_lithium_disilicate') }}</option>
                            <option value="snap_on_smile_veneers">{{ __('lab.snap_on_smile_veneers') }}</option>
                            <option value="partial_denture_cast_acrylic">{{ __('lab.partial_denture_cast_acrylic') }}</option>
                            <option value="complete_denture">{{ __('lab.complete_denture') }}</option>
                        </select>
                    </div>

                    <div class="teeth-quadrant">
                        <h6 class="text-center text-primary">UL</h6>
                        <div class="teeth-buttons-row">
                            <button type="button" class="teeth-btn" data-teeth="21" data-quadrant="ul">21</button>
                            <button type="button" class="teeth-btn" data-teeth="22" data-quadrant="ul">22</button>
                            <button type="button" class="teeth-btn" data-teeth="23" data-quadrant="ul">23</button>
                            <button type="button" class="teeth-btn" data-teeth="24" data-quadrant="ul">24</button>
                            <button type="button" class="teeth-btn" data-teeth="25" data-quadrant="ul">25</button>
                            <button type="button" class="teeth-btn" data-teeth="26" data-quadrant="ul">26</button>
                            <button type="button" class="teeth-btn" data-teeth="27" data-quadrant="ul">27</button>
                            <button type="button" class="teeth-btn" data-teeth="28" data-quadrant="ul">28</button>
                        </div>
                        <label class="form-label">{{ __('lab.select_teeth_for_treatment') }}</label>
                        <select name="ul_treatment_type" class="form-select select2" multiple style="min-height: 80px;">
                            <option value="zirconia_crown_bridge">{{ __('lab.zirconia_crown_bridge') }}</option>
                            <option value="pfm_crown_bridge">{{ __('lab.pfm_crown_bridge') }}</option>
                            <option value="lithium_disilicate_crown_bridge">{{ __('lab.lithium_disilicate_crown_bridge') }}</option>
                            <option value="temporary_crown_pmma">{{ __('lab.temporary_crown_pmma') }}</option>
                            <option value="veneer_zirconia_lithium_disilicate">{{ __('lab.veneer_zirconia_lithium_disilicate') }}</option>
                            <option value="snap_on_smile_veneers">{{ __('lab.snap_on_smile_veneers') }}</option>
                            <option value="partial_denture_cast_acrylic">{{ __('lab.partial_denture_cast_acrylic') }}</option>
                            <option value="complete_denture">{{ __('lab.complete_denture') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Lower Row: LR and LL -->
                <div class="teeth-treatment-container mb-3">
                    <div class="teeth-quadrant">
                        <h6 class="text-center text-primary">LR</h6>
                        <div class="teeth-buttons-row">
                            <button type="button" class="teeth-btn" data-teeth="48" data-quadrant="lr">48</button>
                            <button type="button" class="teeth-btn" data-teeth="47" data-quadrant="lr">47</button>
                            <button type="button" class="teeth-btn" data-teeth="46" data-quadrant="lr">46</button>
                            <button type="button" class="teeth-btn" data-teeth="45" data-quadrant="lr">45</button>
                            <button type="button" class="teeth-btn" data-teeth="44" data-quadrant="lr">44</button>
                            <button type="button" class="teeth-btn" data-teeth="43" data-quadrant="lr">43</button>
                            <button type="button" class="teeth-btn" data-teeth="42" data-quadrant="lr">42</button>
                            <button type="button" class="teeth-btn" data-teeth="41" data-quadrant="lr">41</button>
                        </div>
                        <label class="form-label">{{ __('lab.select_teeth_for_treatment') }}</label>
                        <select name="lr_treatment_type" class="form-select select2" multiple style="min-height: 80px;">
                            <option value="zirconia_crown_bridge">{{ __('lab.zirconia_crown_bridge') }}</option>
                            <option value="pfm_crown_bridge">{{ __('lab.pfm_crown_bridge') }}</option>
                            <option value="lithium_disilicate_crown_bridge">{{ __('lab.lithium_disilicate_crown_bridge') }}</option>
                            <option value="temporary_crown_pmma">{{ __('lab.temporary_crown_pmma') }}</option>
                            <option value="veneer_zirconia_lithium_disilicate">{{ __('lab.veneer_zirconia_lithium_disilicate') }}</option>
                            <option value="snap_on_smile_veneers">{{ __('lab.snap_on_smile_veneers') }}</option>
                            <option value="partial_denture_cast_acrylic">{{ __('lab.partial_denture_cast_acrylic') }}</option>
                            <option value="complete_denture">{{ __('lab.complete_denture') }}</option>
                        </select>
                    </div>

                    <div class="teeth-quadrant">
                        <h6 class="text-center text-primary">LL</h6>
                        <div class="teeth-buttons-row">
                            <button type="button" class="teeth-btn" data-teeth="31" data-quadrant="ll">31</button>
                            <button type="button" class="teeth-btn" data-teeth="32" data-quadrant="ll">32</button>
                            <button type="button" class="teeth-btn" data-teeth="33" data-quadrant="ll">33</button>
                            <button type="button" class="teeth-btn" data-teeth="34" data-quadrant="ll">34</button>
                            <button type="button" class="teeth-btn" data-teeth="35" data-quadrant="ll">35</button>
                            <button type="button" class="teeth-btn" data-teeth="36" data-quadrant="ll">36</button>
                            <button type="button" class="teeth-btn" data-teeth="37" data-quadrant="ll">37</button>
                            <button type="button" class="teeth-btn" data-teeth="38" data-quadrant="ll">38</button>
                        </div>
                        <label class="form-label">{{ __('lab.select_teeth_for_treatment') }}</label>
                        <select name="ll_treatment_type" class="form-select select2" multiple style="min-height: 80px;">
                            <option value="zirconia_crown_bridge">{{ __('lab.zirconia_crown_bridge') }}</option>
                            <option value="pfm_crown_bridge">{{ __('lab.pfm_crown_bridge') }}</option>
                            <option value="lithium_disilicate_crown_bridge">{{ __('lab.lithium_disilicate_crown_bridge') }}</option>
                            <option value="temporary_crown_pmma">{{ __('lab.temporary_crown_pmma') }}</option>
                            <option value="veneer_zirconia_lithium_disilicate">{{ __('lab.veneer_zirconia_lithium_disilicate') }}</option>
                            <option value="snap_on_smile_veneers">{{ __('lab.snap_on_smile_veneers') }}</option>
                            <option value="partial_denture_cast_acrylic">{{ __('lab.partial_denture_cast_acrylic') }}</option>
                            <option value="complete_denture">{{ __('lab.complete_denture') }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-text">{{ __('lab.select_teeth_numbers_help') }}</div>
                
                <input type="hidden" name="teeth_treatment_type" id="teeth_treatment_type_input" value="">
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.cervical_shade_selection') }}</label>
                    <input type="text" name="cervical_shade" id="cervical_shade_input" class="form-control" placeholder="Enter cervical shade">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.middle_shade_selection') }}</label>
                    <input type="text" name="general_shade" id="general_shade_input" class="form-control" placeholder="Enter middle shade">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.incisal_shade_selection') }}</label>
                    <input type="text" name="incisal_shade" id="incisal_shade_input" class="form-control" placeholder="Enter incisal shade">
                </div>
            </div>

            <input type="hidden" name="shade_selection" id="shade_selection_input" value="">

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.margin_location') }}</label>
                    <input type="text" name="margin_location" class="form-control" placeholder="e.g., supra, gingival">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.contact_tightness') }}</label>
                    <input type="text" name="contact_tightness" class="form-control">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.occlusal_scheme') }}</label>
                    <input type="text" name="occlusal_scheme" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('lab.temporary_placed') }}</label>
                    <div class="mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="temporary_placed"
                                id="temporary_placed_yes" value="1">
                            <label class="form-check-label" for="temporary_placed_yes">{{ __('lab.yes') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="temporary_placed"
                                id="temporary_placed_no" value="0">
                            <label class="form-check-label" for="temporary_placed_no">{{ __('lab.no') }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="offcanvas">{{ __('lab.close')}}</button>
                <button class="btn btn-primary" type="submit">{{ __('lab.save') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const treatmentSelects = [
            'ur_treatment_type',
            'ul_treatment_type', 
            'lr_treatment_type',
            'll_treatment_type'
        ];

        treatmentSelects.forEach(selectName => {
            const select = document.querySelector(`select[name="${selectName}"]`);
            if (select) {
                $(select).select2({
                    placeholder: 'Select treatments...',
                    allowClear: true,
                    width: '100%'
                });

                $(select).on('change', function(e) {
                    updateTreatmentDisplay();
                    updateTeethSelection();
                });
            }
        });

        const teethButtons = document.querySelectorAll('.teeth-btn');
        
        let teethData = {
            ur: [],
            ul: [],
            lr: [],
            ll: []
        };
        
        teethButtons.forEach(button => {
            button.addEventListener('click', function() {
                const teethNumber = this.getAttribute('data-teeth');
                const quadrant = this.getAttribute('data-quadrant');
                
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    const index = teethData[quadrant].indexOf(teethNumber);
                    if (index > -1) {
                        teethData[quadrant].splice(index, 1);
                    }
                } else {
                    this.classList.add('selected');
                    teethData[quadrant].push(teethNumber);
                }
                
                updateTeethSelection();
            });
        });
        
        function updateTeethSelection() {
            updateCombinedTeethData();
        }
        
        function updateCombinedTeethData() {
            const combinedData = {};
            
            const urTreatments = $('select[name="ur_treatment_type"]').val() || [];
            const ulTreatments = $('select[name="ul_treatment_type"]').val() || [];
            const lrTreatments = $('select[name="lr_treatment_type"]').val() || [];
            const llTreatments = $('select[name="ll_treatment_type"]').val() || [];
            
            if (teethData.ur.length > 0 || urTreatments.length > 0) {
                combinedData.ur = {
                    teeth: teethData.ur,
                    treatments: urTreatments
                };
            }
            
            if (teethData.ul.length > 0 || ulTreatments.length > 0) {
                combinedData.ul = {
                    teeth: teethData.ul,
                    treatments: ulTreatments
                };
            }
            
            if (teethData.lr.length > 0 || lrTreatments.length > 0) {
                combinedData.lr = {
                    teeth: teethData.lr,
                    treatments: lrTreatments
                };
            }
            
            if (teethData.ll.length > 0 || llTreatments.length > 0) {
                combinedData.ll = {
                    teeth: teethData.ll,
                    treatments: llTreatments
                };
            }
            
            const teethTreatmentTypeInput = document.getElementById('teeth_treatment_type_input');
            if (teethTreatmentTypeInput) {
                const combinedArray = [];
                Object.keys(combinedData).forEach(quadrant => {
                    if (combinedData[quadrant].teeth.length > 0 || combinedData[quadrant].treatments.length > 0) {
                        combinedArray.push({
                            quadrant: quadrant,
                            teeth: combinedData[quadrant].teeth,
                            treatments: combinedData[quadrant].treatments
                        });
                    }
                });
                
                teethTreatmentTypeInput.value = JSON.stringify(combinedArray);
                updateCombinedDataDisplay(combinedData);
            }
        }
        
        function updateCombinedDataDisplay(combinedData) {
            const displayElement = document.getElementById('combined_data_display');
            if (displayElement) {
                if (Object.keys(combinedData).length > 0) {
                    displayElement.textContent = JSON.stringify(combinedData, null, 2);
                    displayElement.className = 'mb-0 text-success';
                } else {
                    displayElement.textContent = 'No data selected yet';
                    displayElement.className = 'mb-0 text-muted';
                }
            }
            
            const teethTreatmentTypeInput = document.getElementById('teeth_treatment_type_input');
            if (teethTreatmentTypeInput && teethTreatmentTypeInput.value) {
                try {
                    const storedData = JSON.parse(teethTreatmentTypeInput.value);
                } catch (e) {
                    console.log('Error parsing stored data:', e);
                }
            }
        }
        
        function initializeTeethData() {
            const teethTreatmentTypeInput = document.getElementById('teeth_treatment_type_input');
            if (teethTreatmentTypeInput && teethTreatmentTypeInput.value) {
                try {
                    const existingData = JSON.parse(teethTreatmentTypeInput.value);
                    
                    existingData.forEach(item => {
                        if (item.quadrant && item.teeth) {
                            teethData[item.quadrant] = item.teeth;
                            
                            item.teeth.forEach(teethNumber => {
                                const button = document.querySelector(`[data-teeth="${teethNumber}"][data-quadrant="${item.quadrant}"]`);
                                if (button) button.classList.add('selected');
                            });
                            
                            if (item.treatments && item.treatments.length > 0) {
                                $(`select[name="${item.quadrant}_treatment_type"]`).val(item.treatments).trigger('change');
                            }
                        }
                    });
                } catch (e) {
                    console.log('Error parsing existing teeth data:', e);
                }
            }
            
            updateTeethSelection();
        }

        const generalShadeInput = document.getElementById('general_shade_input');
        const cervicalShadeInput = document.getElementById('cervical_shade_input');
        const incisalShadeInput = document.getElementById('incisal_shade_input');
        const shadeSelectionInput = document.getElementById('shade_selection_input');
        
        let shadeData = {
            Cervical: '',
            Middle: '',
            Incisal: ''
        };
        
        if (cervicalShadeInput) {
            cervicalShadeInput.addEventListener('input', function() {
                shadeData.Cervical = this.value;
                updateShadeSelection();
            });
        }
        
        if (generalShadeInput) {
            generalShadeInput.addEventListener('input', function() {
                shadeData.Middle = this.value;
                updateShadeSelection();
            });
        }
        
        if (incisalShadeInput) {
            incisalShadeInput.addEventListener('input', function() {
                shadeData.Incisal = this.value;
                updateShadeSelection();
            });
        }
        
        function updateShadeSelection() {
            const jsonData = {};
            
            if (shadeData.Cervical) jsonData.Cervical = shadeData.Cervical;
            if (shadeData.Middle) jsonData.Middle = shadeData.Middle;
            if (shadeData.Incisal) jsonData.Incisal = shadeData.Incisal;
            
            shadeSelectionInput.value = JSON.stringify(jsonData);
        }
        
        function initializeShadeData() {
            if (shadeSelectionInput.value) {
                try {
                    const existingData = JSON.parse(shadeSelectionInput.value);
                    
                    if (existingData.Cervical) {
                        shadeData.Cervical = existingData.Cervical;
                        if (cervicalShadeInput) {
                            cervicalShadeInput.value = existingData.Cervical;
                        }
                    }
                    
                    if (existingData.Middle) {
                        shadeData.Middle = existingData.Middle;
                        if (generalShadeInput) {
                            generalShadeInput.value = existingData.Middle;
                        }
                    }
                    
                    if (existingData.Incisal) {
                        shadeData.Incisal = existingData.Incisal;
                        if (incisalShadeInput) {
                            incisalShadeInput.value = existingData.Incisal;
                        }
                    }
                } catch (e) {
                    console.log('No existing shade data or invalid JSON:', e);
                }
            }
            
            updateShadeSelection();
        }
        
        initializeShadeData();
        initializeTeethData();
        
        const teethTreatmentTypeInput = document.getElementById('teeth_treatment_type_input');
        if (teethTreatmentTypeInput && teethTreatmentTypeInput.value) {
            try {
                const existingData = JSON.parse(teethTreatmentTypeInput.value);
                updateCombinedDataDisplay(existingData);
            } catch (e) {
                updateCombinedDataDisplay({});
            }
        } else {
            updateCombinedDataDisplay({});
        }

        const form = document.getElementById('lab-form-submit');
        form.addEventListener('submit', function(e) {
            const formData = new FormData(form);
        });
    });

    function updateTreatmentDisplay() {
        // Update treatment-related displays
    }
</script>