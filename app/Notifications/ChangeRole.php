<?php

namespace App\Notifications;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeRole extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Group $group, public string $role)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $roleName = ucfirst($this->role);

        return (new MailMessage)
            ->subject("Your Role Has Been Updated in {$this->group->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your role in the **{$this->group->name}** group has been updated to **{$roleName}**.")
            ->action('View Group', route('groups.show', $this->group))
            ->line('If you have any questions about your new role, please contact the group administrator.')
            ->salutation('Best regards, The '.config('app.name').' Team');
    }

    public function toArray(object $notifiable): array
    {

        return [
            'group_id' => $this->group->id,
            'group_name' => $this->group->name,
            'new_role' => $this->role,
            'message' => "Your role in {$this->group->name} has been updated to ".ucfirst($this->role),
            'action_url' => route('groups.show', $this->group),
            'action_text' => 'View Group',
        ];
    }
}
