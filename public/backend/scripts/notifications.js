var Notifications = function () {

    var init = function () {
        handleSubmit();
    };

    var handleSubmit = function () {
        $('#sendNotificationsForm').validate({
            rules: {
               
            },
            messages:{
                
            },
            highlight: function (element) { // hightlight error inputs
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').find('.invalid-feedback').html('').hide();
            },
            errorPlacement: function (error, element) {
                $(element).closest('.form-group').find('.invalid-feedback').html($(error).html()).show();
            }
        });

        var langs = JSON.parse(config.languages);
        for (var x = 0; x < langs.length; x++) {
            var body = "textarea[name='body[" + langs[x] + "]']";
            $(body).rules('add', {
                required: true
            });
        }

        $('#sendNotificationsForm .submit-form').click(function () {
            if ($('#sendNotificationsForm').validate().form()) {
                $('#sendNotificationsForm .submit-form').prop('disabled', true);
                $('#sendNotificationsForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function () {
                    $('#sendNotificationsForm').submit();
                }, 500);
            }
            return false;
        });
        $('#sendNotificationsForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#sendNotificationsForm').validate().form()) {
                    $('#sendNotificationsForm .submit-form').prop('disabled', true);
                    $('#sendNotificationsForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function () {
                        $('#sendNotificationsForm').submit();
                    }, 500);
                }
                return false;
            }
        });



        $('#sendNotificationsForm').submit(function () {
            var action = config.admin_url + '/notifications';
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    $('#sendNotificationsForm .submit-form').prop('disabled', false);
                    $('#sendNotificationsForm .submit-form').html(lang.send);
                    if (data.type == 'success') {
                        My.toast(data.message);
                        Notifications.empty();  
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#sendNotificationsForm .submit-form').prop('disabled', false);
                    $('#sendNotificationsForm .submit-form').html(lang.send);
                    My.ajax_error_message(xhr);
                },
                dataType: "json",
                type: "POST"
            });

            return false;

        })
    }

    return {
        init: function () {
            init();
        },
        empty:function(){
            My.emptyForm();
        }
    };

}();
jQuery(document).ready(function () {
    Notifications.init();
});