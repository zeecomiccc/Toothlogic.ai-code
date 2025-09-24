<div class="offcanvas offcanvas-end offcanvas-w-40" tabindex="-1" id="review-show-sidebar" aria-labelledby="reviewShowLabel">
    <div class="offcanvas-header">
        <div class="offcanvas-header border-bottom">
            <h4 class="offcanvas-title" id="reviewShowLabel">{{ __('clinic.reviews') }}</h4>
        </div>
        <button type="button" data-bs-dismiss="offcanvas" aria-label="Close" class="btn-close-offcanvas">
            <i class="ph ph-x-circle"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <!-- Patient Information Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="mb-3"><i class="ph ph-user me-2"></i>{{ __('feedback.patient_details') }}</h5>
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="me-4">
                                <img id="show-avatar" src="{{ default_user_avatar() }}" class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-3 fw-bold" id="show-name">-</h5>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ph ph-envelope me-2 text-muted"></i>
                                        <span id="show-email" class="text-muted">-</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="ph ph-phone me-2 text-primary"></i>
                                        <span id="show-phone" class="text-primary fw-medium">-</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="ph ph-user-circle me-2 text-muted"></i>
                                        <span class="text-muted">{{ __('feedback.age') }}: </span>
                                        <span class="fw-bold text-primary ms-1" id="show-age">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visit Details Section -->
            <div class="col-md-6">
                <h5 class="mb-3"><i class="ph ph-calendar-check me-2"></i>{{ __('feedback.visit_details') }}</h5>
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-5 fw-bold text-muted">{{ __('feedback.doctor') }}:</div>
                            <div class="col-7" id="show-doctor">-</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold text-muted">{{ __('feedback.service') }}:</div>
                            <div class="col-7" id="show-service">-</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold text-muted">{{ __('feedback.clinic_location') }}:</div>
                            <div class="col-7" id="show-clinic-location">-</div>
                        </div>
                        <div class="row">
                            <div class="col-5 fw-bold text-muted">{{ __('feedback.referral_source') }}:</div>
                            <div class="col-7" id="show-referral-source">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Rating Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-star me-2"></i>{{ __('feedback.overall_rating') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div id="show-overall-stars" class="text-warning fs-2"></div>
                            <div class="d-flex align-items-baseline gap-1">
                                <span class="display-6 fw-bold text-primary" id="show-experience-rating">-</span>
                                <span class="text-muted">/10</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Detailed Ratings Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-chart-line me-2"></i>{{ __('feedback.ratings') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="fw-bold mb-2">{{ __('feedback.dentist_explanation') }}:</div>
                            <div class="ms-3">
                                <span class="badge bg-primary fs-6" id="show-dentist-explanation">-</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold mb-2">{{ __('feedback.pricing_satisfaction') }}:</div>
                            <div class="ms-3">
                                <span id="show-pricing-satisfaction">-</span>
                                <div id="show-pricing-stars" class="text-warning d-inline-block ms-2"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold mb-2">{{ __('feedback.staff_courtesy') }}:</div>
                            <div class="ms-3">
                                <span class="badge bg-primary fs-6" id="show-staff-courtesy">-</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold mb-2">{{ __('feedback.treatment_satisfaction') }}:</div>
                            <div class="ms-3">
                                <span id="show-treatment-satisfaction">-</span>
                                <div id="show-treatment-stars" class="text-warning d-inline-block ms-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Comments Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-chat-circle-text me-2"></i>{{ __('feedback.review') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">{{ __('feedback.title') }}:</div>
                            <div class="col-8" id="show-title">-</div>
                        </div>
                        <div class="row">
                            <div class="col-4 fw-bold">{{ __('feedback.additional_comments') }}:</div>
                            <div class="col-8">
                                <div class="bg-light rounded p-3" id="show-review-msg">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


