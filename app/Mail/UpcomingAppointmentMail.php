<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpcomingAppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $role;

    public function __construct($appointment, $role)
    {
        $this->appointment = $appointment;
        $this->role = $role;
    }

    public function build()
    {
        $name = $this->role === 'doctor' ? $this->appointment->doctor->first_name : $this->appointment->user->first_name;

        return $this->subject('Reminder: Upcoming Appointment Tomorrow')
            ->view('appointment::backend.appointment.upcoming_appointment_reminder')
            ->with([
                'name' => $name,
                'appointment' => $this->appointment,
            ]);
    }
}
