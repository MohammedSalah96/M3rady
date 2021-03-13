var Profile = function () {
    var init = function () {
        handleSubmit();
    };
    var handleSubmit = function () {
        $('#updateProfileForm').validate({
            rules: {
                name: {
                    required: true
                },
                phone: {
                    required: true,
                    number: true
                },
                email: {
                    required: true,
                    email: true,
                },
            },
            messages:{
                name:{
                    required: lang.required_rule
                },
                phone:{
                    required: lang.required_rule,
                    number:lang.number_rule
                },
                email:{
                    required: lang.required_rule,
                    email: lang.email_rule,
                }
            },
            //messages: lang.messages,
            highlight: function (element) { // hightlight error inputs
            },
            unhighlight: function (element) {
              $(element).closest('.form-group').find('.invalid-feedback').html('').hide();
            },
            errorPlacement: function (error, element) {
                $(element).closest('.form-group').find('.invalid-feedback').html($(error).html()).show();
            }
        });
        $('.submit-form').click(function () {
            if ($('#updateProfileForm').validate().form()) {
                $('.submit-form').prop('disabled', true);
                $('.submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function () {
                    $('#updateProfileForm').submit();
                }, 1000);

            }
            return false;
        });

        $('#updateProfileForm').submit(function () {
            var formData = new FormData($(this)[0]);
            var action = config.admin_url + '/profile';
            formData.append('_method', 'PATCH');
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('.submit-form').prop('disabled', false);
                    $('.submit-form').html(lang.save_changes);
                    if (data.type == 'success')
                    {
                        swal.fire({
                            text: data.message,
                            icon: "success",
                            buttonsStyling: !1,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } 
                    else {
                        if (typeof data.errors === 'object') {
                            for (i in data.errors)
                            {
                                var message = data.errors[i][0];
                                $('[name="' + i + '"]').closest('.form-group').find('.invalid-feedback').html(message).show();
                            }
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('.submit-form').prop('disabled', false);
                    $('.submit-form').html(lang.save_changes);
                    My.ajax_error_message(xhr);
                },
                dataType: "json",
                type: "POST"
            });

            return false;

        })
    }

    return{
        init: function () {
            init();
        }
    };
}();

$(document).ready(function () {
    Profile.init();
});