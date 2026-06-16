/**
 * Education ERP — global form enhancements.
 *
 * Auto-applies, on page load and on demand:
 *   - Select2 to every <select> (opt out with data-no-select2)
 *   - Inputmask to CNIC ([data-mask="cnic"]) and phone ([data-mask="phone"]) fields
 *   - a live maxlength counter for inputs/textareas that opt in with data-counter
 *
 * After injecting markup dynamically (e.g. opening a drawer/modal), call
 * window.ErpForms.init(container) to enhance the new fields.
 */
(function () {
    'use strict';

    function initSelect2(root) {
        if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.select2) return;
        window.jQuery(root).find('select:not([data-no-select2])').each(function () {
            var $el = window.jQuery(this);
            if ($el.data('select2')) return; // already initialised
            $el.select2({
                width: '100%',
                placeholder: this.getAttribute('placeholder') || '',
                allowClear: this.hasAttribute('data-allow-clear'),
                dropdownParent: $el.closest('[data-select2-parent]').length
                    ? $el.closest('[data-select2-parent]')
                    : window.jQuery(document.body),
            });
        });
    }

    function initMasks(root) {
        if (!window.Inputmask) return;
        // CNIC: 32301-0000000-0
        window.Inputmask({ mask: '99999-9999999-9', placeholder: '_', clearIncomplete: true })
            .mask(root.querySelectorAll('[data-mask="cnic"]'));
        // Pakistani mobile: 0300-0000000 — the leading "03" prefix is fixed/non-erasable.
        window.Inputmask({ mask: '0399-9999999', placeholder: '_', clearIncomplete: true })
            .mask(root.querySelectorAll('[data-mask="phone"]'));
    }

    function initCounters(root) {
        root.querySelectorAll('[data-counter][maxlength]').forEach(function (field) {
            var max = field.getAttribute('maxlength');
            var counter = document.querySelector('[data-counter-for="' + field.id + '"]');
            if (!counter) return;
            var update = function () { counter.textContent = field.value.length + '/' + max; };
            field.addEventListener('input', update);
            update();
        });
    }

    function init(root) {
        root = root || document;
        initSelect2(root);
        initMasks(root);
        initCounters(root);
    }

    document.addEventListener('DOMContentLoaded', function () { init(document); });

    window.ErpForms = { init: init };
})();
