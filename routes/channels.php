<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('menu-pestania', function () {
    return true;
});

Broadcast::channel('new-notificaciones', function () {
    return true;
});

Broadcast::channel('update-table', function () {
    return true;
});