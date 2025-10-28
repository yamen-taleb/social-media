<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFollower extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $follower)
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("You have a new follower! 🎉")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Great news! **{$this->follower->name}** has started following you on our platform.")
            ->line('Connect with them and see what they\'re sharing!')
            ->action('View Profile', route('profile', $this->follower->username))
            ->line('Thank you for being part of our community!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->follower->name} has just followed you",
            'user_url' => route('profile', $this->follower->username),
            'avatar' => $this->follower->avatar_path ? url('storage/' . $this->follower->avatar_path) : null,
            'action' => null,
        ];
    }
}
