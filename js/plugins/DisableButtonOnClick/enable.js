(function($) {
    $.fn.DisableButtonOnClick = function() {
        var btn  = $(this).filter('.btn');
        var text = btn.attr('data-disable-text') || null;
        var icon = btn.attr('data-disable-icon') || 'spinner';
        var html = '<span class="fa fa-' + icon + ' fa-spin"></span>';
        if (text) {
            html += '<span>' + text + '</span>';
        }

        setTimeout(function() {
            btn.attr('disabled', true)
               .off('click')
               .html(html);
        }, 0);
    }
})(jQuery);