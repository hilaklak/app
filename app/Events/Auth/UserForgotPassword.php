<?php

namespace App\Events\Site\Auth;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserForgotPassword implements ShouldQueue
{
    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    public $user;

    public $type;

    public $token;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $type, $token)
    {
        $this->user = $user;
        $this->type = $type;
        $this->token = $token;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
