var LoginGeneral = function () {
    var t = $("#kt_login"), loginFormValidation, forgotFormValidation, resetFormValidation;

    var init = function () {
        handleLogin();
        handleForgotPassword();
    }

    var handleShowForm = function (i) {
        var o = "login-" + i + "-on";
        i = "kt_login_" + i + "_form";
        t.removeClass("login-forgot-on"), t.removeClass("login-signin-on"), t.removeClass("login-signup-on"), t.addClass(o), KTUtil.animateClass(KTUtil.getById(i), "animate__animated animate__backInUp");
    };

    

    var handleLogin = function () {
        loginFormValidation = FormValidation.formValidation(KTUtil.getById("kt_login_signin_form"), {
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: "Email is required"
                        },
                        emailAddress: {
                            message: "The value is not a valid email address"
                        },
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: "Password is required"
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                bootstrap: new FormValidation.plugins.Bootstrap()
            }
        });

        $("#kt_login_signin_form #kt_login_signin_submit").on("click", function (e) {
            e.preventDefault();
            handleLoginSubmit();
        })


        $("#kt_login_signin_form input").keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                handleLoginSubmit();
            }
        });

        $('#kt_login_signin_form').submit(function () {
            var formData = new FormData($(this)[0]);
            var action = config.admin_url + "/login";
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    $('#kt_login_signin_form #kt_login_signin_submit').prop('disabled', false);
                    $('#kt_login_signin_form #kt_login_signin_submit').html(lang.login);
                    if (data.type == 'success') {
                        swal.fire({
                            text: "All is cool! Welcome back.",
                            icon: "success",
                            buttonsStyling: !1,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            window.location.href = data.message;
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
                    $('#kt_login_signin_form #kt_login_signin_submit').prop('disabled', false);
                    $('#kt_login_signin_form #kt_login_signin_submit').html(lang.login);
                    My.ajax_error_message(xhr);
                },
                type: 'POST',
                dataType: 'json',
            });

            return false;
        });
    }

    var handleLoginSubmit = function () {
        loginFormValidation.validate().then(function (t) {
            if ("Valid" == t) {
                $('#kt_login_signin_form #kt_login_signin_submit').prop('disabled', true);
                $('#kt_login_signin_form #kt_login_signin_submit').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(() => {
                    $('#kt_login_signin_form').submit();
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

    var handleForgotPassword = function () {
        $("#kt_login_forgot").on("click", function (e) {
            e.preventDefault(), handleShowForm("forgot");
        });
        $("#kt_login_forgot_cancel").on("click", function (e) {
            e.preventDefault(), handleShowForm("signin");
        });

        forgotFormValidation = FormValidation.formValidation(KTUtil.getById("kt_login_forgot_form"), {
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: "Email is required"
                        },
                        emailAddress: {
                            message: "The value is not a valid email address"
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                bootstrap: new FormValidation.plugins.Bootstrap()
            }
        });

        $("#kt_login_forgot_form #kt_login_forgot_submit").on("click", function (e) {
            e.preventDefault();
            handleForgotSubmit();
        })


        $("#kt_login_forgot_form input").keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                handleForgotSubmit();
            }
        });

        $('#kt_login_forgot_form').submit(function () {
            var formData = new FormData($(this)[0]);
            var action = config.admin_url + "/password/email";
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#kt_login_forgot_form #kt_login_forgot_submit').prop('disabled', false);
                    $('#kt_login_forgot_form #kt_login_forgot_submit').html(lang.request);
                    if (data.type == 'success') {
                        swal.fire({
                            text: data.message,
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            }
                        });
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
                    $('#kt_login_forgot_form #kt_login_forgot_submit').prop('disabled', false);
                    $('#kt_login_forgot_form #kt_login_forgot_submit').html(lang.request);
                    My.ajax_error_message(xhr);
                },
                type: 'POST',
                dataType: 'json',
            });

            return false;
        });
    }

    var handleForgotSubmit = function () {
        forgotFormValidation.validate().then(function (t) {
            if ("Valid" == t) {
                $('#kt_login_forgot_form #kt_login_forgot_submit').prop('disabled', true);
                $('#kt_login_forgot_form #kt_login_forgot_submit').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(() => {
                    $('#kt_login_forgot_form').submit();
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
        },
    };
}();
jQuery(document).ready(function () {
    LoginGeneral.init();
});