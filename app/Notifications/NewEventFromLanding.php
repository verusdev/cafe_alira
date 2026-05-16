<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewEventFromLanding extends Notification
{
    use Queueable;

    public function __construct(public Event $event)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'client_name' => $this->event->client_name,
            'event_type' => $this->event->type_label,
            'event_date' => $this->event->event_date->format('d.m.Y'),
            'people_count' => $this->event->people_count,
            'url' => route('events.show', $this->event),
        ];
    }
}
