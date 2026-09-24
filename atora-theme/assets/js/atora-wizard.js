(function($){
    'use strict';
    $(function(){
        var $root = $('[data-atora-wizard]');
        if (!$root.length) {
            return;
        }

        $root.find('.form-table select').on('change', function(){
            $root.addClass('is-dirty');
        });
    });
})(jQuery);
