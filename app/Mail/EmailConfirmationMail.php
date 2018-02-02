<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class EmailConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $updated=false)
    {
        $this->user = $user;
        $this->updated = $updated;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->updated) {
            return $this->markdown('email.confirmationUpdated')
                        ->with([ 'user' => $this->user ]);

        }

        return $this->markdown('email.confirmation')
                    ->with([ 'user' => $this->user ]);
    }
}
