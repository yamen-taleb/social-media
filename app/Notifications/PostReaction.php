<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostReaction extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $reactionType,
        public Model $reactable,
        public Authenticatable $reactor
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $reactionEmoji = $this->getReactionEmoji($this->reactionType);
        $isComment = $this->reactable instanceof \App\Models\Comment;

        $message = (new MailMessage)
            ->subject($this->getSubject($isComment, $reactionEmoji))
            ->greeting('Hello '.$notifiable->name.'!')
            ->line($this->getMessage($isComment, $reactionEmoji));

        $this->addAction($message, $isComment);
        return $message->line('Thank you for being part of our community!');
    }

    /**
     * Get the emoji for the reaction type
     */
    protected function getReactionEmoji(string $reactionType): string
    {
        return match ($reactionType) {
            'like' => '👍',
            'love' => '❤️',
            'haha' => '😄',
            'wow' => '😲',
            'sad' => '😢',
            'angry' => '😠',
            default => '👍',
        };
    }

    protected function getSubject(bool $isComment, string $emoji): string
    {
        $target = $isComment ? 'comment' : 'post';
        return $this->reactor->name." reacted to your {$target} ".$emoji;
    }

    protected function getMessage(bool $isComment, string $emoji): string
    {
        $target = $isComment ? 'comment' : 'post';
        return $this->reactor->name." reacted with {$emoji} to your {$target}.";
    }

    protected function addAction(MailMessage $message, bool $isComment): void
    {
        if ($isComment) {
            $message->action('View Comment', route('posts.show', $this->reactable->post_id));
        } else {
            $message->action('View Post', route('posts.show', $this->reactable));
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->getMessage($this->reactable instanceof \App\Models\Comment, $this->getReactionEmoji($this->reactionType)),
            'url' => route('posts.show', $this->reactable instanceof \App\Models\Comment ? $this->reactable->post_id : $this->reactable),
            'avatar' => $this->reactor->avatar_path ? url('storage/' . $this->reactor->avatar_path) : null,
            'action' => null,
        ];
    }
}
