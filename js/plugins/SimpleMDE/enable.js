(function($) {
    $.fn.SimpleMDE = function() {
        this.each(function() {
            var simplemde = new SimpleMDE({
                element: this,
                status : false,
            });
        });
    };
})(jQuery);