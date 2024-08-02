<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{id}', fn (User $user, int|string $id) => $user->id == $id);
