function select2(element, options) {
    element.select2(jQuery.extend({
            theme     : 'bootstrap',
            allowClear: false,
        },
        typeof(options) == 'object' ? options : {}
    ));
}