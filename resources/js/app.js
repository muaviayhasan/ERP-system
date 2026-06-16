import './bootstrap';            // axios + global jQuery (must run before Select2)

import 'select2';                 // extends jQuery
import Inputmask from 'inputmask';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { initErpForms } from './erp-forms';

// Globals used by Blade/erp-forms.
window.Inputmask = Inputmask;
window.Alpine = Alpine;
window.ErpForms = { init: initErpForms };

Alpine.plugin(collapse);

// Enhance forms once the DOM is ready, then start Alpine.
document.addEventListener('DOMContentLoaded', () => initErpForms(document));

Alpine.start();
