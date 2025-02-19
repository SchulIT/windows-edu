require('../css/app.scss');

import { Tooltip } from "bootstrap";

require('../../vendor/schulit/common-bundle/Resources/assets/js/polyfill');
require('../../vendor/schulit/common-bundle/Resources/assets/js/menu');

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[title]').forEach(function(el) {
        new Tooltip(el, {
            placement: 'bottom'
        });
    });
});