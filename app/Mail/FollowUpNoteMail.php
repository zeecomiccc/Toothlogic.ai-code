<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Appointment\Models\FollowUpNote;

class FollowUpNoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $note;
    public $recipientType;

    public function __construct(FollowUpNote $note, $recipientType = 'patient')
    {
        $this->note = $note;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        return $this->subject(__('appointment.follow_up_note_email_subject'))
            ->view('mail.followup_note')
            ->with([
                'note' => $this->note,
                'recipientType' => $this->recipientType,
            ]);
    }
}
