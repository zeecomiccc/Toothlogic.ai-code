<div class="modal fade modal-xl" id="addPatientHistory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('patient_history.patient_history') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" id="patient-history-submit" class="requires-validation" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row" id="patient-history-model">
                        <!-- Modern Stepper Navigation -->
                        <div class="mb-4" id="stepper-nav">
                            <div class="stepper d-flex justify-content-between align-items-center">
                                <div class="stepper-step text-center" data-step="1">
                                    <div class="stepper-circle mx-auto">1</div>
                                    <div class="stepper-label">{{ __('patient_history.demographics') }}</div>
                                </div>
                                <div class="stepper-line"></div>
                                <div class="stepper-step text-center" data-step="2">
                                    <div class="stepper-circle mx-auto">2</div>
                                    <div class="stepper-label">{{ __('patient_history.medical_history') }}</div>
                                </div>
                                <div class="stepper-line"></div>
                                <div class="stepper-step text-center" data-step="3">
                                    <div class="stepper-circle mx-auto">3</div>
                                    <div class="stepper-label">{{ __('patient_history.dental_history') }}</div>
                                </div>
                                <div class="stepper-line"></div>
                                <div class="stepper-step text-center" data-step="4">
                                    <div class="stepper-circle mx-auto">4</div>
                                    <div class="stepper-label">{{ __('patient_history.chief_complaint') }}</div>
                                </div>
                                <div class="stepper-line"></div>
                                <div class="stepper-step text-center" data-step="5">
                                    <div class="stepper-circle mx-auto">5</div>
                                    <div class="stepper-label">{{ __('patient_history.clinical_exam') }}</div>
                                </div>
                                <div class="stepper-line"></div>
                                <div class="stepper-step text-center" data-step="6">
                                    <div class="stepper-circle mx-auto">6</div>
                                    <div class="stepper-label">{{ __('patient_history.radiographs') }}</div>
                                </div>
                                <div class="stepper-line"></div>
                                <div class="stepper-step text-center" data-step="7">
                                    <div class="stepper-circle mx-auto">7</div>
                                    <div class="stepper-label">{{ __('patient_history.diagnosis_plan') }}</div>
                                </div>
                                <div class="stepper-line"></div>
                                <div class="stepper-step text-center" data-step="8">
                                    <div class="stepper-circle mx-auto">8</div>
                                    <div class="stepper-label">{{ __('patient_history.dental_chart') }}</div>
                                </div>
                                <div class="stepper-line"></div>
                                <div class="stepper-step text-center" data-step="9">
                                    <div class="stepper-circle mx-auto">9</div>
                                    <div class="stepper-label">{{ __('patient_history.informed_consent') }}</div>
                                </div>
                            </div>
                        </div>


                        <input type="hidden" name="id" id="patient_history_id">
                        <input type="hidden" name="user_id" id="patient_history_user_id" value="{{ $data['user_id'] }}">
                        <input type="hidden" name="encounter_id" id="patient_history_encounter_id"
                            value="{{ $data['id'] }}">

                        <!-- I. Patient Demographic Information -->
                        <div class="step step-1">
                            <div class="card card-body mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">{{ __('patient_history.patient_demographic_info') }}</h5>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.full_name') }}:</label>
                                        <input type="text" name="full_name" id="full_name" class="form-control"
                                            placeholder="{{ __('patient_history.full_name_placeholder') }}"
                                            data-user-full-name="{{ $data->user->first_name ?? '' }} {{ $data->user->last_name ?? '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.date_of_birth') }}:<span
                                                class="text-danger"></span></label>
                                        <input type="date" name="dob" id="dob" class="form-control"
                                            placeholder="{{ __('patient_history.date_placeholder') }}"
                                            data-user-dob="{{ $data->user->date_of_birth ?? '' }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.gender') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_male"
                                                value="Male" data-user-gender="{{ $data->user->gender ?? '' }}">
                                            <label class="form-check-label" for="gender_male">{{
                                                __('patient_history.male') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender"
                                                id="gender_female" value="Female"
                                                data-user-gender="{{ $data->user->gender ?? '' }}">
                                            <label class="form-check-label" for="gender_female">{{
                                                __('patient_history.female') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_other"
                                                value="Other" data-user-gender="{{ $data->user->gender ?? '' }}">
                                            <label class="form-check-label" for="gender_other">{{
                                                __('patient_history.other') }}</label>
                                        </div>
                                        <span class="text-danger validation-error" data-error-for="gender"></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.occupation') }}:</label>
                                        <input type="text" name="occupation" class="form-control"
                                            placeholder="{{ __('patient_history.occupation_placeholder') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.address') }}:</label>
                                        <input type="text" name="address" id="address" class="form-control"
                                            placeholder="{{ __('patient_history.address_placeholder') }}"
                                            data-user-address="{{ $data->user->address ?? '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.phone_number') }}:</label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            placeholder="{{ __('patient_history.phone_number_placeholder') }}"
                                            data-user-phone="{{ $data->user->mobile ?? '' }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.email_address') }}:</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="{{ __('patient_history.email_address_placeholder') }}"
                                            data-user-email="{{ $data->user->email ?? '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.emergency_contact') }}:</label>
                                        <input type="text" name="emergency_contact" class="form-control"
                                            placeholder="{{ __('patient_history.emergency_contact_placeholder') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- II. Medical History -->
                        <div class="step step-2" style="display:none;">
                            <div class="card card-body mb-3">
                                <h5 class="mb-3">{{ __('patient_history.medical_history') }}</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.under_medical_treatment') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="under_medical_treatment"
                                                id="treatment_yes" value="Yes">
                                            <label class="form-check-label" for="treatment_yes">{{
                                                __('patient_history.yes') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="under_medical_treatment"
                                                id="treatment_no" value="No">
                                            <label class="form-check-label" for="treatment_no">{{
                                                __('patient_history.no') }}</label>
                                        </div>
                                        <span class="text-danger validation-error"
                                            data-error-for="under_medical_treatment"></span>
                                        <input type="text" name="treatment_details" class="form-control mt-2"
                                            placeholder="{{ __('patient_history.treatment_details_placeholder') }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.hospitalized_last_year') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="hospitalized_last_year"
                                                id="hospitalized_yes" value="Yes">
                                            <label class="form-check-label" for="hospitalized_yes">{{
                                                __('patient_history.yes') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="hospitalized_last_year"
                                                id="hospitalized_no" value="No">
                                            <label class="form-check-label" for="hospitalized_no">{{
                                                __('patient_history.no') }}</label>
                                        </div>
                                        <span class="text-danger validation-error"
                                            data-error-for="hospitalized_last_year"></span>
                                        <input type="text" name="hospitalization_reason" class="form-control mt-2"
                                            placeholder="{{ __('patient_history.hospitalization_reason_placeholder') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('patient_history.diseases') }}:</label><br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_heart" value="Heart
                                                    Disease">
                                                <label class="form-check-label" for="disease_heart">{{
                                                    __('patient_history.heart_disease') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_blood_pressure" value="High/Low Blood Pressure">
                                                <label class="form-check-label" for="disease_blood_pressure">{{
                                                    __('patient_history.blood_pressure') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_diabetes" value="Diabetes">
                                                <label class="form-check-label" for="disease_diabetes">{{
                                                    __('patient_history.diabetes') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_asthma" value="Asthma">
                                                <label class="form-check-label" for="disease_asthma">{{
                                                    __('patient_history.asthma') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_bleeding" value="Bleeding Disorders">
                                                <label class="form-check-label" for="disease_bleeding">{{
                                                    __('patient_history.bleeding_disorders') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_epilepsy" value="Epilepsy/Seizures">
                                                <label class="form-check-label" for="disease_epilepsy">{{
                                                    __('patient_history.epilepsy_seizures') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_hepatitis" value="Hepatitis A/B/C">
                                                <label class="form-check-label" for="disease_hepatitis">{{
                                                    __('patient_history.hepatitis') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_hiv" value="HIV/AIDS">
                                                <label class="form-check-label" for="disease_hiv">{{
                                                    __('patient_history.hiv_aids') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_tb" value="Tuberculosis">
                                                <label class="form-check-label" for="disease_tb">{{
                                                    __('patient_history.tuberculosis') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_kidney" value="Kidney Disease">
                                                <label class="form-check-label" for="disease_kidney">{{
                                                    __('patient_history.kidney_disease') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_liver" value="Liver Disease">
                                                <label class="form-check-label" for="disease_liver">{{
                                                    __('patient_history.liver_disease') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_cancer" value="Cancer">
                                                <label class="form-check-label" for="disease_cancer">{{
                                                    __('patient_history.cancer') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_psych" value="Psychiatric Disorders">
                                                <label class="form-check-label" for="disease_psych">{{
                                                    __('patient_history.psychiatric_disorders') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_thyroid" value="Thyroid Problems">
                                                <label class="form-check-label" for="disease_thyroid">{{
                                                    __('patient_history.thyroid_problems') }}</label>
                                            </div>
                                            <span class="text-danger validation-error" data-error-for="diseases"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_osteoporosis" value="Osteoporosis">
                                                <label class="form-check-label" for="disease_osteoporosis">{{
                                                    __('patient_history.osteoporosis') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases[]"
                                                    id="disease_other" value="Other">
                                                <label class="form-check-label" for="disease_other">{{
                                                    __('patient_history.other') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.pregnant_or_breastfeeding') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="pregnant_or_breastfeeding" id="pregnant_yes" value="Yes">
                                            <label class="form-check-label" for="pregnant_yes">{{
                                                __('patient_history.yes') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="pregnant_or_breastfeeding" id="pregnant_no" value="No">
                                            <label class="form-check-label" for="pregnant_no">{{
                                                __('patient_history.no') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="pregnant_or_breastfeeding" id="pregnant_na" value="N/A">
                                            <label class="form-check-label" for="pregnant_na">{{
                                                __('patient_history.na') }}</label>
                                        </div>
                                        <span class="text-danger validation-error"
                                            data-error-for="pregnant_or_breastfeeding"></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.taking_medications') }}:</label><br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="taking_medications[]" id="med_blood_thinners"
                                                        value="Blood Thinners">
                                                    <label class="form-check-label" for="med_blood_thinners">{{
                                                        __('patient_history.blood_thinners') }}</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="taking_medications[]" id="med_bisphosphonates"
                                                        value="Bisphosphonates">
                                                    <label class="form-check-label" for="med_bisphosphonates">{{
                                                        __('patient_history.bisphosphonates') }}</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="taking_medications[]" id="med_steroids" value="Steroids">
                                                    <label class="form-check-label" for="med_steroids">{{
                                                        __('patient_history.steroids') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="taking_medications[]" id="med_immunosuppressants"
                                                        value="Immunosuppressants">
                                                    <label class="form-check-label" for="med_immunosuppressants">{{
                                                        __('patient_history.immunosuppressants') }}</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="taking_medications[]" id="med_herbal"
                                                        value="Herbal Supplements">
                                                    <label class="form-check-label" for="med_herbal">{{
                                                        __('patient_history.herbal_supplements') }}</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="taking_medications[]" id="med_others" value="Others">
                                                    <label class="form-check-label" for="med_others">{{
                                                        __('patient_history.others') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-danger validation-error"
                                            data-error-for="taking_medications"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('patient_history.known_allergies') }}:</label><br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="known_allergies[]"
                                                    id="allergy_anesthetics" value="Local Anesthetics">
                                                <label class="form-check-label" for="allergy_anesthetics">{{
                                                    __('patient_history.local_anesthetics') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="known_allergies[]"
                                                    id="allergy_latex" value="Latex">
                                                <label class="form-check-label" for="allergy_latex">{{
                                                    __('patient_history.latex') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="known_allergies[]"
                                                    id="allergy_penicillin" value="Penicillin/Antibiotics">
                                                <label class="form-check-label" for="allergy_penicillin">{{
                                                    __('patient_history.penicillin_antibiotics') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="known_allergies[]"
                                                    id="allergy_other" value="Other">
                                                <label class="form-check-label" for="allergy_other">{{
                                                    __('patient_history.other') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger validation-error" data-error-for="known_allergies"></span>
                                </div>
                            </div>
                        </div>
                        <!-- III. Dental History -->
                        <div class="step step-3" style="display:none;">
                            <div class="card card-body mb-3">
                                <h5 class="mb-3">{{ __('patient_history.dental_history') }}</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.last_dental_visit_date') }}:</label>
                                        <input type="date" name="last_dental_visit_date" class="form-control"
                                            placeholder="{{ __('patient_history.date_placeholder') }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.reason_for_last_visit') }}:</label>
                                        <input type="text" name="reason_for_last_visit" class="form-control"
                                            placeholder="{{ __('patient_history.reason_for_last_visit_placeholder') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('patient_history.issues_experienced') }}:</label><br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="issues_experienced[]" id="issue_toothache"
                                                    value="Toothache or sensitivity">
                                                <label class="form-check-label" for="issue_toothache">{{
                                                    __('patient_history.toothache_sensitivity') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="issues_experienced[]" id="issue_bleeding_gums"
                                                    value="Bleeding gums">
                                                <label class="form-check-label" for="issue_bleeding_gums">{{
                                                    __('patient_history.bleeding_gums') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="issues_experienced[]" id="issue_bad_breath"
                                                    value="Bad breath or taste">
                                                <label class="form-check-label" for="issue_bad_breath">{{
                                                    __('patient_history.bad_breath_taste') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="issues_experienced[]" id="issue_difficulty_chewing"
                                                    value="Difficulty chewing">
                                                <label class="form-check-label" for="issue_difficulty_chewing">{{
                                                    __('patient_history.difficulty_chewing') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="issues_experienced[]" id="issue_jaw_pain"
                                                    value="Clicking or pain in jaw">
                                                <label class="form-check-label" for="issue_jaw_pain">{{
                                                    __('patient_history.clicking_jaw_pain') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="issues_experienced[]" id="issue_dry_mouth" value="Dry mouth">
                                                <label class="form-check-label" for="issue_dry_mouth">{{
                                                    __('patient_history.dry_mouth') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="issues_experienced[]" id="issue_loose_teeth"
                                                    value="Loose or shifting teeth">
                                                <label class="form-check-label" for="issue_loose_teeth">{{
                                                    __('patient_history.loose_shifting_teeth') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="issues_experienced[]" id="issue_ortho"
                                                    value="Previous orthodontic treatment">
                                                <label class="form-check-label" for="issue_ortho">{{
                                                    __('patient_history.previous_orthodontic_treatment') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="issues_experienced[]" id="issue_trauma"
                                                    value="History of dental trauma">
                                                <label class="form-check-label" for="issue_trauma">{{
                                                    __('patient_history.history_dental_trauma') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="issues_experienced[]" id="issue_past_treatments"
                                                    value="Problems with past dental treatments">
                                                <label class="form-check-label" for="issue_past_treatments">{{
                                                    __('patient_history.problems_past_dental_treatments') }}</label>
                                            </div>
                                            <span class="text-danger validation-error"
                                                data-error-for="issues_experienced"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.dental_anxious') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="dental_anxious"
                                                id="dental_anxious_yes" value="Yes">
                                            <label class="form-check-label" for="dental_anxious_yes">{{
                                                __('patient_history.yes') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="dental_anxious"
                                                id="dental_anxious_no" value="No">
                                            <label class="form-check-label" for="dental_anxious_no">{{
                                                __('patient_history.no') }}</label>
                                        </div>
                                        <span class="text-danger validation-error"
                                            data-error-for="dental_anxious"></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.dental_anxiety_level') }}:</label>
                                        <input type="number" min="1" max="5" name="dental_anxiety_level"
                                            class="form-control"
                                            placeholder="{{ __('patient_history.dental_anxiety_level_placeholder') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- IV. Chief Complaint -->
                        <div class="step step-4" style="display:none;">
                            <div class="card card-body mb-3">
                                <h5 class="mb-3">{{ __('patient_history.chief_complaint') }}</h5>
                                <div class="form-group">
                                    <label>{{ __('patient_history.complaint_notes') }}:</label>
                                    <textarea name="complaint_notes" class="form-control" rows="2"
                                        placeholder="{{ __('patient_history.complaint_notes_placeholder') }}"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- V. Clinical Examination -->
                        <div class="step step-5" style="display:none;">
                            <div class="card card-body mb-3">
                                <h5 class="mb-3">{{ __('patient_history.clinical_exam') }}</h5>
                                <div class="form-group">
                                    <label>{{ __('patient_history.extraoral_examination') }}</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>{{ __('patient_history.face_symmetry') }}:</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="face_symmetry"
                                                    id="face_normal" value="Normal">
                                                <label class="form-check-label" for="face_normal">{{
                                                    __('patient_history.normal') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="face_symmetry"
                                                    id="face_abnormal" value="Abnormal">
                                                <label class="form-check-label" for="face_abnormal">{{
                                                    __('patient_history.abnormal') }}</label>
                                            </div>
                                            <input type="text" name="face_symmetry_details" class="form-control mt-2"
                                                placeholder="{{ __('patient_history.face_symmetry_details_placeholder') }}">
                                            <span class="text-danger validation-error"
                                                data-error-for="face_symmetry"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>{{ __('patient_history.temporomandibular_joint') }}:</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="tmj_status[]"
                                                    id="tmj_normal" value="Normal">
                                                <label class="form-check-label" for="tmj_normal">{{
                                                    __('patient_history.normal') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="tmj_status[]"
                                                    id="tmj_clicking" value="Clicking">
                                                <label class="form-check-label" for="tmj_clicking">{{
                                                    __('patient_history.clicking') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="tmj_status[]"
                                                    id="tmj_pain" value="Pain">
                                                <label class="form-check-label" for="tmj_pain">{{
                                                    __('patient_history.pain') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="tmj_status[]"
                                                    id="tmj_limited" value="Limited Opening">
                                                <label class="form-check-label" for="tmj_limited">{{
                                                    __('patient_history.limited_opening') }}</label>
                                            </div>
                                            <span class="text-danger validation-error"
                                                data-error-for="tmj_status"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('patient_history.intraoral_examination') }}</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>{{ __('patient_history.soft_tissues') }}:</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="soft_tissue_status"
                                                    id="soft_normal" value="Normal">
                                                <label class="form-check-label" for="soft_normal">{{
                                                    __('patient_history.normal') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="soft_tissue_status"
                                                    id="soft_abnormal" value="Abnormal">
                                                <label class="form-check-label" for="soft_abnormal">{{
                                                    __('patient_history.abnormalities') }}</label>
                                            </div>
                                            <input type="text" name="soft_tissues_details" class="form-control mt-2"
                                                placeholder="{{ __('patient_history.soft_tissues_details_placeholder') }}">
                                            <span class="text-danger validation-error"
                                                data-error-for="soft_tissue_status"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>{{ __('patient_history.teeth') }}:</label>
                                            <input type="text" name="teeth_status" class="form-control"
                                                placeholder="{{ __('patient_history.missing_teeth_caries_restorations_wear') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.gingival_periodontal_health') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gingival_health"
                                                id="gingival_healthy" value="Healthy">
                                            <label class="form-check-label" for="gingival_healthy">{{
                                                __('patient_history.healthy') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gingival_health"
                                                id="gingival_gingivitis" value="Gingivitis">
                                            <label class="form-check-label" for="gingival_gingivitis">{{
                                                __('patient_history.gingivitis') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gingival_health"
                                                id="gingival_periodontitis" value="Periodontitis">
                                            <label class="form-check-label" for="gingival_periodontitis">{{
                                                __('patient_history.periodontitis') }}</label>
                                        </div>
                                        <span class="text-danger validation-error"
                                            data-error-for="gingival_health"></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.bleeding_on_probing') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bleeding_on_probing"
                                                id="bleeding_yes" value="Yes">
                                            <label class="form-check-label" for="bleeding_yes">{{
                                                __('patient_history.yes') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bleeding_on_probing"
                                                id="bleeding_no" value="No">
                                            <label class="form-check-label" for="bleeding_no">{{
                                                __('patient_history.no') }}</label>
                                        </div>
                                        <span class="text-danger validation-error"
                                            data-error-for="bleeding_on_probing"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.pocket_depths') }}:</label>
                                        <input type="text" name="pocket_depths" class="form-control"
                                            placeholder="{{ __('patient_history.pocket_depths_placeholder') }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.mobility') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mobility"
                                                id="mobility_present" value="Present">
                                            <label class="form-check-label" for="mobility_present">{{
                                                __('patient_history.present') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mobility"
                                                id="mobility_absent" value="Absent">
                                            <label class="form-check-label" for="mobility_absent">{{
                                                __('patient_history.absent') }}</label>
                                        </div>
                                        <span class="text-danger validation-error" data-error-for="mobility"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('patient_history.occlusion') }}:</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="occlusion_bite"
                                            id="occlusion_normal" value="Normal">
                                        <label class="form-check-label" for="occlusion_normal">{{
                                            __('patient_history.normal') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="occlusion_bite"
                                            id="occlusion_malocclusion" value="Malocclusion">
                                        <label class="form-check-label" for="occlusion_malocclusion">{{
                                            __('patient_history.malocclusion') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="malocclusion_class[]"
                                            id="malocclusion_class_i" value="Class I">
                                        <label class="form-check-label" for="malocclusion_class_i">{{
                                            __('patient_history.class_i') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="malocclusion_class[]"
                                            id="malocclusion_class_ii" value="Class II">
                                        <label class="form-check-label" for="malocclusion_class_ii">{{
                                            __('patient_history.class_ii') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="malocclusion_class[]"
                                            id="malocclusion_class_iii" value="Class III">
                                        <label class="form-check-label" for="malocclusion_class_iii">{{
                                            __('patient_history.class_iii') }}</label>
                                    </div>
                                    <span class="text-danger validation-error"
                                        data-error-for="malocclusion_class"></span>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('patient_history.signs_bruxism_clenching') }}:</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="bruxism" id="bruxism_yes"
                                            value="Yes">
                                        <label class="form-check-label" for="bruxism_yes">{{ __('patient_history.yes')
                                            }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="bruxism" id="bruxism_no"
                                            value="No">
                                        <label class="form-check-label" for="bruxism_no">{{ __('patient_history.no')
                                            }}</label>
                                    </div>
                                    <span class="text-danger validation-error" data-error-for="bruxism"></span>
                                </div>
                            </div>
                        </div>
                        <!-- VI. Radiographic Examination -->
                        <div class="step step-6" style="display:none;">
                            <div class="card card-body mb-3">
                                <h5 class="mb-3">{{ __('patient_history.radiographic_examination') }}</h5>
                                <div class="form-group">
                                    <label>{{ __('patient_history.radiograph_type') }}:</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="radiograph_type[]"
                                            id="radiograph_bitewings" value="Bitewings">
                                        <label class="form-check-label" for="radiograph_bitewings">{{
                                            __('patient_history.bitewings') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="radiograph_type[]"
                                            id="radiograph_periapical" value="Periapical">
                                        <label class="form-check-label" for="radiograph_periapical">{{
                                            __('patient_history.periapical') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="radiograph_type[]"
                                            id="radiograph_opg" value="OPG">
                                        <label class="form-check-label" for="radiograph_opg">{{
                                            __('patient_history.opg') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="radiograph_type[]"
                                            id="radiograph_lateral_cephalogram" value="Lateral Cephalogram">
                                        <label class="form-check-label" for="radiograph_lateral_cephalogram">{{
                                            __('patient_history.lateral_cephalogram') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="radiograph_type[]"
                                            id="radiograph_cbct" value="CBCT">
                                        <label class="form-check-label" for="radiograph_cbct">{{
                                            __('patient_history.cbct') }}</label>
                                    </div>
                                    {{-- <span class="text-danger validation-error"
                                        data-error-for="radiograph_type"></span> --}}
                                </div>
                                <div class="form-group">
                                    <label>{{ __('patient_history.radiograph_findings') }}:</label>
                                    <textarea name="radiograph_findings" class="form-control" rows="2"
                                        placeholder="{{ __('patient_history.radiograph_findings_placeholder') }}"></textarea>
                                </div>
                                <span class="text-danger validation-error" data-error-for="radiograph_type"></span>
                            </div>
                        </div>
                        <!-- VII. Diagnosis & Treatment Plan -->
                        <div class="step step-7" style="display:none;">
                            <div class="card card-body mb-3">
                                <h5 class="mb-3">{{ __('patient_history.diagnosis_plan') }}</h5>
                                <div class="form-group">
                                    <label>{{ __('patient_history.diagnosis') }}:</label>
                                    <textarea name="diagnosis" class="form-control" rows="2"
                                        placeholder="{{ __('patient_history.diagnosis_placeholder') }}"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('patient_history.proposed_treatment_plan') }}:</label><br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check"><input class="form-check-input" type="checkbox"
                                                    name="proposed_treatments[]" id="treatment_oral_prophylaxis"
                                                    value="Oral Prophylaxis">
                                                <label class="form-check-label" for="treatment_oral_prophylaxis">{{
                                                    __('patient_history.oral_prophylaxis') }}</label>
                                            </div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox"
                                                    name="proposed_treatments[]" id="treatment_fillings"
                                                    value="Fillings">
                                                <label class="form-check-label" for="treatment_fillings">{{
                                                    __('patient_history.fillings') }}</label>
                                            </div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox"
                                                    name="proposed_treatments[]" id="treatment_root_canal"
                                                    value="Root Canal Treatment">
                                                <label class="form-check-label" for="treatment_root_canal">{{
                                                    __('patient_history.root_canal_treatment') }}</label>
                                            </div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox"
                                                    name="proposed_treatments[]" id="treatment_crowns"
                                                    value="Crowns/Bridges">
                                                <label class="form-check-label" for="treatment_crowns">{{
                                                    __('patient_history.crowns_bridges') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check"><input class="form-check-input" type="checkbox"
                                                    name="proposed_treatments[]" id="treatment_implants"
                                                    value="Implants">
                                                <label class="form-check-label" for="treatment_implants">{{
                                                    __('patient_history.implants') }}</label>
                                            </div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox"
                                                    name="proposed_treatments[]" id="treatment_extractions"
                                                    value="Extractions">
                                                <label class="form-check-label" for="treatment_extractions">{{
                                                    __('patient_history.extractions') }}</label>
                                            </div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox"
                                                    name="proposed_treatments[]" id="treatment_orthodontics"
                                                    value="Orthodontics (Braces/Aligners)">
                                                <label class="form-check-label" for="treatment_orthodontics">{{
                                                    __('patient_history.orthodontics_braces_aligners') }}</label>
                                            </div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox"
                                                    name="proposed_treatments[]" id="treatment_other" value="Other">
                                                <label class="form-check-label" for="treatment_other">{{
                                                    __('patient_history.other') }}</label>
                                            </div>
                                            <span class="text-danger validation-error"
                                                data-error-for="proposed_treatments"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.planned_timeline') }}:</label>
                                        <input type="text" name="planned_timeline" class="form-control"
                                            placeholder="{{ __('patient_history.planned_timeline_placeholder') }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.alternative_options_discussed') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="alternatives_discussed"
                                                id="alt_options_yes" value="Yes">
                                            <label class="form-check-label" for="alt_options_yes">{{
                                                __('patient_history.yes') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="alternatives_discussed"
                                                id="alt_options_no" value="No">
                                            <label class="form-check-label" for="alt_options_no">{{
                                                __('patient_history.no') }}</label>
                                        </div>
                                        <span class="text-danger validation-error"
                                            data-error-for="alternatives_discussed"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.risks_benefits_explained') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="risks_explained"
                                                id="risks_yes" value="Yes">
                                            <label class="form-check-label" for="risks_yes">{{ __('patient_history.yes')
                                                }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="risks_explained"
                                                id="risks_no" value="No">
                                            <label class="form-check-label" for="risks_no">{{ __('patient_history.no')
                                                }}</label>
                                        </div>
                                        <span class="text-danger validation-error"
                                            data-error-for="risks_explained"></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('patient_history.patient_questions_addressed') }}:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="questions_addressed"
                                                id="questions_yes" value="Yes">
                                            <label class="form-check-label" for="questions_yes">{{
                                                __('patient_history.yes') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="questions_addressed"
                                                id="questions_no" value="No">
                                            <label class="form-check-label" for="questions_no">{{
                                                __('patient_history.no') }}</label>
                                        </div>
                                        <span class="text-danger validation-error"
                                            data-error-for="questions_addressed"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- VIII. Dental Chart -->
                        <div class="step step-8" style="display:none;">
                            <div class="card card-body mb-3">
                                <h5 class="mb-3">{{ __('patient_history.dental_chart') }}</h5>

                                <!-- Dental Chart Controls -->
                                <div class="controls mb-4">
                                    <button class="btn btn-red active" id="btn-cavity" type="button">
                                        <span>Mark Cavity</span>
                                    </button>
                                    <button class="btn btn-blue" id="btn-crown" type="button">
                                        <span>Mark Crown/Restored</span>
                                    </button>
                                    <button class="btn btn-yellow" id="btn-missing" type="button">
                                        <span>Mark Missing</span>
                                    </button>
                                    <button class="btn btn-green" id="btn-retained" type="button">
                                        <span>Mark Retained</span>
                                    </button>
                                    <button class="btn btn-secondary" id="btn-clear" type="button">
                                        <span>Clear All</span>
                                    </button>
                                </div>

                                <!-- Selected Teeth by Category -->
                                <div class="category-section mb-4">
                                    <h6>Selected Teeth by Category</h6>
                                    <div class="category-list">
                                        <div class="category-item">
                                            <div class="category-title">Cavity</div>
                                            <div class="tooth-list" id="cavity-list"></div>
                                        </div>
                                        <div class="category-item">
                                            <div class="category-title">Crown/Restored</div>
                                            <div class="tooth-list" id="crown-list"></div>
                                        </div>
                                        <div class="category-item">
                                            <div class="category-title">Missing</div>
                                            <div class="tooth-list" id="missing-list"></div>
                                        </div>
                                        <div class="category-item">
                                            <div class="category-title">Retained</div>
                                            <div class="tooth-list" id="retained-list"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Interactive Dental Chart -->
                                <div class="dental-chart" id="dentalChart">
                                    <div class="upper-layer-box">
                                        <div class="adult-upper-tooth">
                                            <div class="adult-upper-stack">
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-8"
                                                        name="dental_chart[8]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">8</div>
                                                    </div>
                                                </div>
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-9"
                                                        name="dental_chart[9]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">9</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-stack">
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-7"
                                                        name="dental_chart[7]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">7</div>
                                                    </div>
                                                </div>
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-10"
                                                        name="dental_chart[10]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">10</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-stack">
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-6"
                                                        name="dental_chart[6]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">6</div>
                                                    </div>
                                                </div>
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-11"
                                                        name="dental_chart[11]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">11</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-stack">
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-5"
                                                        name="dental_chart[5]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">5</div>
                                                    </div>
                                                </div>
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-12"
                                                        name="dental_chart[12]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">12</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-stack">
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-4"
                                                        name="dental_chart[4]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">4</div>
                                                    </div>
                                                </div>
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-13"
                                                        name="dental_chart[13]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">13</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-stack">
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-3"
                                                        name="dental_chart[3]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">3</div>
                                                    </div>
                                                </div>
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-14"
                                                        name="dental_chart[14]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">14</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-stack">
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-2"
                                                        name="dental_chart[2]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">2</div>
                                                    </div>
                                                </div>
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-15"
                                                        name="dental_chart[15]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">15</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-upper-stack">
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-1"
                                                        name="dental_chart[1]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">1</div>
                                                    </div>
                                                </div>
                                                <div class="adult-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-upper-tooth-16"
                                                        name="dental_chart[16]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">16</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-upper-tooth">
                                            <div class="child-upper-stack">
                                                <div class="child-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-upper-tooth-e"
                                                        name="dental_chart[e]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">E</div>
                                                    </div>
                                                </div>
                                                <div class="child-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-upper-tooth-f"
                                                        name="dental_chart[f]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">F</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="child-upper-stack">
                                                <div class="child-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-upper-tooth-d"
                                                        name="dental_chart[d]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">D</div>
                                                    </div>
                                                </div>
                                                <div class="child-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-upper-tooth-g"
                                                        name="dental_chart[g]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">G</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="child-upper-stack">
                                                <div class="child-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-upper-tooth-c"
                                                        name="dental_chart[c]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">C</div>
                                                    </div>
                                                </div>
                                                <div class="child-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-upper-tooth-h"
                                                        name="dental_chart[h]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">H</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="child-upper-stack">
                                                <div class="child-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-upper-tooth-b"
                                                        name="dental_chart[b]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">B</div>
                                                    </div>
                                                </div>
                                                <div class="child-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-upper-tooth-i"
                                                        name="dental_chart[i]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">I</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="child-upper-stack">
                                                <div class="child-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-upper-tooth-a"
                                                        name="dental_chart[a]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">A</div>
                                                    </div>
                                                </div>
                                                <div class="child-upper-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-upper-tooth-j"
                                                        name="dental_chart[j]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">J</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bottom-layer-box">
                                        <div class="child-bottom-tooth">
                                            <div class="child-bottom-stack">
                                                <div class="child-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-bottom-tooth-t"
                                                        name="dental_chart[t]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">T</div>
                                                    </div>
                                                </div>
                                                <div class="child-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-bottom-tooth-k"
                                                        name="dental_chart[k]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">K</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="child-bottom-stack">
                                                <div class="child-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-bottom-tooth-s"
                                                        name="dental_chart[s]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">S</div>
                                                    </div>
                                                </div>
                                                <div class="child-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-bottom-tooth-l"
                                                        name="dental_chart[l]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">L</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="child-bottom-stack">
                                                <div class="child-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-bottom-tooth-r"
                                                        name="dental_chart[r]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">R</div>
                                                    </div>
                                                </div>
                                                <div class="child-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-bottom-tooth-m"
                                                        name="dental_chart[m]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">M</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="child-bottom-stack">
                                                <div class="child-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-bottom-tooth-q"
                                                        name="dental_chart[q]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">Q</div>
                                                    </div>
                                                </div>
                                                <div class="child-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-bottom-tooth-n"
                                                        name="dental_chart[n]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">N</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="child-bottom-stack">
                                                <div class="child-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-bottom-tooth-p"
                                                        name="dental_chart[p]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">P</div>
                                                    </div>
                                                </div>
                                                <div class="child-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="child-bottom-tooth-o"
                                                        name="dental_chart[o]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">O</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="adult-bottom-tooth">
                                            <div class="adult-bottom-stack">
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-32"
                                                        name="dental_chart[32]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">32</div>
                                                    </div>
                                                </div>
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-17"
                                                        name="dental_chart[17]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">17</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-stack">
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-31"
                                                        name="dental_chart[31]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">31</div>
                                                    </div>
                                                </div>
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-18"
                                                        name="dental_chart[18]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">18</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-stack">
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-30"
                                                        name="dental_chart[30]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">30</div>
                                                    </div>
                                                </div>
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-19"
                                                        name="dental_chart[19]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">19</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-stack">
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-29"
                                                        name="dental_chart[29]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">29</div>
                                                    </div>
                                                </div>
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-20"
                                                        name="dental_chart[20]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">20</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-stack">
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-28"
                                                        name="dental_chart[28]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">28</div>
                                                    </div>
                                                </div>
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-21"
                                                        name="dental_chart[21]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">21</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-stack">
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-27"
                                                        name="dental_chart[27]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">27</div>
                                                    </div>
                                                </div>
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-22"
                                                        name="dental_chart[22]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">22</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-stack">
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-26"
                                                        name="dental_chart[26]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">26</div>
                                                    </div>
                                                </div>
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-23"
                                                        name="dental_chart[23]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">23</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="adult-bottom-stack">
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-25"
                                                        name="dental_chart[25]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">25</div>
                                                    </div>
                                                </div>
                                                <div class="adult-bottom-tooth-number tooth-number-container">
                                                    <input type="checkbox" id="adult-bottom-tooth-24"
                                                        name="dental_chart[24]" />
                                                    <div class="tooth-svg">
                                                        <div class="tooth-svg-inner">24</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden inputs for dental chart data -->
                                <input type="hidden" name="dental_chart_data" id="dental_chart_data">
                                <input type="hidden" name="upper_jaw_treatment" id="upper_jaw_data">
                                <input type="hidden" name="lower_jaw_treatment" id="lower_jaw_data">

                                {{-- <div class="row mt-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="upper_jaw_treatment" class="form-label">{{
                                            __('patient_history.upper_jaw_treatment') }}:</label>
                                        <textarea id="upper_jaw_treatment" name="upper_jaw_treatment"
                                            class="form-control" rows="3"
                                            placeholder="{{ __('patient_history.upper_jaw_treatment_placeholder') }}"></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lower_jaw_treatment" class="form-label">{{
                                            __('patient_history.lower_jaw_treatment') }}:</label>
                                        <textarea id="lower_jaw_treatment" name="lower_jaw_treatment"
                                            class="form-control" rows="3"
                                            placeholder="{{ __('patient_history.lower_jaw_treatment_placeholder') }}"></textarea>
                                    </div>
                                </div> --}}

                            </div>
                        </div>
                        <!-- IX. Informed Consent -->
                        <div class="step step-9" style="display:none;">
                            <div class="card card-body mb-3">
                                <h5 class="mb-3">{{ __('patient_history.informed_consent') }}</h5>
                                <div class="form-group mb-4">
                                    <div class="consent-text p-3 border rounded">
                                        <p class="mb-3"><strong>{{ __('patient_history.consent_statement') }}</strong>
                                        </p>
                                        {{-- <p class="mb-3">{{ __('patient_history.consent_text') }}</p> --}}
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Patient Signature -->
                                    <div class="form-group col-md-6">
                                        <label for="patient_signature" class="form-label">{{
                                            __('patient_history.patient_signature') }}:</label>
                                        <div id="patient-signature-container">
                                            <div class="signature-pad-container">
                                                <canvas id="patient-signature-pad" class="signature-pad" width="300"
                                                    height="120"></canvas>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    onclick="clearSignature('patient')">{{ __('patient_history.clear')
                                                    }}</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="saveSignature('patient', event)">{{
                                                    __('patient_history.save_signature') }}</button>
                                            </div>
                                            <input type="hidden" name="patient_signature" id="patient_signature_data">
                                        </div>
                                    </div>

                                    <!-- Dentist Signature -->
                                    <div class="form-group col-md-6">
                                        <label for="dentist_signature" class="form-label">{{
                                            __('patient_history.dentist_signature') }}:</label>
                                        <div id="dentist-signature-container">
                                            <div class="signature-pad-container">
                                                <canvas id="dentist-signature-pad" class="signature-pad" width="300"
                                                    height="120"></canvas>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    onclick="clearSignature('dentist')">{{ __('patient_history.clear')
                                                    }}</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="saveSignature('dentist', event)">{{
                                                    __('patient_history.save_signature') }}</button>
                                            </div>
                                            <input type="hidden" name="dentist_signature" id="dentist_signature_data">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Step Navigation Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-3" id="step-nav-buttons">
                        <button type="button" class="btn btn-secondary" id="prev-step">{{ __('patient_history.previous')
                            }}</button>
                        <div>
                            <button type="button" class="btn btn-success" id="save-step">{{ __('patient_history.save')
                                }}</button>
                            <button type="button" class="btn btn-primary" id="next-step">{{ __('patient_history.next')
                                }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loader overlay -->
    <div id="patient-history-loader"
        style="display:none; position: absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:9999; justify-content:center; align-items:center;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Dental Chart Styles -->
    <style>
        .dental-chart .tooth-number-container {
            position: relative;
            width: 30px;
            height: 30px;
            cursor: pointer;
            margin: 5px;
        }

        .dental-chart .tooth-number-container input[type="checkbox"] {
            display: none;
        }

        .dental-chart .tooth-svg {
            width: 100%;
            height: 100%;
            transition: all 0.3s ease;
            fill: #f0f0f0;
            stroke: #333;
            stroke-width: 1;
        }

        .dental-chart .tooth-number {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: bold;
            pointer-events: none;
        }

        .dental-chart .tooth-svg {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: white;
            border: 2px solid #989898;
            position: relative;
            transition: all 0.3s ease;
        }

        /* Add cross lines */
        .dental-chart .tooth-svg::before,
        .dental-chart .tooth-svg::after {
            content: "";
            position: absolute;
            background: #989898;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .dental-chart .tooth-svg::before {
            width: 2px;
            height: 100%;
        }

        .dental-chart .tooth-svg::after {
            width: 100%;
            height: 2px;
        }

        /* Inner circle */
        .dental-chart .tooth-svg-inner {
            position: absolute;
            width: 60%;
            height: 60%;
            border-radius: 50%;
            background: white;
            border: 1px solid #989898;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            padding: 1px;
        }

        .dental-chart {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .adult-upper-stack,
        .adult-bottom-stack {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .upper-layer-box,
        .bottom-layer-box {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            position: relative;
        }

        .adult-upper-tooth,
        .adult-bottom-tooth {
            max-width: 350px;
            width: 100%;
        }

        .adult-upper-tooth> :nth-child(1) {
            width: max-content;
            margin: auto;
        }

        .adult-upper-tooth> :nth-child(2) {
            width: 44%;
            margin: -30px auto 0;
        }

        .adult-upper-tooth> :nth-child(3) {
            width: 64%;
            margin: -14px auto 0;
        }

        .adult-upper-tooth> :nth-child(4) {
            width: 80%;
            margin: -12px auto 0;
        }

        .adult-upper-tooth> :nth-child(5) {
            width: 90%;
            margin: -5px auto 0;
        }

        .adult-upper-tooth> :nth-child(6) {
            width: 97%;
            margin: -5px auto 0;
        }

        .adult-upper-tooth> :nth-child(7) {
            width: 100%;
            margin: -5px auto 0;
        }

        .adult-bottom-tooth> :nth-child(8) {
            width: max-content;
            margin: -30px auto 0;
        }

        .adult-bottom-tooth> :nth-child(7) {
            width: 44%;
            margin: -20px auto 0;
        }

        .adult-bottom-tooth> :nth-child(6) {
            width: 64%;
            margin: -12px auto 0;
        }

        .adult-bottom-tooth> :nth-child(5) {
            width: 80%;
            margin: -5px auto 0;
        }

        .adult-bottom-tooth> :nth-child(4) {
            width: 90%;
            margin: -2px auto 0;
        }

        .adult-bottom-tooth> :nth-child(3) {
            width: 95%;
            margin: -2px auto 0;
        }

        .adult-bottom-tooth> :nth-child(2) {
            width: 98%;
            margin: -02px auto 0;
        }

        .adult-bottom-tooth> :nth-child(1) {
            width: 100%;
            margin: -2px auto 0;
        }

        .child-upper-tooth {
            max-width: 250px;
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        .child-upper-stack,
        .child-bottom-stack {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .child-upper-tooth> :nth-child(1) {
            width: max-content;
            margin: 0 auto -0px;
        }

        .child-upper-tooth> :nth-child(2) {
            width: 62%;
            margin: -28px auto 0;
        }

        .child-upper-tooth> :nth-child(3) {
            width: 85%;
            margin: -15px auto 0;
        }

        .child-upper-tooth> :nth-child(4) {
            width: 98%;
            margin: -5px auto 0;
        }

        .child-upper-tooth> :nth-child(5) {
            width: 100%;
            margin: -5px auto 0;
        }

        .child-bottom-tooth {
            max-width: 250px;
            width: 100%;
            position: absolute;
            top: 0;
        }

        .child-bottom-tooth> :nth-child(1) {
            width: 100%;
            margin: auto;
        }

        .child-bottom-tooth> :nth-child(2) {
            width: 98%;
            margin: -2px auto 0;
        }

        .child-bottom-tooth> :nth-child(3) {
            width: 85%;
            margin: -4px auto 0;
        }

        .child-bottom-tooth> :nth-child(4) {
            width: 62%;
            margin: -10px auto 0;
        }

        .child-bottom-tooth> :nth-child(5) {
            width: max-content;
            margin: -30px auto -0px;
        }

        @media (max-width: 400px) {
            .dental-chart {
                gap: 0px;
                align-items: center;
            }

            .upper-layer-box,
            .bottom-layer-box {
                transform: scale(0.8);
                max-width: unset;
                width: 350px;
            }
        }

        /* Enhanced button styles */
        .controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 20px 0;
            justify-content: center;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .controls .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .controls .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .controls .btn.active {
            transform: scale(1.05);
            box-shadow: 0 0 0 2px white, 0 0 0 4px #333;
        }

        .controls .btn-red {
            background-color: #ff4444;
        }

        .controls .btn-blue {
            background-color: #4285f4;
        }

        .controls .btn-yellow {
            background-color: #ffbb33;
        }

        .controls .btn-green {
            background-color: #00c851;
        }

        .controls .btn-secondary {
            background-color: #6c757d;
        }

        /* Category list styles */
        .category-section {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .category-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }

        .category-item {
            flex: 1;
            min-width: 200px;
            background: white;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .category-title {
            font-weight: 600;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .tooth-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .tooth-badge {
            background: #e9ecef;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 12px;
            color: #333;
        }
    </style>

    <!-- Dental Chart JavaScript -->
    <script>
        // Global variable to track selected teeth across functions
        window.selectedTeeth = {
            cavity: new Set(),
            crown: new Set(),
            missing: new Set(),
            retained: new Set()
        };
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dental chart functionality when step 8 is shown
            const step8 = document.querySelector('.step-8');
            if (step8) {
                initializeDentalChart();
            }
        });
        
        function initializeDentalChart() {
            const buttons = document.querySelectorAll('.controls .btn:not(#btn-clear)');
            const clearBtn = document.getElementById('btn-clear');
            const toothContainers = document.querySelectorAll('.tooth-number-container');
            
            let currentAction = 'cavity';
        
            // Set active button
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    buttons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentAction = this.id.replace('btn-', '');
                });
            });
        
            // Handle tooth selection
            toothContainers.forEach(container => {
                const checkbox = container.querySelector('input[type="checkbox"]');
                const svg = container.querySelector('.tooth-svg');
                const toothId = checkbox.id.split('-').pop();
        
                container.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isChecked = checkbox.checked;
                    
                    // Remove from all categories first
                    Object.keys(window.selectedTeeth).forEach(category => {
                        window.selectedTeeth[category].delete(toothId);
                    });
                    
                    // Add to current category if not unchecking
                    if (!isChecked) {
                        window.selectedTeeth[currentAction].add(toothId);
                    }
                    
                    // Update UI
                    updateToothAppearance(svg, currentAction, !isChecked);
                    updateCategoryLists();
                    checkbox.checked = !isChecked;
                    
                    // Update hidden input for form submission
                    updateDentalChartData();
                });
            });
        
            // Clear all selections
            clearBtn.addEventListener('click', function() {
                toothContainers.forEach(container => {
                    const checkbox = container.querySelector('input[type="checkbox"]');
                    const svg = container.querySelector('.tooth-svg');
                    
                    checkbox.checked = false;
                    svg.style.backgroundColor = '';
                    svg.style.borderColor = '';
                    
                    // Clear all categories
                    Object.keys(window.selectedTeeth).forEach(category => {
                        window.selectedTeeth[category].clear();
                    });
                });
                
                updateCategoryLists();
                updateDentalChartData();
            });
            
            // AUTO-RESTORE DENTAL CHART COLORS FROM DATABASE WHEN EDITING
            // Check if we have a patient history ID (editing mode)
            const patientHistoryId = document.getElementById('patient_history_id')?.value;
            if (patientHistoryId) {
                // Fetch dental chart data from database
                $.ajax({
                    url: `/app/appointment/patient-history/dental-chart/${patientHistoryId}`,
                    method: 'GET',
                    success: function(response) {
                        if (response && (response.upper_jaw_treatment || response.lower_jaw_treatment)) {
                            // Parse the JSON data and restore colors
                            restoreDentalChartColors(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Silent error handling for missing data
                    }
                });
            }
            
            // Initialize the dental chart data when the chart is first shown
            // This ensures the hidden inputs are populated even if no teeth are selected
            updateDentalChartData();
        }
        
        function updateToothAppearance(svg, action, isActive) {
            if (!isActive) {
                svg.style.backgroundColor = '';
                svg.style.borderColor = '';
                return;
            }
    
            switch(action) {
                case 'cavity':
                    svg.style.backgroundColor = 'rgba(255, 68, 68, 0.3)';
                    svg.style.borderColor = '#ff4444';
                    break;
                case 'crown':
                    svg.style.backgroundColor = 'rgba(66, 133, 244, 0.3)';
                    svg.style.borderColor = '#4285f4';
                    break;
                case 'missing':
                    svg.style.backgroundColor = 'rgba(255, 187, 51, 0.3)';
                    svg.style.borderColor = '#ffbb33';
                    break;
                case 'retained':
                    svg.style.backgroundColor = 'rgba(0, 200, 81, 0.3)';
                    svg.style.borderColor = '#00c851';
                    break;
            }
        }
    
        function updateCategoryLists() {
            // Clear all lists
            Object.keys(window.selectedTeeth).forEach(category => {
                const list = document.getElementById(`${category}-list`);
                if (list) {
                    list.innerHTML = '';
                    
                    // Add teeth to respective lists
                    window.selectedTeeth[category].forEach(toothId => {
                        const badge = document.createElement('span');
                        badge.className = 'tooth-badge';
                        badge.textContent = toothId.toUpperCase();
                        list.appendChild(badge);
                    });
                }
            });
        }
    
        function updateDentalChartData() {
            const dentalChartData = document.getElementById('dental_chart_data');
            const upperJawData = document.getElementById('upper_jaw_data');
            const lowerJawData = document.getElementById('lower_jaw_data');
            
            if (dentalChartData && upperJawData && lowerJawData) {
                // Organize teeth by jaw and condition
                const jawData = {
                    upper_jaw: {},
                    lower_jaw: {}
                };
    
                // Define upper jaw teeth (1-16 and A-J)
                const upperJawTeeth = new Set([
                    '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16',
                    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'
                ]);
    
                // Define lower jaw teeth (17-32 and K-T)
                const lowerJawTeeth = new Set([
                    '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32',
                    'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't'
                ]);
    
                // Process each category
                Object.keys(window.selectedTeeth).forEach(category => {
                    const upperTeeth = [];
                    const lowerTeeth = [];
    
                    window.selectedTeeth[category].forEach(toothId => {
                        if (upperJawTeeth.has(toothId.toLowerCase())) {
                            upperTeeth.push(toothId.toUpperCase());
                        } else if (lowerJawTeeth.has(toothId.toLowerCase())) {
                            lowerTeeth.push(toothId.toUpperCase());
                        }
                    });
    
                    // Add to upper jaw if there are teeth
                    if (upperTeeth.length > 0) {
                        jawData.upper_jaw[`mark_${category}`] = upperTeeth;
                    }
    
                    // Add to lower jaw if there are teeth
                    if (lowerTeeth.length > 0) {
                        jawData.lower_jaw[`mark_${category}`] = lowerTeeth;
                    }
                });
    
                // Store the organized data in respective fields
                dentalChartData.value = JSON.stringify(jawData);
                upperJawData.value = JSON.stringify(jawData.upper_jaw);
                lowerJawData.value = JSON.stringify(jawData.lower_jaw);
            }
        }

        // Function to restore dental chart colors from database data
        function restoreDentalChartColors(data) {
            console.log('=== RESTORING DENTAL CHART COLORS ===');
            
            let upperJawData = {};
            let lowerJawData = {};
            
            try {
                // Parse upper jaw data
                if (data.upper_jaw_treatment && typeof data.upper_jaw_treatment === 'string') {
                    upperJawData = JSON.parse(data.upper_jaw_treatment);
                }
                
                // Parse lower jaw data
                if (data.lower_jaw_treatment && typeof data.lower_jaw_treatment === 'string') {
                    lowerJawData = JSON.parse(data.lower_jaw_treatment);
                }
            } catch (e) {
                console.error('Error parsing dental chart data:', e);
                return;
            }
            
            
            // Clear existing selections first
            clearDentalChartSelections();
            
            // Restore upper jaw colors
            Object.keys(upperJawData).forEach(category => {
                if (category.startsWith('mark_')) {
                    const treatmentType = category.replace('mark_', '');
                    const teeth = upperJawData[category];
                    
                    if (Array.isArray(teeth)) {
                        teeth.forEach(toothId => {
                            // Try multiple selectors to handle case sensitivity
                            const selectors = [
                                `#adult-upper-tooth-${toothId}`,
                                `#adult-upper-tooth-${toothId.toLowerCase()}`,
                                `#adult-upper-tooth-${toothId.toUpperCase()}`,
                                `#child-upper-tooth-${toothId}`,
                                `#child-upper-tooth-${toothId.toLowerCase()}`,
                                `#child-upper-tooth-${toothId.toUpperCase()}`
                            ];
                            
                            let toothElement = null;
                            let checkbox = null;
                            
                            // First try to find the checkbox directly
                            for (const selector of selectors) {
                                checkbox = document.querySelector(selector);
                                if (checkbox) break;
                            }
                            
                            if (checkbox) {
                                // Find the parent container that holds both checkbox and SVG
                                const container = checkbox.closest('.tooth-number-container');
                                if (container) {
                                    const svg = container.querySelector('.tooth-svg');
                                    
                                    if (svg) {
                                        // Apply color
                                        updateToothAppearance(svg, treatmentType, true);
                                        
                                        // Update checkbox state
                                        checkbox.checked = true;
                                        
                                        // Add to selected teeth tracking
                                        if (window.selectedTeeth[treatmentType]) {
                                            window.selectedTeeth[treatmentType].add(toothId);
                                        }
                                    } else {
                                        console.warn(`Missing SVG for tooth ${toothId} in container:`, container);
                                    }
                                } else {
                                    console.warn(`No container found for checkbox ${toothId}`);
                                }
                            } else {
                                console.warn(`No checkbox found for ${toothId} in ${treatmentType}. Tried selectors:`, selectors);
                            }
                        });
                    }
                }
            });
            
            // Restore lower jaw colors
            Object.keys(lowerJawData).forEach(category => {
                if (category.startsWith('mark_')) {
                    const treatmentType = category.replace('mark_', '');
                    const teeth = lowerJawData[category];
                    
                    if (Array.isArray(teeth)) {
                        teeth.forEach(toothId => {
                            // Try multiple selectors to handle case sensitivity
                            const selectors = [
                                `#adult-bottom-tooth-${toothId}`,
                                `#adult-bottom-tooth-${toothId.toLowerCase()}`,
                                `#adult-bottom-tooth-${toothId.toUpperCase()}`,
                                `#child-bottom-tooth-${toothId}`,
                                `#child-bottom-tooth-${toothId.toLowerCase()}`,
                                `#child-bottom-tooth-${toothId.toUpperCase()}`
                            ];
                            
                            let checkbox = null;
                            
                            // First try to find the checkbox directly
                            for (const selector of selectors) {
                                checkbox = document.querySelector(selector);
                                if (checkbox) break;
                            }
                            
                            if (checkbox) {
                                
                                // Find the parent container that holds both checkbox and SVG
                                const container = checkbox.closest('.tooth-number-container');
                                if (container) {
                                    const svg = container.querySelector('.tooth-svg');
                                    
                                    if (svg) {
                                        
                                        // Apply color
                                        updateToothAppearance(svg, treatmentType, true);
                                        
                                        // Update checkbox state
                                        checkbox.checked = true;
                                        
                                        // Add to selected teeth tracking
                                        if (window.selectedTeeth[treatmentType]) {
                                            window.selectedTeeth[treatmentType].add(toothId);
                                        }
                                    } else {
                                        console.warn(`Missing SVG for tooth ${toothId} in container:`, container);
                                    }
                                } else {
                                    console.warn(`No container found for checkbox ${toothId}`);
                                }
                            } else {
                                console.warn(`No checkbox found for ${toothId} in ${treatmentType}. Tried selectors:`, selectors);
                            }
                        });
                    }
                }
            });
            
            // Update category lists and dental chart data
            updateCategoryLists();
            updateDentalChartData();
        }
        
        // Function to clear all dental chart selections
        function clearDentalChartSelections() {
            const toothContainers = document.querySelectorAll('.tooth-number-container');
            toothContainers.forEach(container => {
                const checkbox = container.querySelector('input[type="checkbox"]');
                const svg = container.querySelector('.tooth-svg');
                
                checkbox.checked = false;
                svg.style.backgroundColor = '';
                svg.style.borderColor = '';
            });
            
            // Clear selected teeth tracking
            Object.keys(window.selectedTeeth).forEach(category => {
                window.selectedTeeth[category].clear();
            });
        }
        
        // AUTO-RESTORE DENTAL CHART COLORS FROM DATABASE WHEN EDITING
        // This function will be called when the dental chart step is shown
        function autoRestoreDentalChartColors() {
            // Check if we have a patient history ID (editing mode)
            const patientHistoryId = document.getElementById('patient_history_id')?.value;
            if (patientHistoryId) {
               
                // Fetch dental chart data from database
                $.ajax({
                    url: `/app/appointment/patient-history/dental-chart/${patientHistoryId}`,
                    method: 'GET',
                    success: function(response) {
                        
                        if (response && (response.upper_jaw_treatment || response.lower_jaw_treatment)) {
                            // Parse the JSON data and restore colors
                            restoreDentalChartColors(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('No existing dental chart data found or error occurred:', error);
                    }
                });
            }
        }
    </script>
</div>