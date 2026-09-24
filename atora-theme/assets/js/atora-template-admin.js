(function ($) {
    'use strict';

    var i18n = window.atoraTemplateAdminI18n || {};

    function initTemplateTabs() {
        var $browser = $('[data-atora-template-browser]');
        if (!$browser.length) {
            return;
        }

        var $tabs = $browser.find('[data-atora-template-tab]');
        var $panels = $browser.find('[data-atora-template-panel]');

        $tabs.on('click', function () {
            var tabId = $(this).data('atora-template-tab');
            if (!tabId) {
                return;
            }

            $tabs.removeClass('is-active').attr('aria-selected', 'false');
            $(this).addClass('is-active').attr('aria-selected', 'true');

            $panels.removeClass('is-active');
            $browser.find('[data-atora-template-panel="' + tabId + '"]').addClass('is-active');
        });
    }

    function initTemplateActivation() {
        var $form = $('#atora-template-browser-form');
        if (!$form.length) {
            return;
        }

        $('[data-atora-activate-template]').on('click', function () {
            var optionName = $(this).data('target-option');
            var templateId = $(this).data('template-id');

            if (!optionName || !templateId) {
                return;
            }

            var $hidden = $('#' + optionName);
            if (!$hidden.length) {
                return;
            }

            $hidden.val(templateId);
            $form.addClass('is-saving');
            $form.trigger('submit');
        });
    }

    function initActionConfirms() {
        $(document).on('submit', 'form[data-atora-confirm]', function (event) {
            var message = $(this).data('atora-confirm');
            if (message && !window.confirm(message)) {
                event.preventDefault();
            }
        });
    }

    function updateTemplateSummary($select) {
        var $wrapper = $select.closest('.atora-template-selector');
        if (!$wrapper.length) {
            return;
        }

        var $selected = $select.find('option:selected');
        if (!$selected.length) {
            return;
        }

        var label = $selected.data('template-label') || $selected.text() || '';
        var description = $selected.data('template-description') || '';
        var useCase = $selected.data('template-use-case') || '';
        var audience = $selected.data('template-audience') || '';
        var sections = $selected.data('template-sections') || '';
        var preview = $selected.data('template-preview') || '';

        $wrapper.find('[data-template-current-label]').text(label);
        $wrapper.find('[data-template-current-description]').text(description);

        if (useCase) {
            $wrapper.find('[data-template-current-usecase]').html('<strong>' + (i18n.idealFor || 'Ideal para:') + '</strong> ' + useCase);
        } else {
            $wrapper.find('[data-template-current-usecase]').empty();
        }

        if (audience) {
            $wrapper.find('[data-template-current-audience]').html('<strong>' + (i18n.audience || 'Audiencia:') + '</strong> ' + audience);
        } else {
            $wrapper.find('[data-template-current-audience]').empty();
        }

        if (sections) {
            $wrapper.find('[data-template-current-sections]').html('<strong>' + (i18n.sectionsIncluded || 'Secciones incluidas:') + '</strong> ' + sections);
        } else {
            $wrapper.find('[data-template-current-sections]').empty();
        }

        var $previewWrap = $wrapper.find('.atora-template-current-preview');
        if (!$previewWrap.length) {
            return;
        }

        if (preview) {
            var $img = $previewWrap.find('img[data-template-current-image]');
            if (!$img.length) {
                $img = $('<img>', { 'data-template-current-image': '1', alt: label });
                $previewWrap.empty().append($img);
            }
            $img.attr('src', preview).attr('alt', label);
        } else {
            $previewWrap.html('<span data-template-current-image-empty>' + (i18n.noPreview || 'Sin vista previa') + '</span>');
        }
    }

    function initMetaboxPreview() {
        $('select[data-atora-template-select]').each(function () {
            updateTemplateSummary($(this));
        });

        $(document).on('change', 'select[data-atora-template-select]', function () {
            updateTemplateSummary($(this));
        });
    }

    $(function () {
        initTemplateTabs();
        initTemplateActivation();
        initActionConfirms();
        initMetaboxPreview();
    });
})(jQuery);
