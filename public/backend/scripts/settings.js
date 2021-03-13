var Settings = function () {

    var init = function () {
        handleSubmit();
    };

    var handleSubmit = function () {
        $('#editSettingsForm').validate({
            rules: {
                'setting[social_media][facebook]': {
                    required: true
                },
                'setting[social_media][twitter]': {
                    required: true
                },
                'setting[email]': {
                    required: true
                },
                'setting[phone]': {
                    required: true
                },
                'setting[allowed_free_posts]': {
                    required: true
                }
            },
            messages:{
                'setting[social_media][facebook]':{
                    required: lang.required_rule
                },
                'setting[social_media][twitter]':{
                    required: lang.required_rule
                },
                'setting[email]':{
                    required: lang.required_rule
                },
                'setting[phone]':{
                    required: lang.required_rule
                },
                'setting[allowed_free_posts]':{
                    required: lang.required_rule
                }
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
            var about_us = "textarea[name='about_us[" + langs[x] + "]']";
            $(about_us).rules('add', {
                required: true
            });
        }

        $('#editSettingsForm .submit-form').click(function () {
            if ($('#editSettingsForm').validate().form()) {
                $('#editSettingsForm .submit-form').prop('disabled', true);
                $('#editSettingsForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function () {
                    $('#editSettingsForm').submit();
                }, 500);
            }
            return false;
        });
        $('#editSettingsForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#editSettingsForm').validate().form()) {
                    $('#editSettingsForm .submit-form').prop('disabled', true);
                    $('#editSettingsForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function () {
                        $('#editSettingsForm').submit();
                    }, 500);
                }
                return false;
            }
        });



        $('#editSettingsForm').submit(function () {
            var action = config.admin_url + '/settings';
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#editSettingsForm .submit-form').prop('disabled', false);
                    $('#editSettingsForm .submit-form').html(lang.save);
                    if (data.type == 'success') {
                        My.toast(data.message);
                    } else {
                        if (typeof data.errors === 'object') {
                            for (i in data.errors) {
                                var message = data.errors[i];
                                if (i.startsWith('about_us')) {
                                    var key_arr = i.split('.');
                                    var key_text = key_arr[0] + '[' + key_arr[1] + ']';
                                    i = key_text;
                                }
                                $('[name="' + i + '"]').closest('.form-group').find('.invalid-feedback').html(message).show();
                            }
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#editSettingsForm .submit-form').prop('disabled', false);
                    $('#editSettingsForm .submit-form').html(lang.save);
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
        }
    };

}();
jQuery(document).ready(function () {
    Settings.init();
});