(function ($) {
    $(document).ready(function () {
//
        $('#user_login').attr('placeholder', 'Username');
        $('#user_pass').attr('placeholder', 'Password');
        ///
        var $textBoxes = $('input[type="text"],input[type="password"]');
        $textBoxes.focus(function () {
            $(this).parent().addClass('is-focus');
        }).blur(function () {
            $(this).parent().removeClass('is-focus');
        });
        ///
        $textBoxes.on('keyup blur', function () {
            var val = $(this).val().trim();
            if (val != '')
                $(this).parent().addClass('is-active');
            else
                $(this).parent().removeClass('is-active')
        });
        ////  FORM PROCESS  ////
        function startAjax($this) {
            var $btn = $('button[type="submit"]', $this);
            submitBtnText = $btn.text();
            $btn.text('Please Wait...');
        }

        function stopAjax($this) {
            var $btn = $('button[type="submit"]', $this);
            $btn.text(submitBtnText);
        }

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }

        var formErrors;
        var $ajaxForm = $('.xsAjax');
        $ajaxForm.append('<div class="row"><div class="col-sm-12"><div class="formErrors"></div></div></div>');
        $ajaxForm.submit(function (e) {
            formErrors = new Array();
            $('.formErrors').html('').removeClass('alert alert-danger alert-success');
            var $this = $(this);
            var $btn = $('button[type=submit]', this);
            var $btnTxt = $btn.text();
            $btn.addClass('xs-loading');
            $btn.text('Loading ...');
            startAjax($this);
            $.ajax({
                type: 'POST',
                data: $(this).serialize(),
                //dataType: 'JSON',
                success: function (data) {
                    stopAjax();
                    if (isJson(data)) {
                        var obj = jQuery.parseJSON(data);
                        if (obj.status) {
                            $('.formErrors').html(obj.errors).addClass('alert alert-success').removeClass(' alert-danger');
                        } else {
                            $('.formErrors').html(obj.errors).addClass('alert alert-danger').removeClass(' alert-success');
                        }
                        if (typeof  obj.redirect != 'undefined' && obj.redirect != null) {
                            window.location = obj.redirect;
                        }
                        $btn.text($btnTxt);
                        $btn.removeClass('xs-loading');
                    } else {
                        formErrors.push('Invalid JSON data returned by server');
                        $('.formErrors').html(formErrors.join('<br>')).addClass('alert alert-danger').removeClass(' alert-success');
                        $btn.text($btnTxt);
                        $btn.removeClass('xs-loading');
                    }
                },
                error: function () {
                    stopAjax();
                }
            })
            e.preventDefault();
        });
    })
})(jQuery)