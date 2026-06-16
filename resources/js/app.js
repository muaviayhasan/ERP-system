import './bootstrap';            // axios + global jQuery (must run before plugins)

import 'jquery-mask-plugin';      // $.fn.mask
import 'select2';                 // $.fn.select2 (CSS is bundled via app.css)

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { initErpForms } from './erp-forms';

// Globals used by Blade.
window.Alpine = Alpine;
window.ErpForms = { init: initErpForms };

Alpine.plugin(collapse);

// Enhance forms once the DOM is ready, then start Alpine.
document.addEventListener('DOMContentLoaded', () => initErpForms(document));

Alpine.start();
