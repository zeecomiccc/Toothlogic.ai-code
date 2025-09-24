<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Helpers\UrlEncryption;
use Modules\Clinic\Models\DoctorRating;

class PublicPatientFeedbackController extends Controller
{
    /**
     * Show the patient feedback form
     */
    public function show(Request $request)
    {
        try {
            // Get the specific doctor from users table with user_type = 'doctor' and ID 19
            $doctor = User::where('id', 19)->where('user_type', 'doctor')->first();

            if (!$doctor) {
                Log::error('Doctor with ID 19 and user_type doctor not found');
                return back()->with('error', 'Doctor not found. Please contact administrator.');
            }

            // Get customer data if short hash is provided
            $customer = null;
            if ($request->has('id')) {
                $hash = $request->id;
                $customerId = UrlEncryption::decodeShortHash($hash);
                
                if ($customerId) {
                    $customer = User::where('id', $customerId)
                        ->where('user_type', 'user')
                        ->first();
                }
            }

            return view('public.patient-feedback', compact('doctor', 'customer'));
        } catch (\Exception $e) {
            Log::error('Error loading patient feedback form', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error loading form. Please try again.');
        }
    }

    /**
     * Store the patient feedback
     */
    public function store(Request $request)
    {
        try {
            // Get the specific doctor from users table with user_type = 'doctor' and ID 19
            $doctor = User::where('id', 19)->where('user_type', 'doctor')->first();

            if (!$doctor) {
                Log::error('Doctor with ID 19 and user_type doctor not found');
                return back()->with('error', 'Doctor not found. Please contact administrator.');
            }

            // Check if patient exists by email
            $patient = User::where('email', $request->email)->first();

            if (!$patient) {
                $patient = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->phone,
                    'password' => Hash::make('12345678'), // Set fixed password for patients
                    'user_type' => 'user', // Set user_type as 'user' for patient
                    'status' => 1,
                    'email_verified_at' => now(), // Auto-verify for public submissions
                ]);

                // Assign patient role if roles are available
                if (method_exists($patient, 'assignRole')) {
                    try {
                        $patient->assignRole('user');
                    } catch (\Exception $e) {
                        Log::warning('Could not assign user role', ['error' => $e->getMessage()]);
                    }
                }
            } else {
            }

            // Create doctor review with the patient's user_id
            $ratingData = [
                'doctor_id' => $doctor->id, // This is the doctor's user ID from users table
                'user_id' => $patient->id, // Use the patient's user_id
                'rating' => (float) $request->rating,
                'review_msg' => $request->review_msg,
                'title' => 'Patient Feedback',
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'age' => (int) $request->age,
                'treatments' => $request->treatments,
                'clinic_location' => $request->clinic_location,
                'referral_source' => $request->referral_source,
                'referral_source_other' => $request->referral_source_other,
                'experience_rating' => $request->experience_rating ? (float) $request->experience_rating : null,
                'dentist_explanation' => $request->dentist_explanation ? (float) $request->dentist_explanation : null,
                'pricing_satisfaction' => $request->pricing_satisfaction ? (float) $request->pricing_satisfaction : null,
                'staff_courtesy' => $request->staff_courtesy ? (float) $request->staff_courtesy : null,
                'treatment_satisfaction' => $request->treatment_satisfaction ? (float) $request->treatment_satisfaction : null,
            ];

            // Remove null values to avoid database issues
            $ratingData = array_filter($ratingData, function($value) {
                return $value !== null && $value !== '';
            });

            // Create the doctor review
            $review = DoctorRating::create($ratingData);
       
            return back()->with('success', 'Thank you for your feedback! Your review has been submitted successfully.');
        } catch (\Exception $e) {
            // Provide more specific error messages
            $errorMessage = 'An error occurred while submitting your feedback. Please try again.';
            
            if (str_contains($e->getMessage(), 'SQLSTATE')) {
                $errorMessage = 'Database error occurred. Please check your input and try again.';
            } elseif (str_contains($e->getMessage(), 'fillable')) {
                $errorMessage = 'Invalid data provided. Please check your input and try again.';
            } elseif (str_contains($e->getMessage(), 'Column not found')) {
                $errorMessage = 'Database structure error. Please contact administrator.';
            }
            
            return back()->with('error', $errorMessage)->withInput();
        }
    }


}
