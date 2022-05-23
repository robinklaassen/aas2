import lodash from "lodash";
import $ from "jquery";
import axios from "axios";
import moment from "moment";

(window as any)._ = lodash;
(window as any).moment = moment;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    // popper.js required for bootstrap 4
    // window.Popper = require('popper.js').default;

    (window as any).$ = (window as any).jQuery = $;

    require('bootstrap');
    require('datatables.net-bs');
    require('datatables.net-responsive-bs');
} catch (e) { }

const Modernizr = (window as any).Modernizr = require('Modernizr');
const webshim = (window as any).webshim = require('webshim');


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

(window as any).axios = axios;

(window as any).axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (!Modernizr.inputtypes.date) {
    webshim.polyfill('forms-ext');
}

const select2 = (window as any).select2 = require('select2');
const L = (window as any).L = require('leaflet');

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
