<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
 */

Broadcast::channel('App.User.{hash_id}', function ($user, $hash_id) {
    return $user->hash_id === $hash_id;
});

Broadcast::channer("App.Conversation.{id}", function ($conversation, $id) {
    return $conversation->id === $id;
});

Broadcast::channer("App.GetMatches", function () {
    return true;
});

Broadcast::channel('private-notification.users.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::bind('id', function ($value) {
    dd($value);
});
