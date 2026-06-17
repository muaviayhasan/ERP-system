import './bootstrap';            // axios + global jQuery (must run before plugins)

import 'jquery-mask-plugin';      // $.fn.mask

// Select2 ships a CommonJS UMD that *exports a factory function* rather than
// self-attaching to jQuery. A bare `import 'select2'` never runs that factory,
// so $.fn.select2 would stay undefined under Vite (selects fall back to native).
// Import the factory and invoke it against our global jQuery to register it.
import select2 from 'select2';   // CSS is bundled via app.css
select2(window, window.jQuery);

import { initErpForms } from './erp-forms';

// Alpine is provided by Livewire (loaded via @livewireScripts) — we do NOT
// import/start our own Alpine here to avoid a double Alpine instance. Livewire
// 4 bundles Alpine + the collapse plugin used by the sidebar.
window.ErpForms = { init: initErpForms };

// Enhance Select2 / input masks once the DOM is ready.
document.addEventListener('DOMContentLoaded', () => initErpForms(document));

// Re-run after Livewire SPA navigation (wire:navigate) so enhancements survive
// page swaps that don't fire DOMContentLoaded.
document.addEventListener('livewire:navigated', () => initErpForms(document));
