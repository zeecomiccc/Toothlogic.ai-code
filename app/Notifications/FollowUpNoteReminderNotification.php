<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Appointment\Models\FollowUpNote;
use Carbon\Carbon;

class FollowUpNoteReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $note;
    public $receptionist;

    /**
     * Create a new notification instance.
     */
    public function __construct(FollowUpNote $note, $receptionist)
    {
        $this->note = $note;
        $this->receptionist = $receptionist;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Only database notifications, no email
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $doctor = $this->note->doctor;
        $patient = $this->note->patient;
        $clinic = $this->note->clinic;

        // Get the first initial of the patient for the avatar
        $patientInitial = strtoupper(substr($patient->first_name ?? '?', 0, 1));

        // Format the date as 'DD/MM/YYYY'
        $formattedDate = $this->note->date ? Carbon::parse($this->note->date)->format('d/m/Y') : 'N/A';

        // Current time when notification is generated
        $notificationTime = Carbon::now()->format('h:i A');

        return [
            // This is the EXACT structure your frontend expects
            'subject' => 'Follow-up Note Reminder',
            'data' => [
                // These fields are what your frontend reads for the table
                'notification_type' => 'follow_up_note',
                'notification_group' => 'follow_up_note',
                'id' => $this->note->id,
                'message' => 'Follow-up note reminder: ' . ($this->note->title ?? 'N/A'),
                
                // Patient information for the Patient column
                'user_id' => $patient->id ?? null,
                'user_name' => ($patient->first_name ?? 'N/A') . ' ' . ($patient->last_name ?? ''),
                'user_email' => $patient->email ?? 'N/A',
                'user_avatar' => $patientInitial,
                
                // Follow-up note specific data
                'follow_up_note_id' => $this->note->id,
                'follow_up_note_title' => $this->note->title ?? 'N/A',
                'follow_up_note_description' => $this->note->description ?? 'No description available',
                'doctor_name' => ($doctor->first_name ?? 'N/A') . ' ' . ($doctor->last_name ?? ''),
                'clinic_name' => $clinic->name ?? 'ToothLogic',
                'follow_up_date' => $formattedDate,
                
                // For the dropdown notifications
                'dropdown_title' => 'New Follow-up Note!',
                'dropdown_message' => 'Follow-up note received for **' . ($this->note->title ?? 'N/A') . '** for patient ' . ($patient->first_name ?? 'N/A') . ' ' . ($patient->last_name ?? ''),
                'dropdown_avatar_initial' => $patientInitial,
                'dropdown_date' => $formattedDate,
                'dropdown_time' => $notificationTime,
            ]
        ];
    }
}
