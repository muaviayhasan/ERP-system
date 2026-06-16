import './bootstrap';            // axios + global jQuery (must run before plugins)

import 'jquery-mask-plugin';      // $.fn.mask
import 'select2';                 // $.fn.select2 (CSS is bundled via app.css)

import { initErpForms } from './erp-forms';

// Alpine is provided by Livewire (loaded via @livewireScripts) — we do NOT
// import/start our own Alpine here to avoid a double Alpine instance. Livewire
// 4 bundles Alpine + the collapse plugin used by the sidebar.
window.ErpForms = { init: initErpForms };

// Enhance Select2 / input masks once the DOM is ready.
document.addEventListener('DOMContentLoaded', () => initErpForms(document));
