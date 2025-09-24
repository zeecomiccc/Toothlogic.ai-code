<?php

namespace App\Console\Commands;

use App\Mail\UpcomingAppointmentMail;
use App\Mail\FollowUpNoteReminderMail;
use App\Notifications\FollowUpNoteReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Modules\Appointment\Models\Appointment;
use Modules\Appointment\Models\FollowUpNote;

class SendUpcomingAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-mail-upcoming-appointment-reminders';
    protected $description = 'Send email reminders one day before upcoming appointments and follow-up notes';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Send appointment reminders
        $this->sendAppointmentReminders($tomorrow);
        
        // Send follow-up note reminders
        $this->sendFollowUpNoteReminders($tomorrow);
    }

    private function sendAppointmentReminders($tomorrow)
    {
        $appointments = Appointment::with(['user', 'doctor', 'cliniccenter', 'clinicservice'])
            ->whereDate('appointment_date', $tomorrow)
            ->where('reminder_sent', false) // only send for not-yet-sent
            ->get();

        foreach ($appointments as $appointment) {
            if ($appointment->user && $appointment->user->email) {
                Mail::to($appointment->user->email)->queue(new UpcomingAppointmentMail($appointment, 'user'));
            }

            if ($appointment->doctor && $appointment->doctor->email) {
                Mail::to($appointment->doctor->email)->queue(new UpcomingAppointmentMail($appointment, 'doctor'));
            }

            // Mark as sent
            $appointment->reminder_sent = true;
            $appointment->save();
        }

        $this->info("Sent appointment reminders for {$tomorrow}. Total: " . $appointments->count());
    }

    private function sendFollowUpNoteReminders($tomorrow)
    {
        $followUpNotes = FollowUpNote::with(['doctor', 'patient', 'clinic', 'receptionists'])
            ->whereDate('date', $tomorrow)
            ->where('reminder_sent', false) // only send for not-yet-sent
            ->get();

        $totalRemindersSent = 0;

        foreach ($followUpNotes as $note) {
            // Get receptionists for this clinic
            $receptionists = $note->receptionists;
            
            if ($receptionists->isNotEmpty()) {
                foreach ($receptionists as $receptionist) {
                    if ($receptionist->email) {
                        // Send email
                        Mail::to($receptionist->email)->queue(new FollowUpNoteReminderMail($note, $receptionist));
                        
                        // Send notification (for in-app notifications)
                        $receptionist->notify(new FollowUpNoteReminderNotification($note, $receptionist));
                        
                        $totalRemindersSent++;
                    }
                }
            }

            // Mark as sent
            $note->reminder_sent = true;
            $note->save();
        }

        $this->info("Sent follow-up note reminders for {$tomorrow}. Total reminders sent: {$totalRemindersSent}");
    }
}
