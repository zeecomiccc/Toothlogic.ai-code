<style>
    /* Star Rating Styles */
    .star {
        cursor: pointer;
        transition: transform 0.3s ease;
        display: inline-block;
        margin: 0 3px;
    }

    .star:hover {
        transform: scale(1.2);
    }

    .star.selected .icon-fill {
        display: inline-block !important;
        color: #ffc107 !important;
    }

    .star.selected .icon-normal {
        display: none !important;
    }

    .star.hover .icon-fill {
        display: inline-block !important;
        color: #ffc107 !important;
    }

    .star.hover .icon-normal {
        display: none !important;
    }

    .icon-fill {
        display: none;
    }

    .icon-normal {
        display: inline-block;
        color: #6E8192 !important;
    }

    .star .icon {
        font-size: 1.8rem;
    }

    .rating-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 5px;
    }

    .rating-list li {
        display: inline-block;
    }
</style>

<!-- Patient Feedback Side Collapse Panel -->
<div class="offcanvas offcanvas-end offcanvas-w-40" tabindex="-1" id="patient-feedback-sidebar" aria-labelledby="patientFeedbackLabel">
    <div class="offcanvas-header">
        <div class="offcanvas-header border-bottom">
            <h4 class="offcanvas-title" id="patientFeedbackLabel">{{ __('feedback.add_patient_feedback') }}</h4>
        </div>
        <button type="button" data-bs-dismiss="offcanvas" aria-label="Close" class="btn-close-offcanvas">
            <i class="ph ph-x-circle"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <form id="patientFeedbackForm">
            <!-- Patient, Doctor, and Service Selection -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">{{ __('feedback.select_patient') }}</label>
                    <select class="form-select select2" id="patientSelect" name="user_id" required>
                        <option value="">{{ __('feedback.select_patient_placeholder') }}</option>
                    </select>
                    <div id="patient-error" class="text-danger d-none mt-1">
                        {{ __('feedback.patient_required') }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">{{ __('feedback.select_doctor') }}</label>
                    <select class="form-select select2" id="doctorSelect" name="doctor_id" required disabled>
                        <option value="">{{ __('feedback.select_doctor_placeholder') }}</option>
                    </select>
                    <div id="doctor-error" class="text-danger d-none mt-1">
                        {{ __('feedback.doctor_required') }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">{{ __('feedback.select_service') }}</label>
                    <select class="form-select select2" id="serviceSelect" name="service_id" required disabled>
                        <option value="">{{ __('feedback.select_service_placeholder') }}</option>
                    </select>
                    <div id="service-error" class="text-danger d-none mt-1">
                        {{ __('feedback.service_required') }}
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('feedback.name') }}</label>
                    <input type="text" class="form-control" id="feedbackName" name="name" placeholder="{{ __('feedback.name_placeholder') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('feedback.email') }}</label>
                    <input type="email" class="form-control" id="feedbackEmail" name="email" placeholder="{{ __('feedback.email_placeholder') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('feedback.phone') }}</label>
                    <input type="tel" class="form-control" id="feedbackPhone" name="phone" placeholder="{{ __('feedback.phone_placeholder') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('feedback.age') }}</label>
                    <input type="number" class="form-control" id="feedbackAge" name="age" min="1" max="120" placeholder="{{ __('feedback.age_placeholder') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('feedback.treatments_received') }}</label>
                    <textarea class="form-control" id="feedbackTreatments" name="treatments" rows="2" placeholder="{{ __('feedback.treatments_placeholder') }}"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('feedback.clinic_location') }}</label>
                    <input type="text" class="form-control" id="feedbackLocation" name="clinic_location" placeholder="{{ __('feedback.clinic_location_placeholder') }}">
                </div>
            </div>

            <!-- How did you come across this clinic? -->
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('feedback.how_did_you_come_across') }}</label>
                    <div class="radio-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="referral_source" id="modalSocialMedia" value="social_media">
                            <label class="form-check-label" for="modalSocialMedia">{{ __('feedback.social_media') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="referral_source" id="modalWalkIn" value="walk_in">
                            <label class="form-check-label" for="modalWalkIn">{{ __('feedback.walk_in') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="referral_source" id="modalReferredFriend" value="referred_friend">
                            <label class="form-check-label" for="modalReferredFriend">{{ __('feedback.referred_by_friend') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="referral_source" id="modalOther" value="other">
                            <label class="form-check-label" for="modalOther">{{ __('feedback.other') }}</label>
                        </div>
                    </div>
                    <div id="modalReferralOtherBox" class="mt-2" style="display:none;">
                        <input type="text" class="form-control" id="modalReferralOtherInput" name="referral_source_other" placeholder="{{ __('feedback.other_specify') }}">
                    </div>
                </div>
            </div>

            <!-- Overall Experience Rating -->
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label required">{{ __('feedback.experience_at_clinic') }}</label>
                    <div class="mt-3">
                        <ul class="rating-list">
                            @for($i = 1; $i <= 10; $i++)
                            <li data-value="{{ $i }}" class="star modal-experience-star">
                                <span class="icon">
                                    <i class="ph-fill ph-star icon-fill"></i>
                                    <i class="ph ph-star icon-normal"></i>
                                </span>
                            </li>
                            @endfor
                        </ul>
                        <input type="hidden" name="experience_rating" id="experience_rating_value" value="">
                        <div id="modal-experience-rating-error" class="text-danger d-none mt-2">
                            {{ __('feedback.please_select_rating') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dentist Explanation Rating -->
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('feedback.dentist_explanation') }}</label>
                    <div class="rating-scale mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="radio-group d-flex gap-3">
                                <span class="text-muted small">{{ __('feedback.not_at_all') }}</span>
                                @for($i = 1; $i <= 10; $i++)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dentist_explanation" id="modalExplanation{{$i}}" value="{{$i}}">
                                    <label class="form-check-label small" for="modalExplanation{{$i}}">{{$i}}</label>
                                </div>
                                @endfor
                                <span class="text-muted small">{{ __('feedback.yes_completely') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Treatment Pricing Satisfaction -->
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('feedback.pricing_satisfaction') }}</label>
                    <div class="mt-3">
                        <ul class="rating-list">
                            @for($i = 1; $i <= 10; $i++)
                            <li data-value="{{ $i }}" class="star modal-pricing-star">
                                <span class="icon">
                                    <i class="ph-fill ph-star icon-fill"></i>
                                    <i class="ph ph-star icon-normal"></i>
                                </span>
                            </li>
                            @endfor
                        </ul>
                        <input type="hidden" name="pricing_satisfaction" id="pricing_rating_value" value="">
                    </div>
                </div>
            </div>

            <!-- Staff Courtesy Rating -->
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('feedback.staff_courtesy') }}</label>
                    <div class="rating-scale mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="radio-group d-flex gap-3">
                                <span class="text-muted small">{{ __('feedback.poor') }}</span>
                                @for($i = 1; $i <= 10; $i++)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="staff_courtesy" id="modalCourtesy{{$i}}" value="{{$i}}">
                                    <label class="form-check-label small" for="modalCourtesy{{$i}}">{{$i}}</label>
                                </div>
                                @endfor
                                <span class="text-muted small">{{ __('feedback.excellent') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Treatment Satisfaction -->
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('feedback.treatment_satisfaction') }}</label>
                    <div class="mt-3">
                        <ul class="rating-list">
                            @for($i = 1; $i <= 10; $i++)
                            <li data-value="{{ $i }}" class="star modal-treatment-star">
                                <span class="icon">
                                    <i class="ph-fill ph-star icon-fill"></i>
                                    <i class="ph ph-star icon-normal"></i>
                                </span>
                            </li>
                            @endfor
                        </ul>
                        <input type="hidden" name="treatment_satisfaction" id="treatment_rating_value" value="">
                    </div>
                </div>
            </div>

            <!-- Additional Comments -->
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('feedback.additional_comments') }}</label>
                    <textarea class="form-control" placeholder="{{ __('feedback.additional_comments_placeholder') }}" rows="4" id="feedbackTextarea" name="review_msg"></textarea>
                </div>
            </div>
        </form>
    </div>
    <!-- Action Buttons -->
    <div class="d-flex justify-content-end gap-3 pt-3 border-top px-3 pb-3">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="offcanvas">{{ __('messages.cancel') }}</button>
        <button class="btn btn-primary" type="button" id="submitFeedbackBtn">{{ __('feedback.submit') }}</button>
    </div>
</div>


