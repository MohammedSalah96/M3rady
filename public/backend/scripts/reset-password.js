var ResetEmail = function () {
    var resetFormValidation;
    var init = function () {
        handleResetPassword();
    }
    var handleResetPassword = function () {
        $("#kt_login").removeClass('login-signin-on').addClass('login-signup-on');
        resetFormValidation = FormValidation.formValidation(KTUtil.getById("kt_login_reset_password_form"), {
            fields: {
                 email: {
                         validators: {
                             notEmpty: {
                                message: "Email address is required"
                             },
                             emailAddress: {
                                message: "The value is not a valid email address"
                             }
                         }
                     },
                     password: {
                         validators: {
                             notEmpty: {
                                 message: "The password is required"
                             }
                         }
                     },
                     password_confirmation: {
                         validators: {
                             notEmpty: {
                                message: "The password confirmation is required"
                             },
                             identical: {
                                 compare: function () {
                                    return KTUtil.getById("kt_login_reset_password_form").querySelector('[name="password"]').value;
                                 },
                                 message: "The password and its confirm are not the same",
                             },
                         },
                     },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                bootstrap: new FormValidation.plugins.Bootstrap()
            }
        });

        $("#kt_login_reset_password_form #kt_login_reset_password_submit").on("click", function (e) {
            e.preventDefault();
            handleResetPasswordSubmit();
        })


        $("#kt_login_reset_password_form input").keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                handleResetPasswordSubmit();
            }
        });

        $('#kt_login_reset_password_form').submit(function () {
            var formData = new FormData($(this)[0]);
            var action = config.admin_url + "/password/reset";
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#kt_login_reset_password_form #kt_login_reset_password_submit').prop('disabled', false);
                    $('#kt_login_reset_password_form #kt_login_reset_password_submit').html(lang.reset_password);
                    if (data.type == 'success') {
                        swal.fire({
                            text: 'You have successfully reset your password!',
                            icon: "success",
                            buttonsStyling: !1,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            window.location.href = config.admin_url + '/login';
                        }, 2000);
                    } else {
                        if (typeof data.errors !== 'undefined') {
                            for (i in data.errors) {
                                $('[name="' + i + '"]').addClass('is-invalid').removeClass('is-valid');
                                $('[name="' + i + '"]').closest('.form-group').addClass('has-danger').removeClass("has-success");
                                if ($('[name="' + i + '"]').closest('.form-group').find(".fv-help-block") !== 'undefined') {
                                    $('[name="' + i + '"]').closest('.form-group').find('.fv-plugins-message-container').append('<div class="fv-help-block"></div>');
                                }
                                $('[name="' + i + '"]').closest('.form-group').find(".fv-help-block").html(data.errors[i][0])
                            }
                        }
                        if (typeof data.message !== 'undefined') {
                            swal.fire({
                                text: data.message,
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                },
                            })
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#kt_login_reset_password_form #kt_login_reset_password_submit').prop('disabled', false);
                    $('#kt_login_reset_password_form #kt_login_reset_password_submit').html(lang.reset_password);
                    My.ajax_error_message(xhr);
                },
                type: 'POST',
                dataType: 'json',
            });

            return false;
        });
    }

    var handleResetPasswordSubmit = function () {
        resetFormValidation.validate().then(function (t) {
            if ("Valid" == t) {
                $('#kt_login_reset_password_form #kt_login_reset_password_submit').prop('disabled', true);
                $('#kt_login_reset_password_form #kt_login_reset_password_submit').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(() => {
                    $('#kt_login_reset_password_form').submit();
                }, 1000);

            } else {
                swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        },
                    })
                    .then(function () {
                        KTUtil.scrollTop();
                    });
            }
        });
    }

    return {
        init: function () {
            init();
        }
    }

}();

jQuery(document).ready(function () {
    ResetEmail.init();
});