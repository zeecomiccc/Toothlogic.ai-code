 <div class="modal fade rating-modal" id="review-service" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content section-bg rounded">
            <div class="modal-body modal-body-inner rate-us-modal">
                <div class="close-modal-btn" data-bs-dismiss="modal">
                    <i class="ph ph-x align-middle"></i>                                                                
                </div>
                <div class="text-center">
                    <form id="reviewForm">
                        <div class="mt-5 pt-3 text-center rate-box">
                            <h5>{{ __('feedback.rate_our_service') }}</h5>
                            <p class="mb-5">{{ __('feedback.feedback_message') }}</p>

                            <!-- Personal Information Section -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group text-start">
                                        <label for="reviewName" class="form-label">{{ __('feedback.name') }}</label>
                                        <input type="text" class="form-control" id="reviewName" name="name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-start">
                                        <label for="reviewEmail" class="form-label">{{ __('feedback.email') }}</label>
                                        <input type="email" class="form-control" id="reviewEmail" name="email">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group text-start">
                                        <label for="reviewPhone" class="form-label">{{ __('feedback.phone') }}</label>
                                        <input type="tel" class="form-control" id="reviewPhone" name="phone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-start">
                                        <label for="reviewAge" class="form-label">{{ __('feedback.age') }}</label>
                                        <input type="number" class="form-control" id="reviewAge" name="age" min="1" max="120">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group text-start">
                                        <label for="reviewTreatments" class="form-label">{{ __('feedback.treatments_received') }}</label>
                                        <textarea class="form-control" id="reviewTreatments" name="treatments" rows="2" placeholder="{{ __('feedback.treatments_placeholder') }}"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-start">
                                        <label for="reviewLocation" class="form-label">{{ __('feedback.clinic_location') }}</label>
                                        <input type="text" class="form-control" id="reviewLocation" name="clinic_location" placeholder="{{ __('feedback.clinic_location_placeholder') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- How did you come across this clinic? -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group text-start">
                                        <label class="form-label">{{ __('feedback.how_did_you_come_across') }}</label>
                                        <div class="radio-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="referral_source" id="socialMedia" value="social_media">
                                                <label class="form-check-label" for="socialMedia">{{ __('feedback.social_media') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="referral_source" id="walkIn" value="walk_in">
                                                <label class="form-check-label" for="walkIn">{{ __('feedback.walk_in') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="referral_source" id="referredFriend" value="referred_friend">
                                                <label class="form-check-label" for="referredFriend">{{ __('feedback.referred_by_friend') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="referral_source" id="other" value="other">
                                                <label class="form-check-label" for="other">{{ __('feedback.other') }}</label>
                                            </div>
                                        </div>
                                        <div id="referralOtherBox" class="mt-2" style="display:none;">
                                            <input type="text" class="form-control" id="referralOtherInput" name="referral_source_other" placeholder="{{ __('feedback.other_specify') }}">
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Overall Experience Rating -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group text-start">
                                        <label class="form-label">{{ __('feedback.experience_at_clinic') }}</label>
                                        <div class="mt-2">
                                            <ul class="list-inline m-0 p-0 d-flex align-items-center gap-3 rating-list">
                                                <li data-value="1" class="star experience-star">
                                                    <span class="text-warning fs-4 icon">
                                                        <i class="ph-fill ph-star icon-fill"></i>
                                                        <i class="ph ph-star icon-normal"></i>
                                                    </span>
                                                </li>
                                                <li data-value="2" class="star experience-star">
                                                    <span class="text-warning fs-4 icon">
                                                        <i class="ph-fill ph-star icon-fill"></i>
                                                        <i class="ph ph-star icon-normal"></i>
                                                    </span>
                                                </li>
                                                <li data-value="3" class="star experience-star">
                                                    <span class="text-warning fs-4 icon">
                                                        <i class="ph-fill ph-star icon-fill"></i>
                                                        <i class="ph ph-star icon-normal"></i>
                                                    </span>
                                                </li>
                                                <li data-value="4" class="star experience-star">
                                                    <span class="text-warning fs-4 icon">
                                                        <i class="ph-fill ph-star icon-fill"></i>
                                                        <i class="ph ph-star icon-normal"></i>
                                                    </span>
                                                </li>
                                                <li data-value="5" class="star experience-star">
                                                    <span class="text-warning fs-4 icon">
                                                        <i class="ph-fill ph-star icon-fill"></i>
                                                        <i class="ph ph-star icon-normal"></i>
                                                    </span>
                                                </li>
                                            </ul>
                                            <div id="experience-rating-error" class="text-danger d-none mt-2">
                                                {{ __('feedback.please_select_rating') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dentist Explanation Rating -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group text-start">
                                        <label class="form-label">{{ __('feedback.dentist_explanation') }}</label>
                                        <div class="rating-scale mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                
                                                <div class="radio-group d-flex gap-3">
                                                    <span class="text-muted small">{{ __('feedback.not_at_all') }}</span>
                                                    @for($i = 1; $i <= 10; $i++)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="dentist_explanation" id="explanation{{$i}}" value="{{$i}}">
                                                        <label class="form-check-label small" for="explanation{{$i}}">{{$i}}</label>
                                                    </div>
                                                    @endfor
                                                    <span class="text-muted small">{{ __('feedback.yes_completely') }}</span>
                                                </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Treatment Pricing Satisfaction -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group text-start">
                                        <label class="form-label">{{ __('feedback.pricing_satisfaction') }}</label>
                                        <div class="mt-2">
                                            <ul class="list-inline m-0 p-0 d-flex align-items-center gap-3 rating-list">
                                                <li data-value="1" class="star pricing-star">
                                        <span class="text-warning fs-4 icon">
                                            <i class="ph-fill ph-star icon-fill"></i>
                                            <i class="ph ph-star icon-normal"></i>
                                        </span>
                                    </li>
                                                <li data-value="2" class="star pricing-star">
                                        <span class="text-warning fs-4 icon">
                                            <i class="ph-fill ph-star icon-fill"></i>
                                            <i class="ph ph-star icon-normal"></i>
                                        </span>
                                    </li>
                                                <li data-value="3" class="star pricing-star">
                                        <span class="text-warning fs-4 icon">
                                            <i class="ph-fill ph-star icon-fill"></i>
                                            <i class="ph ph-star icon-normal"></i>
                                        </span>
                                    </li>
                                                <li data-value="4" class="star pricing-star">
                                        <span class="text-warning fs-4 icon">
                                            <i class="ph-fill ph-star icon-fill"></i>
                                            <i class="ph ph-star icon-normal"></i>
                                        </span>
                                    </li>
                                                <li data-value="5" class="star pricing-star">
                                        <span class="text-warning fs-4 icon">
                                            <i class="ph-fill ph-star icon-fill"></i>
                                            <i class="ph ph-star icon-normal"></i>
                                        </span>
                                    </li>
                                </ul>
                                            <div id="pricing-rating-error" class="text-danger d-none mt-2">
                                                {{ __('feedback.please_select_rating') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Staff Courtesy Rating -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group text-start">
                                        <label class="form-label">{{ __('feedback.staff_courtesy') }}</label>
                                        <div class="rating-scale mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="radio-group d-flex gap-3">
                                                <span class="text-muted small">{{ __('feedback.poor') }}</span>
                                                    @for($i = 1; $i <= 10; $i++)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="staff_courtesy" id="courtesy{{$i}}" value="{{$i}}">
                                                        <label class="form-check-label small" for="courtesy{{$i}}">{{$i}}</label>
                                                    </div>
                                                    @endfor
                                                <span class="text-muted small">{{ __('feedback.excellent') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Treatment Satisfaction -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group text-start">
                                        <label class="form-label">{{ __('feedback.treatment_satisfaction') }}</label>
                                        <div class="mt-2">
                                            <ul class="list-inline m-0 p-0 d-flex align-items-center gap-3 rating-list">
                                                <li data-value="1" class="star treatment-star">
                                                    <span class="text-warning fs-4 icon">
                                                        <i class="ph-fill ph-star icon-fill"></i>
                                                        <i class="ph ph-star icon-normal"></i>
                                                    </span>
                                                </li>
                                                <li data-value="2" class="star treatment-star">
                                                    <span class="text-warning fs-4 icon">
                                                        <i class="ph-fill ph-star icon-fill"></i>
                                                        <i class="ph ph-star icon-normal"></i>
                                                    </span>
                                                </li>
                                                <li data-value="3" class="star treatment-star">
                                                    <span class="text-warning fs-4 icon">
                                                        <i class="ph-fill ph-star icon-fill"></i>
                                                        <i class="ph ph-star icon-normal"></i>
                                                    </span>
                                                </li>
                                                <li data-value="4" class="star treatment-star">
                                                    <span class="text-warning fs-4 icon">
                                                        <i class="ph-fill ph-star icon-fill"></i>
                                                        <i class="ph ph-star icon-normal"></i>
                                                    </span>
                                                </li>
                                                <li data-value="5" class="star treatment-star">
                                                    <span class="text-warning fs-4 icon">
                                                        <i class="ph-fill ph-star icon-fill"></i>
                                                        <i class="ph ph-star icon-normal"></i>
                                                    </span>
                                                </li>
                                            </ul>
                                            <div id="treatment-rating-error" class="text-danger d-none mt-2">
                                    {{ __('feedback.please_select_rating') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>

                            <!-- Original Review Text -->
                                <div class="mt-5">
                                <div class="form-group text-start">
                                    <label for="reviewTextarea" class="form-label">{{ __('feedback.additional_comments') }}</label>
                                    <textarea class="form-control" placeholder="{{ __('feedback.additional_comments_placeholder') }}" rows="4" id="reviewTextarea"></textarea>
                                </div>
                            </div>

                            <!-- Hidden rating error element for backward compatibility -->
                            <div id="rating-error" class="text-danger d-none">
                                {{ __('feedback.please_select_rating') }}
                                </div>

                                <div class="mt-5 pt-2">
                                    <button type="submit" class="btn btn-secondary" id="submitBtn">{{ __('feedback.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function () {
        let selectedRating = 0;   
        let selectedExperienceRating = 0;
        let selectedPricingRating = 0;
        let selectedTreatmentRating = 0;
        let serviceId = null;     
        let doctorId = null;      
        let reviewId = null;      
        
        const stars = document.querySelectorAll('.star:not(.experience-star):not(.pricing-star):not(.treatment-star)');
        const experienceStars = document.querySelectorAll('.experience-star');
        const pricingStars = document.querySelectorAll('.pricing-star');
        const treatmentStars = document.querySelectorAll('.treatment-star');
        const ratingError = document.getElementById('rating-error');
        const experienceRatingError = document.getElementById('experience-rating-error');
        const pricingRatingError = document.getElementById('pricing-rating-error');
        const treatmentRatingError = document.getElementById('treatment-rating-error');
        const reviewForm = document.getElementById('reviewForm');
        const reviewTextarea = document.getElementById('reviewTextarea');

        function showRatingError(element, show = true) {
            if (!element) return; // Guard against null elements
            
            if (show) {
                element.classList.remove('d-none');
                element.classList.add('d-block', 'mt-2');
            } else {
                element.classList.remove('d-block');
                element.classList.add('d-none');
            }
        }

        function highlightStars(starElements, rating) {
            starElements.forEach(function(star) {
                const starValue = parseInt(star.getAttribute('data-value'));
                if (starValue <= rating) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
        }

        function resetRating() {
            selectedRating = 0;
            selectedExperienceRating = 0;
            selectedPricingRating = 0;
            selectedTreatmentRating = 0;
            $('.star').removeClass('selected');
            showRatingError(ratingError, false);
            showRatingError(experienceRatingError, false);
            showRatingError(pricingRatingError, false);
            showRatingError(treatmentRatingError, false);
            reviewTextarea.value = '';
            $('#reviewForm')[0].reset();
            
            // Clear validation states
            $('.form-control').removeClass('is-invalid');
            $('.radio-group').removeClass('border-danger');
            $('.rating-scale').removeClass('border-danger');
        }

        // Original star rating functionality
        $('.star:not(.experience-star):not(.pricing-star):not(.treatment-star)').on('click', function () {
            selectedRating = $(this).data('value');
            highlightStars(stars, selectedRating);
            showRatingError(ratingError, false); 
        });

        // Experience star rating
        $('.experience-star').on('click', function () {
            selectedExperienceRating = $(this).data('value');
            highlightStars(experienceStars, selectedExperienceRating);
            showRatingError(experienceRatingError, false); 
        });

        // Pricing star rating
        $('.pricing-star').on('click', function () {
            selectedPricingRating = $(this).data('value');
            highlightStars(pricingStars, selectedPricingRating);
            showRatingError(pricingRatingError, false); 
        });

        // Treatment star rating
        $('.treatment-star').on('click', function () {
            selectedTreatmentRating = $(this).data('value');
            highlightStars(treatmentStars, selectedTreatmentRating);
            showRatingError(treatmentRatingError, false); 
        });

        // Show/hide 'Other' textbox for referral source
        $('input[name="referral_source"]').on('change', function() {
            if ($('#other').is(':checked')) {
                $('#referralOtherBox').show();
                $('#referralOtherInput').prop('required', true);
            } else {
                $('#referralOtherBox').hide();
                $('#referralOtherInput').prop('required', false).val('');
            }
        });

        $('#reviewForm').on('submit', function (event) {
            event.preventDefault();

            // Remove all validation logic, just collect and submit the form data
            const formData = {
                service_id: serviceId,
                doctor_id: doctorId,
                rating: selectedRating,
                review_msg: reviewTextarea.value.trim(),
                id: reviewId,
                name: $('#reviewName').val(),
                email: $('#reviewEmail').val(),
                phone: $('#reviewPhone').val(),
                age: $('#reviewAge').val(),
                treatments: $('#reviewTreatments').val(),
                referral_source: $('input[name="referral_source"]:checked').val(),
                referral_source_other: $('#referralOtherInput').val(),
                clinic_location: $('#reviewLocation').val(),
                experience_rating: selectedExperienceRating,
                dentist_explanation: $('input[name="dentist_explanation"]:checked').val(),
                pricing_satisfaction: selectedPricingRating,
                staff_courtesy: $('input[name="staff_courtesy"]:checked').val(),
                treatment_satisfaction: selectedTreatmentRating
            };

            const url = '{{ route('save-rating') }}?is_ajax=1';
            
            $.ajax({
                url: url,
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify(formData),
                success: function (data) {
                    Swal.fire({
                        icon: 'success',
                        text: data.message
                    });
                    $('#review-service').modal('hide');
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        text: 'An error occurred while submitting your review.'
                    });
                }
            });
        });

        $('#review-service').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            serviceId = button.data('service-id');
            doctorId = button.data('doctor-id');
            reviewId = button.data('review-id');
            const rating = button.data('rating');
            const reviewMsg = button.data('review-msg');

            showRatingError(ratingError, false); 
            showRatingError(experienceRatingError, false);
            showRatingError(pricingRatingError, false);
            showRatingError(treatmentRatingError, false);

            if (reviewId) {
                // Populate all form fields with existing data
                selectedRating = rating;
                reviewTextarea.value = reviewMsg;
                highlightStars(stars, selectedRating);
                
                // Personal Information
                $('#reviewName').val(button.data('name') || '');
                $('#reviewEmail').val(button.data('email') || '');
                $('#reviewPhone').val(button.data('phone') || '');
                $('#reviewAge').val(button.data('age') || '');
                $('#reviewTreatments').val(button.data('treatments') || '');
                $('#reviewLocation').val(button.data('clinic-location') || '');
                
                // Referral Source
                const referralSource = button.data('referral-source');
                if (referralSource) {
                    $(`input[name="referral_source"][value="${referralSource}"]`).prop('checked', true);
                    if (referralSource === 'other') {
                        $('#referralOtherBox').show();
                        $('#referralOtherInput').val(button.data('referral-source-other') || '');
                    }
                }
                
                // Experience Rating
                const experienceRating = button.data('experience-rating');
                if (experienceRating) {
                    selectedExperienceRating = experienceRating;
                    highlightStars(experienceStars, selectedExperienceRating);
                }
                
                // Dentist Explanation
                const dentistExplanation = button.data('dentist-explanation');
                if (dentistExplanation) {
                    $(`input[name="dentist_explanation"][value="${dentistExplanation}"]`).prop('checked', true);
                }
                
                // Pricing Satisfaction
                const pricingSatisfaction = button.data('pricing-satisfaction');
                if (pricingSatisfaction) {
                    selectedPricingRating = pricingSatisfaction;
                    highlightStars(pricingStars, selectedPricingRating);
                }
                
                // Staff Courtesy
                const staffCourtesy = button.data('staff-courtesy');
                if (staffCourtesy) {
                    $(`input[name="staff_courtesy"][value="${staffCourtesy}"]`).prop('checked', true);
                }
                
                // Treatment Satisfaction
                const treatmentSatisfaction = button.data('treatment-satisfaction');
                if (treatmentSatisfaction) {
                    selectedTreatmentRating = treatmentSatisfaction;
                    highlightStars(treatmentStars, selectedTreatmentRating);
                }
            } else {
                resetRating();
            }
        });

        $('#review-service').on('hidden.bs.modal', function () {
            resetRating(); 
        });

        // Hover effects for all star groups
        $('.star:not(.experience-star):not(.pricing-star):not(.treatment-star)').hover(
            function() {
                const hoverValue = $(this).data('value');
                highlightStars(stars, hoverValue);
            },
            function() {
                highlightStars(stars, selectedRating); 
            }
        );

        $('.experience-star').hover(
            function() {
                const hoverValue = $(this).data('value');
                highlightStars(experienceStars, hoverValue);
            },
            function() {
                highlightStars(experienceStars, selectedExperienceRating); 
            }
        );

        $('.pricing-star').hover(
            function() {
                const hoverValue = $(this).data('value');
                highlightStars(pricingStars, hoverValue);
            },
            function() {
                highlightStars(pricingStars, selectedPricingRating); 
            }
        );

        $('.treatment-star').hover(
            function() {
                const hoverValue = $(this).data('value');
                highlightStars(treatmentStars, hoverValue);
            },
            function() {
                highlightStars(treatmentStars, selectedTreatmentRating); 
            }
        );
    });
</script>
@endpush

<style>
.star {
    cursor: pointer; 
    transition: transform 0.3s ease;  
}

.star:hover {
    transform: scale(1.2);  
}

.star.selected .icon-fill {
    display: inline-block;  
}

.star.selected .icon-normal {
    display: none;  
}

.icon-fill {
    display: none; 
}

.icon-normal {
    display: inline-block; 
}

.radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.rating-scale .radio-group {
    justify-content: space-between;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.modal-lg {
    max-width: 800px;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.border-danger {
    border: 1px solid #dc3545 !important;
    border-radius: 0.375rem;
    padding: 0.5rem;
}

.radio-group.border-danger {
    padding: 0.5rem;
    border-radius: 0.375rem;
}

.rating-scale.border-danger {
    padding: 0.5rem;
    border-radius: 0.375rem;
}

/* Radio button styling with #00C2CB color */
.form-check-input {
    border: 2px solid #00C2CB;
    border-radius: 50%;
    width: 1.2em;
    height: 1.2em;
    transition: all 0.2s ease;
}

.form-check-input:checked {
    border-color: #00C2CB;
    background-color: #00C2CB;
}

.form-check-input:focus {
    border-color: #00C2CB;
    box-shadow: 0 0 0 0.2rem rgba(0, 194, 203, 0.25);
}

.form-check-input:hover {
    border-color: #00C2CB;
}
</style>
