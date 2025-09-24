@extends('backend.layouts.app', ['isNoUISlider' => true])

@section('title')
{{ $module_title }}
@endsection



@push('after-styles')
<link rel="stylesheet" href="{{ mix('modules/service/style.css') }}">

<!-- Phosphor Icons (required for star rating filled/outline states) -->
<link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css">
<link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/fill/style.css">

<style>
  /* Patient Feedback Modal Styles */
  /* Note: Star rating styles moved to patient-feedback-form.blade.php to avoid conflicts */

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

  /* Select2 dropdown styling for modal */
  .select2-container--default .select2-selection--single {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    height: 38px;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
  }

  /* Modal specific styles */
  #add-patient-feedback-modal .modal-body {
    max-height: 70vh;
    overflow-y: auto;
  }

  #add-patient-feedback-modal .form-control:focus {
    border-color: #00C2CB;
    box-shadow: 0 0 0 0.2rem rgba(0, 194, 203, 0.25);
  }

  #add-patient-feedback-modal .btn-primary {
    background-color: #00C2CB;
    border-color: #00C2CB;
  }

  #add-patient-feedback-modal .btn-primary:hover {
    background-color: #0099a3;
    border-color: #0099a3;
  }


</style>
@endpush

@section('content')
<div class="table-content mb-5">
  <x-backend.section-header>
    <div>
      <x-backend.quick-action url="{{ route('backend.doctor.bulk_action_review') }}">
        <div class="">
          <select name="action_type" class="form-control select2 col-12" id="quick-action-type" style="width:100%">
            <option value="">{{ __('messages.no_action') }}</option>
            <option value="delete">{{ __('messages.delete') }}</option>
          </select>
        </div>
      </x-backend.quick-action>
    </div>
    <x-slot name="toolbar">
      <div class="d-flex gap-2">
        <div class="input-group flex-nowrap">
          <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="text" class="form-control dt-search" placeholder={{ (__('appointment.search')) }}
            aria-label="Search" aria-describedby="addon-wrapping">
        </div>
                 <button type="button" class="btn btn-primary" id="togglePatientFeedbackBtn" data-bs-toggle="offcanvas" data-bs-target="#patient-feedback-sidebar">
           <i class="fa-solid fa-plus me-2"></i>{{ __('feedback.add_patient_feedback') }}
         </button>
      </div>
    </x-slot>
  </x-backend.section-header>
  <table id="datatable" class="table table-responsive">
  </table>
</div>
<x-backend.advance-filter>
  <x-slot name="title">
    <h4>Advanced Filter</h4>
  </x-slot>
</x-backend.advance-filter>

<!-- Include Patient Feedback Form -->
@include('clinic::backend.doctor.patient-feedback-form')
@include('clinic::backend.doctor.review_show')

@endsection

@push('after-styles')
<!-- DataTables Core and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
<!-- DataTables Core and Extensions -->
<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>

