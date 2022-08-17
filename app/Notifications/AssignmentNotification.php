<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignmentNotification extends Notification
{
    use Queueable;
    private $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $firstName = str($notifiable->user->name)->words(1, '');
        return (new MailMessage)
            ->from('no-reply@eling.smknegeri3malang.sch.id', 'E-Learning SMK Negeri 3 Malang')
            ->subject('Tugas Baru')
            ->greeting('Hai ' . $firstName)
            ->line($this->data['message'])
            ->line('Deadline: ' . $this->data['details']['assignment_deadline'])
            ->action('Buka E-Learning', config('app.spa_url') . '/')
            ->line('Segera dikerjakan ya tugasnya. SELAMAT BELAJAR! 😉');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->data['message'],
            'details' => $this->data['details']
        ];
    }
}
