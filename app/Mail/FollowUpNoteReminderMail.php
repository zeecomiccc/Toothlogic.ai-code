<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Appointment\Models\FollowUpNote;

class FollowUpNoteReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $note;
    public $receptionist;

    public function __construct(FollowUpNote $note, $receptionist)
    {
        $this->note = $note;
        $this->receptionist = $receptionist;
    }

    public function build()
    {
        return $this->subject('Follow-up Note Reminder - Action Required')
            ->view('mail.followup_note_reminder')
            ->with([
                'note' => $this->note,
                'receptionist' => $this->receptionist,
            ]);
    }
}