<script type="text/javascript" defer>
  const range_flatpicker = document.querySelectorAll('.booking-date-range')
  Array.from(range_flatpicker, (elem) => {
    if (typeof flatpickr !== typeof undefined) {
      flatpickr(elem, {
        mode: "range",
        dateFormat: "d-m-Y",
      })
    }
  })
  const columns = [
    @unless(auth()->user()->hasRole('doctor'))
    {
      name: 'check',
      data: 'check',
      title: '<input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">',
      width: '0%',
      exportable: false,
      orderable: false,
      searchable: false,
    }, 
    @endunless
     
    {
      data: 'user_id',
      name: 'user_id',
      title: "{{ __('sidebar.patient') }}",
      orderable: true,
      searchable: true,
    },
    {
      data: 'doctor_id',
      name: 'doctor_id',
      title: "{{ __('appointment.lbl_doctor') }}",
      orderable: true,
      searchable: true,
      // width: '10%'
    },
    {
      data: 'service_id',
      name: 'service_id',
      title: "{{ __('appointment.lbl_service') }}",
      orderable: true,
      searchable: true,
      render: function(data, type, row) {
        // First priority: Check if service_id exists and has clinic_service data
        if (row.service_id && row.clinic_service && row.clinic_service.name) {
          return row.clinic_service.name;
        } 
        // Second priority: If no service, show treatments
        else if (row.treatments && row.treatments.trim() !== '') {
          return row.treatments;
        } 
        // Default: Show dash if neither exists
        else {
          return '-';
        }
      }
      // width: '10%'
    },
    {
      data: 'review_msg',
      name: 'review_msg',
      title: "{{ __('clinic.lbl_message') }}",
      width: '10%',
      className: 'description-column'

    },
    {
      data: 'experience_rating',
      name: 'rating',
      title: "{{ __('clinic.lbl_rating') }}",
      width: '5%'
    },
    {
      data: 'updated_at',
      name: 'updated_at',
      title: "{{ __('messages.date_time') }}",
      orderable: true,
      visible: true,
    }

  ]

  const actionColumn = [
    @if(auth()->user()->hasRole(['admin','demo_admin']))
    {
    data: 'action',
    name: 'action',
    orderable: false,
    searchable: false,
    title: "{{ __('employee.lbl_action') }}",
    width: '5%'
    }
    @endif
  ]



  let finalColumns = [
    ...columns,
    ...actionColumn
  ]


  document.addEventListener('DOMContentLoaded', (event) => {
    initDatatable({
      url: '{{ route("backend.doctor.review_data", ["doctor_id" => $doctor_id ]) }}',
      finalColumns,
      orderColumn: [ 
        @if(auth()->user()->hasRole('doctor'))
          [5, "desc"]
        @else
          [6, "desc"]
        @endif
      ],
      advanceFilter: () => {
        return {
          booking_date: $('#booking_date').val(),

        }
      }
    });

    function resetQuickAction() {
      const actionValue = $('#quick-action-type').val();
      if (actionValue != '') {
        $('#quick-action-apply').removeAttr('disabled');

        if (actionValue == 'change-status') {
          $('.quick-action-field').addClass('d-none');
          $('#change-status-action').removeClass('d-none');
        } else {
          $('.quick-action-field').addClass('d-none');
        }
      } else {
        $('#quick-action-apply').attr('disabled', true);
        $('.quick-action-field').addClass('d-none');
      }
    }

    $('#quick-action-type').change(function() {
      resetQuickAction()
    });
  })

  // Show Review (Offcanvas) like Lab
  function showReview(reviewId) {
    $.ajax({
      url: '{{ route("backend.doctor.review_show", ":id") }}'.replace(':id', reviewId),
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.status) {
          const r = response.data || {};
          const safe = (v) => v ?? '-';
          $('#show-name').text(safe(r.name) !== '-' ? r.name : safe(r.user?.full_name));
          $('#show-email').text(safe(r.email) !== '-' ? r.email : safe(r.user?.email));
          $('#show-phone').text(safe(r.phone) !== '-' ? r.phone : safe(r.user?.mobile));
          $('#show-age').text(safe(r.age));
          $('#show-doctor').text(safe(r.doctor?.full_name));
          // Show service first, if no service then show treatments
          if (r.clinic_service && r.clinic_service.name) {
            $('#show-service').text(r.clinic_service.name);
          } else if (r.treatments && r.treatments.trim() !== '') {
            $('#show-service').text(r.treatments);
          } else {
            $('#show-service').text('-');
          }
          $('#show-doctor-badge').text(safe(r.doctor?.full_name));
          $('#show-service-badge').text(safe(r.clinic_service?.name));
          if (r.user && r.user.profile_image) {
            $('#show-avatar').attr('src', r.user.profile_image);
          } else {
            $('#show-avatar').attr('src', '{{ default_user_avatar() }}');
          }
          $('#show-clinic-location').text(safe(r.clinic_location));
          $('#show-treatments').text(safe(r.treatments));
          let referral = safe(r.referral_source);
          if (referral === 'other' && r.referral_source_other) {
            referral = referral + ' (' + r.referral_source_other + ')';
          }
          $('#show-referral-source').text(referral);
          $('#show-overall-rating').text(safe(r.experience_rating));
          $('#show-experience-rating').text(safe(r.experience_rating));
          $('#show-dentist-explanation').text(safe(r.dentist_explanation));
          $('#show-pricing-satisfaction').text(safe(r.pricing_satisfaction));
          $('#show-staff-courtesy').text(safe(r.staff_courtesy));
          $('#show-treatment-satisfaction').text(safe(r.treatment_satisfaction));
          $('#show-title').text(safe(r.title));
          $('#show-review-msg').html(r.review_msg ? r.review_msg.replace(/\n/g, '<br>') : '-');

          // render star helpers
          const renderStars = (container, value) => {
            const v = Number(value) || 0;
            let html = '';
            const max = 10; // ratings are out of 10
            for (let i = 1; i <= max; i++) {
              if (i <= Math.floor(v)) {
                html += '<i class="ph-fill ph-star"></i>';
              } else {
                html += '<i class="ph ph-star"></i>';
              }
            }
            $(container).html(html);
          };

          renderStars('#show-overall-stars', r.experience_rating);
          renderStars('#show-experience-stars', r.experience_rating);
          renderStars('#show-pricing-stars', r.pricing_satisfaction);
          renderStars('#show-treatment-stars', r.treatment_satisfaction);

          const oc = new bootstrap.Offcanvas('#review-show-sidebar');
          oc.show();
        } else {
          alert('Error loading review');
        }
      },
      error: function(xhr) {
        alert('Error loading review: ' + xhr.responseText);
      }
    });
  }

  // Patient Feedback Offcanvas Functionality
  $(document).ready(function() {
    // Initialize Select2 for dropdowns
    $('#patientSelect, #doctorSelect, #serviceSelect').select2({
      dropdownParent: $('#patient-feedback-sidebar')
    });

    // Load patients from appointments
    function loadPatients() {
      $.ajax({
        url: '{{ route("backend.doctor.patients_from_appointments") }}',
        method: 'GET',
        success: function(response) {
          const patientSelect = $('#patientSelect');
          patientSelect.empty();
          patientSelect.append('<option value="">{{ __('feedback.select_patient_placeholder') }}</option>');
          
          if (response.data && response.data.length > 0) {
            response.data.forEach(function(patient) {
              patientSelect.append(`<option value="${patient.id}" 
                data-name="${patient.first_name || ''} ${patient.last_name || ''}" 
                data-email="${patient.email || ''}" 
                data-phone="${patient.mobile || patient.phone || ''}" 
                data-age="${patient.age || ''}">${patient.first_name || ''} ${patient.last_name || ''}</option>`);
            });
          }
        },
        error: function(xhr, status, error) {
          console.error('Error loading patients:', error);
        }
      });
    }

    // Load doctors from appointments for a specific patient
    function loadDoctors(patientId) {
      $.ajax({
        url: '{{ route("backend.doctor.doctors_from_appointments") }}',
        method: 'GET',
        data: { patient_id: patientId },
        success: function(response) {
          const doctorSelect = $('#doctorSelect');
          doctorSelect.empty();
          doctorSelect.append('<option value="">{{ __('feedback.select_doctor_placeholder') }}</option>');
          
          if (response.data && response.data.length > 0) {
            response.data.forEach(function(doctor) {
              doctorSelect.append(`<option value="${doctor.doctor_id}">${doctor.doctor_name}</option>`);
            });
          }
          
          // Enable doctor dropdown
          doctorSelect.prop('disabled', false);
        },
        error: function(xhr, status, error) {
          console.error('Error loading doctors:', error);
        }
      });
    }

    // Load services from appointments for a specific patient and doctor
    function loadServices(patientId, doctorId) {
      $.ajax({
        url: '{{ route("backend.doctor.services_from_appointments") }}',
        method: 'GET',
        data: { 
          patient_id: patientId,
          doctor_id: doctorId 
        },
        success: function(response) {
          const serviceSelect = $('#serviceSelect');
          serviceSelect.empty();
          serviceSelect.append('<option value="">{{ __('feedback.select_service_placeholder') }}</option>');
          
          if (response.data && response.data.length > 0) {
            response.data.forEach(function(service) {
              serviceSelect.append(`<option value="${service.id}">${service.name}</option>`);
            });
          }
          
          // Enable service dropdown
          serviceSelect.prop('disabled', false);
        },
        error: function(xhr, status, error) {
          console.error('Error loading services:', error);
        }
      });
    }

    // Auto-fill patient details when patient is selected
    $('#patientSelect').on('change', function() {
      const selectedOption = $(this).find('option:selected');
      const patientId = $(this).val();
      
      if (patientId) {
        // Auto-fill patient details
        const name = selectedOption.data('name');
        const email = selectedOption.data('email');
        const phone = selectedOption.data('phone');
        const age = selectedOption.data('age');
        
        $('#feedbackName').val(name);
        $('#feedbackEmail').val(email);
        $('#feedbackPhone').val(phone);
        $('#feedbackAge').val(age);
        
        // Enable doctor dropdown and load doctors for this patient
        $('#doctorSelect').prop('disabled', false);
        loadDoctors(patientId);
        
        // Reset and disable service dropdown
        $('#serviceSelect').prop('disabled', true).empty().append('<option value="">{{ __('feedback.select_service_placeholder') }}</option>');
      } else {
        // Clear patient details and disable doctor/service dropdowns
        $('#feedbackName, #feedbackEmail, #feedbackPhone, #feedbackAge').val('');
        $('#doctorSelect, #serviceSelect').prop('disabled', true).empty();
        $('#doctorSelect').append('<option value="">{{ __('feedback.select_doctor_placeholder') }}</option>');
        $('#serviceSelect').append('<option value="">{{ __('feedback.select_service_placeholder') }}</option>');
      }
    });

    // Load services when doctor is selected
    $('#doctorSelect').on('change', function() {
      const patientId = $('#patientSelect').val();
      const doctorId = $(this).val();
      
      if (patientId && doctorId) {
        loadServices(patientId, doctorId);
      } else {
        // Reset and disable service dropdown
        $('#serviceSelect').prop('disabled', true).empty().append('<option value="">{{ __('feedback.select_service_placeholder') }}</option>');
      }
    });

    // Handle offcanvas show event
    $('#patient-feedback-sidebar').on('show.bs.offcanvas', function() {
      loadPatients();
      // Initialize empty and disabled doctor and service dropdowns
      $('#doctorSelect').prop('disabled', true).empty().append('<option value="">{{ __('feedback.select_doctor_placeholder') }}</option>');
      $('#serviceSelect').prop('disabled', true).empty().append('<option value="">{{ __('feedback.select_service_placeholder') }}</option>');
      resetFeedbackForm();
    });

    // Handle offcanvas hidden event
    $('#patient-feedback-sidebar').on('hidden.bs.offcanvas', function() {
      // Reset form when offcanvas is closed
      resetFeedbackForm();
    });

    // Handle referral source "Other" field
    $('input[name="referral_source"]').on('change', function() {
      if ($('#modalOther').is(':checked')) {
        $('#modalReferralOtherBox').show();
        $('#modalReferralOtherInput').prop('required', true);
      } else {
        $('#modalReferralOtherBox').hide();
        $('#modalReferralOtherInput').prop('required', false).val('');
      }
    });

    // Submit button click handler
    $('#submitFeedbackBtn').on('click', function() {
      $('#patientFeedbackForm').submit();
    });

    // Star rating functionality for sidebar
    let modalExperienceRating = 0;
    let modalPricingRating = 0;
    let modalTreatmentRating = 0;

    const modalExperienceStars = document.querySelectorAll('.modal-experience-star');
    const modalPricingStars = document.querySelectorAll('.modal-pricing-star');
    const modalTreatmentStars = document.querySelectorAll('.modal-treatment-star');

    function highlightModalStars(starElements, rating) {
      starElements.forEach(function(star) {
        const starValue = parseInt(star.getAttribute('data-value'));
        if (starValue <= rating) {
          star.classList.add('selected');
        } else {
          star.classList.remove('selected');
        }
      });
    }

    // Experience star rating
    $('.modal-experience-star').on('click', function() {
      modalExperienceRating = parseInt($(this).attr('data-value'));
      highlightModalStars(modalExperienceStars, modalExperienceRating);
      $('#modal-experience-rating-error').addClass('d-none');
      // Set the experience rating value
      $('#experience_rating_value').val(modalExperienceRating);
    });

    // Pricing star rating
    $('.modal-pricing-star').on('click', function() {
      modalPricingRating = parseInt($(this).attr('data-value'));
      highlightModalStars(modalPricingStars, modalPricingRating);
      // Set the pricing rating value
      $('#pricing_rating_value').val(modalPricingRating);
    });

    // Treatment star rating
    $('.modal-treatment-star').on('click', function() {
      modalTreatmentRating = parseInt($(this).attr('data-value'));
      highlightModalStars(modalTreatmentStars, modalTreatmentRating);
      // Set the treatment rating value
      $('#treatment_rating_value').val(modalTreatmentRating);
    });

    // Hover effects for sidebar stars
    $('.modal-experience-star').hover(
      function() {
        const hoverValue = parseInt($(this).attr('data-value'));
        // Show hover effect for all stars up to the hovered one
        modalExperienceStars.forEach(function(star, index) {
          if (index < hoverValue) {
            star.classList.add('hover');
          }
        });
      },
      function() {
        // Remove hover effect and show actual selected rating
        modalExperienceStars.forEach(function(star) {
          star.classList.remove('hover');
        });
        highlightModalStars(modalExperienceStars, modalExperienceRating);
      }
    );

    $('.modal-pricing-star').hover(
      function() {
        const hoverValue = parseInt($(this).attr('data-value'));
        // Show hover effect for all stars up to the hovered one
        modalPricingStars.forEach(function(star, index) {
          if (index < hoverValue) {
            star.classList.add('hover');
          }
        });
      },
      function() {
        // Remove hover effect and show actual selected rating
        modalPricingStars.forEach(function(star) {
          star.classList.remove('hover');
        });
        highlightModalStars(modalPricingStars, modalPricingRating);
      }
    );

    $('.modal-treatment-star').hover(
      function() {
        const hoverValue = parseInt($(this).attr('data-value'));
        // Show hover effect for all stars up to the hovered one
        modalTreatmentStars.forEach(function(star, index) {
          if (index < hoverValue) {
            star.classList.add('hover');
          }
        });
      },
      function() {
        // Remove hover effect and show actual selected rating
        modalTreatmentStars.forEach(function(star) {
          star.classList.remove('hover');
        });
        highlightModalStars(modalTreatmentStars, modalTreatmentRating);
      }
    );

    // Form submission
    $('#patientFeedbackForm').on('submit', function(e) {
      e.preventDefault();
      
      // Reset error messages
      $('.text-danger').addClass('d-none');
      $('.is-invalid').removeClass('is-invalid');
      
      // Validate required fields
      let isValid = true;
      let errorMessage = '';
      
      // Check patient selection
      if (!$('#patientSelect').val()) {
        $('#patient-error').removeClass('d-none');
        $('#patientSelect').addClass('is-invalid');
        isValid = false;
        errorMessage += '{{ __("feedback.patient_required") }}\n';
      }
      
      // Check doctor selection
      if (!$('#doctorSelect').val()) {
        $('#doctor-error').removeClass('d-none');
        $('#doctorSelect').addClass('is-invalid');
        isValid = false;
        errorMessage += '{{ __("feedback.doctor_required") }}\n';
      }
      
      // Check service selection
      if (!$('#serviceSelect').val()) {
        $('#service-error').removeClass('d-none');
        $('#serviceSelect').addClass('is-invalid');
        isValid = false;
        errorMessage += '{{ __("feedback.service_required") }}\n';
      }
      
      // Check experience rating
      if (!modalExperienceRating || modalExperienceRating === 0) {
        $('#modal-experience-rating-error').removeClass('d-none');
        isValid = false;
        errorMessage += '{{ __("feedback.please_select_rating") }}\n';
      }
      
      // Check referral source
      if (!$('input[name="referral_source"]:checked').val()) {
        $('input[name="referral_source"]').closest('.form-group').addClass('is-invalid');
        isValid = false;
        errorMessage += '{{ __("feedback.referral_source_required") }}\n';
      }
      
      // Check referral source other if "other" is selected
      if ($('#modalOther').is(':checked') && !$('#modalReferralOtherInput').val().trim()) {
        $('#modalReferralOtherInput').addClass('is-invalid');
        isValid = false;
        errorMessage += '{{ __("feedback.referral_source_other_required") }}\n';
      }
      
      // Check dentist explanation
      if (!$('input[name="dentist_explanation"]:checked').val()) {
        $('input[name="dentist_explanation"]').closest('.form-group').addClass('is-invalid');
        isValid = false;
        errorMessage += '{{ __("feedback.dentist_explanation_required") }}\n';
      }
      
      // Check pricing satisfaction
      if (!modalPricingRating || modalPricingRating === 0) {
        $('.modal-pricing-star').closest('.form-group').addClass('is-invalid');
        isValid = false;
        errorMessage += '{{ __("feedback.pricing_satisfaction_required") }}\n';
      }
      
      // Check staff courtesy
      if (!$('input[name="staff_courtesy"]:checked').val()) {
        $('input[name="staff_courtesy"]').closest('.form-group').addClass('is-invalid');
        isValid = false;
        errorMessage += '{{ __("feedback.staff_courtesy_required") }}\n';
      }
      
      // Check treatment satisfaction
      if (!modalTreatmentRating || modalTreatmentRating === 0) {
        $('.modal-treatment-star').closest('.form-group').addClass('is-invalid');
        isValid = false;
        errorMessage += '{{ __("feedback.treatment_satisfaction_required") }}\n';
      }
      
      // Get form data
      const formData = {
        doctor_id: $('#doctorSelect').val(),
        user_id: $('#patientSelect').val(),
        service_id: $('#serviceSelect').val(),
        rating: modalExperienceRating,
        name: $('#feedbackName').val(),
        email: $('#feedbackEmail').val(),
        phone: $('#feedbackPhone').val(),
        age: $('#feedbackAge').val(),
        treatments: $('#feedbackTreatments').val(),
        clinic_location: $('#feedbackLocation').val(),
        referral_source: $('input[name="referral_source"]:checked').val(),
        referral_source_other: $('#modalReferralOtherInput').val(),
        experience_rating: $('#experience_rating_value').val(),
        dentist_explanation: $('input[name="dentist_explanation"]:checked').val(),
        pricing_satisfaction: $('#pricing_rating_value').val(),
        staff_courtesy: $('input[name="staff_courtesy"]:checked').val(),
        treatment_satisfaction: $('#treatment_rating_value').val(),
        review_msg: $('#feedbackTextarea').val(),
        _token: '{{ csrf_token() }}'
      };
      
             // Submit form via AJAX
       $.ajax({
         url: '{{ route("backend.doctor.add_patient_feedback") }}',
         method: 'POST',
         data: formData,
         success: function(response) {
             window.successSnackbar(response.message);
             // Close the offcanvas using Bootstrap method
             const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('patient-feedback-sidebar'));
             if (offcanvas) {
                 offcanvas.hide();
             }
             resetFeedbackForm();
             // Reload the datatable to show the new feedback
             if (typeof window.renderedDataTable !== 'undefined' && window.renderedDataTable) {
                 window.renderedDataTable.ajax.reload();
             }
         },
         error: function(xhr, status, error) {
           let errorMessage = '{{ __("messages.error") }}';
           
           if (xhr.responseJSON && xhr.responseJSON.message) {
             errorMessage = xhr.responseJSON.message;
           } else if (xhr.responseJSON && xhr.responseJSON.errors) {
             // Handle validation errors
             const errors = xhr.responseJSON.errors;
             Object.keys(errors).forEach(function(field) {
               const fieldElement = $(`#${field}`);
               if (fieldElement.length) {
                 fieldElement.addClass('is-invalid');
                 // Show validation error message
                 const errorDiv = fieldElement.siblings('.text-danger');
                 if (errorDiv.length) {
                   errorDiv.removeClass('d-none').text(errors[field][0]);
                 }
               }
             });
             return;
           }
         }
       });
    });

    // Reset form function
    function resetFeedbackForm() {
      $('#patientFeedbackForm')[0].reset();
      
      // Reset star ratings
      modalExperienceRating = 0;
      modalPricingRating = 0;
      modalTreatmentRating = 0;
      
      // Remove all star classes
      $('.modal-experience-star, .modal-pricing-star, .modal-treatment-star').removeClass('selected hover');
      
      // Reset all error messages
      $('.text-danger').addClass('d-none');
      $('.is-invalid').removeClass('is-invalid');
      $('#modal-experience-rating-error').addClass('d-none');
      
      // Hide referral source other field
      $('#modalReferralOtherBox').hide();
      $('#modalReferralOtherInput').prop('required', false).val('');
      
      // Reset dropdowns to initial state
      $('#patientSelect').val('');
      $('#doctorSelect, #serviceSelect').prop('disabled', true).empty();
      $('#doctorSelect').append('<option value="">{{ __('feedback.select_doctor_placeholder') }}</option>');
      $('#serviceSelect').append('<option value="">{{ __('feedback.select_service_placeholder') }}</option>');
      
      // Clear all input fields
      $('#feedbackName, #feedbackEmail, #feedbackPhone, #feedbackAge, #feedbackTreatments, #feedbackLocation, #feedbackTextarea').val('');
    }
  });
</script>
@endpush