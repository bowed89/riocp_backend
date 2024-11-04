<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Notificaciones implements ShouldBroadcast
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new Channel('new-notificaciones');
    }

    public function broadcastAs()
    {
        return 'App\\Events\\Notificaciones';
    }

}
