var Main = function () {

    var init = function () {
        handleChangeLang();
        $('[data-toggle="tooltip"]').tooltip();
    }

    var handleChangeLang = function () {
        $(document).on('click', '.change-lang', function (e) {
            e.preventDefault();
            var lang_code = $(this).attr('data-lang');
            var action = config.admin_url + '/change_lang';
            $.ajax({
                url: action,
                data: {
                    lang_code: lang_code
                },
                async: false,
                success: function (data) {
                    if (data.type == 'success') {
                        window.location.reload()
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    My.ajax_error_message(xhr);
                },
                dataType: "JSON",
                type: "GET"
            });

            return false;
        });
    }

    return {
        init: function () {
            init();
        },
    }

}();

jQuery(document).ready(function () {
    Main.init();
});