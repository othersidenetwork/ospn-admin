(function($) {
    $(document).ready(function() {
        $('form.ospn-form').submit(function(e) {
            var form = this;

            $('.glass', form).show();

            var activeElement = $(document.activeElement);
            if ("bypass" !== activeElement.attr("x-data-validation")) {

                $('[required="required"]', form).each(function (i, o) {
                    if (o.tagName == 'INPUT' || o.tagName == 'SELECT' || o.tagName == 'TEXTAREA') {
                        if ($(o).val() === '') {
                            $(o).addClass('invalid');
                        } else {
                            $(o).removeClass('invalid');
                        }
                    } else {
                        // Unknown tag
                    }
                });

                $('[type=email]', form).each(function (i, o) {
                    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    if (re.test($(o).val()) === false) {
                        $(o).addClass('invalid');
                    } else {
                        $(o).removeClass('invalid');
                    }
                });

                $('[pattern]', form).each(function (i, o) {
                    var re = new RegExp('^' + $(o).attr('pattern') + '$');
                    var value = $(o).val();
                    if (value != '' && re.test(value) === false) {
                        $(o).addClass('invalid');
                    } else {
                        $(o).removeClass('invalid');
                    }
                });

                $('[type=url]', form).each(function (i, o) {
                    var re = /^https?:\/\/.+\.[^\.]+\/.*$/;
                    if (re.test($(o).val()) === false) {
                        $(o).addClass('invalid');
                    } else {
                        $(o).removeClass('invalid');
                    }
                });

                if ($('.invalid', form).size() != 0) {
                    $('.glass', form).hide();
                    e.preventDefault();
                }

            }

        });
    });

}(window.jQuery));
