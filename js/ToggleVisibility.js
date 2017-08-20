(function($) {
    $('body').on('change', '[data-type="toggle-visibility"]', function() {
        var input = $(this);
        var name  = input.attr('name');
        var type  = input.attr('type');
        var form  = $(this.form);
        
        form.find('[data-visibility-input="' + name + '"]').hide();
        
        if(type == 'checkbox') {
            if(this.checked) {
                var state = 'checked';
            } else {
                var state = 'unchecked';
            }
            form.find('[data-visibility-input="' + name + '"][data-visibility-state="' + state + '"]').show();
        } else if(type == 'radio') {
            if(input.is(':checked')) {
                form.find('[data-visibility-input="' + name + '"][data-visibility-value="' + this.value + '"]').show();
            }
        } else {
            form.find('[data-visibility-input="' + name + '"][data-visibility-value="' + this.value + '"]').show();
        }
    });
    $('[data-type="toggle-visibility"]').trigger('change');
})(jQuery);