<?php

namespace App\Mail;

use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class GroupInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $group;
    public $invitation;
    public $acceptUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Group $group, GroupUser $invitation)
    {
        $this->group = $group;
        $this->invitation = $invitation;
        $this->acceptUrl = route('group.invitation.accept', [
            'token' => $invitation->token,
            'group' => $group->id
        ]);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.group-invitation',
            with: [
                'group' => $this->group,
                'acceptUrl' => $this->acceptUrl
            ],
        );
    }
}
