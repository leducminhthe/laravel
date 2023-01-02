window._ = require('lodash');

try {
    // require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

/*import Echo from 'laravel-echo'
window.Pusher = require('pusher-js');
var url = window.location.href;
if(url.split('/')[3] == 'social-network' || url.split('/')[3] == 'survey-react' || url.split('/')[5] == 'template-online') {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        // cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        // encrypted: false,
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        enabledTransports: ['ws'],
        disableStats: true,
    });
}*/
/************ socket.io*****************/

import Echo from 'laravel-echo'
window.io = require('socket.io-client');
var url = window.location.href;
if(url.split('/')[3] == 'social-network' || (url.split('/')[3] == 'survey-react' && (url.split('/')[4] == 'online' || url.split('/')[4] == 'edit-user-online')) || url.split('/')[5] == 'template-online') {
    if (_app_env_=='local') {
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6002'
        });
    }else {
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname,
            path: '/ws/socket.io',
            transports: ["polling", "websocket"]
        });
    }

}
