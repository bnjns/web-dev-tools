function datetimepicker(input, options) {
    var Defaults = {
        allowInputToggle: true,
        format          : 'YYYY-MM-DD',
        icons           : {
            time    : 'fa fa-clock-o',
            date    : 'fa fa-calendar',
            up      : 'fa fa-chevron-up',
            down    : 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next    : 'fa fa-chevron-right',
            today   : 'fa fa-bullseye',
            clear   : 'fa fa-trash',
            close   : 'fa fa-remove',
        },
        locale          : moment().locale('en-gb'),
        showTodayButton : true,
    };

    var attributes = {};
    var regex      = /^data\-date\-(.*)/;
    var regex_result;
    jQuery.each(input.attributes, function(i, attribute) {
        regex_result = regex.exec(attribute.name);
        if (regex_result != null) {
            attributes[regex_result[1].toCamelCase()] = attribute.value;
        }
    });

    input      = $(input);
    var parent = input.parent();
    options    = jQuery.extend(
        {},
        Defaults,
        typeof(options) == 'object' ? options : {},
        attributes,
    );

    if (parent.hasClass('input-group')) {
        return parent.addClass('date').datetimepicker(options);
    } else {
        return input.datetimepicker(options);
    }
}