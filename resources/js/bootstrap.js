import * as Popper from 'popper.js'

try {
    window.Popper = Popper
    window.$ = window.jQuery = require('jquery')
} catch (e) {}

import * as Bootstrap from 'bootstrap'

window.Bootstrap = Bootstrap