function select2(element, options) {
    var select2_attributes = {};
    var regexp             = new RegExp('select2\-(.*)', 'g');
    var regexp_result;
    jQuery.each(element.attributes, function(i, attribute) {
        regexp_result = regexp.exec(attribute.name);
        if (regexp_result != null) {
            select2_attributes[regexp_result[1].toCamelCase()] = attribute.value;
        }
    });

    $(element).select2(
        jQuery.extend({
                theme     : 'bootstrap',
                allowClear: false,
            },
            typeof(options) == 'object' ? options : {},
            select2_attributes),
    );
}