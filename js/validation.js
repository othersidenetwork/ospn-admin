(function($) {
    $(document).ready(function() {
        $('form#member_new_form').submit(function(e) {
            var form = this;

            $('[required="required"]', form).each(function (i, o) {
                if (o.tagName == 'INPUT' || o.tagName == 'SELECT') {
                    if ($(o).val() === '') {
                        $(o).addClass('invalid');
                    } else {
                        $(o).removeClass('invalid');
                    }
                } else {
                    // Unknown tag
                }
            });

            $('[type=email]', form).each(function(i, o) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (re.test($(o).val()) === false) {
                    $(o).addClass('invalid');
                } else {
                    $(o).removeClass('invalid');
                }
            });

            $('[pattern]', form).each(function(i, o) {
                var re = new RegExp('^' + $(o).attr('pattern') + '$');
                var value = $(o).val();
                if (value != '' && re.test(value) === false) {
                    $(o).addClass('invalid');
                } else {
                    $(o).removeClass('invalid');
                }
            });

            /*
            if ($('#podcast-host', form).val() == -1) {
                $('#podcast-host', form).addClass('invalid');
            } else {
                $('#podcast-host', form).removeClass('invalid');
            }
            */

            if ($('.invalid', form).size() != 0) {
                e.preventDefault();
            }
        });
    });

}(window.jQuery));
