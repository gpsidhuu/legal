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
        })
    })
})(jQuery)