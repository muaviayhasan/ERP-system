/**
 * Education ERP — global form enhancements.
 *
 * Auto-applies (on load and on demand via window.ErpForms.init(container)):
 *   - Select2 on every <select> (opt out with data-no-select2)
 *   - Inputmask on CNIC ([data-mask="cnic"]) and phone ([data-mask="phone"]) fields
 *   - a live maxlength counter for fields with data-counter
 *
 * Relies on window.jQuery (+ Select2) and window.Inputmask being available,
 * which app.js sets up before calling init.
 */
function initSelect2(root) {
    const $ = window.jQuery;
    if (!$ || !$.fn || !$.fn.select2) return;

    $(root).find('select:not([data-no-select2])').each(function () {
        const $el = $(this);
        if ($el.data('select2')) return;

        // Anchor the dropdown to an explicit container (drawer/modal) or the
        // field's own wrapper — never <body> (Select2 positioning bug).
        let $parent = $el.closest('[data-select2-parent]');
        if (!$parent.length) {
            $parent = $el.parent();
            if ($parent.css('position') === 'static') {
                $parent.css('position', 'relative');
            }
        }

        const config = {
            width: '100%',
            allowClear: this.hasAttribute('data-allow-clear'),
            dropdownParent: $parent,
            // Show the search box only for longer lists (avoids an empty bar on
            // short selects); searchable when it actually helps.
            minimumResultsForSearch: 8,
            // Drop the empty placeholder <option> from the results list so it
            // can never render as a blank highlighted row. Returning null tells
            // Select2 to hide that option entirely.
            templateResult: function (data) {
                // Hide only empty leaf options (the placeholder); keep optgroup
                // labels, which also have no id but carry children.
                if ((data.id === '' || data.id == null) && !data.children) {
                    return null;
                }
                return data.text;
            },
        };

        // Only set a placeholder when one is provided, so Select2 treats the
        // empty <option> as the placeholder (and keeps it out of the results).
        const placeholder = this.getAttribute('placeholder');
        if (placeholder) {
            config.placeholder = placeholder;
        }

        $el.select2(config);
    });
}

function initMasks(root) {
    if (!window.Inputmask) return;
    window.Inputmask({ mask: '99999-9999999-9', placeholder: '_', clearIncomplete: true })
        .mask(root.querySelectorAll('[data-mask="cnic"]'));
    window.Inputmask({ mask: '0399-9999999', placeholder: '_', clearIncomplete: true })
        .mask(root.querySelectorAll('[data-mask="phone"]'));
}

function initCounters(root) {
    root.querySelectorAll('[data-counter][maxlength]').forEach((field) => {
        const max = field.getAttribute('maxlength');
        const counter = document.querySelector('[data-counter-for="' + field.id + '"]');
        if (!counter) return;
        const update = () => { counter.textContent = field.value.length + '/' + max; };
        field.addEventListener('input', update);
        update();
    });
}

export function initErpForms(root) {
    root = root || document;
    initSelect2(root);
    initMasks(root);
    initCounters(root);
}
