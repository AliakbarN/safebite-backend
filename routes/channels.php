<?php

use App\Broadcasting\Chat;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat', Chat::class);
