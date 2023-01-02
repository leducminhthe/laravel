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

//Broadcast::channel('App.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});
use App\Models\Profile;

Broadcast::channel('chat.{id}', function ($user, $id) {
    return $user;
});
Broadcast::channel('room.{id}', function ($user) {//return 3443;
    return $user;
});
Broadcast::channel('login', function ($user) {
    $user->new_messages = 0;
    return $user;
});
Broadcast::channel('social', function ($user) {
    $user->id != profile()->user_id;
    return $user;
});