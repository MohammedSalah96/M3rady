var My = function () {

    // IE mode
    var isRTL = false;
    var isIE8 = false;
    var isIE9 = false;
    var isIE10 = false;

    var resizeHandlers = [];

    var assetsPath = 'assets/';

    var globalImgPath = 'global/img/';

    var globalPluginsPath = 'global/plugins/';

    var globalCssPath = 'global/css/';
    var ajaxGoToXHR;
    // theme layout color set

    var brandColors = {
        'blue': '#89C4F4',
        'red': '#F3565D',
        'green': '#1bbc9b',
        'purple': '#9b59b6',
        'grey': '#95a5a6',
        'yellow': '#F8CB00'
    };

    var handleNewValidatorMethods = function () {
        $.validator.addMethod('filesize', function (value, element, param) {
            if (element.files.length > 0) {
                return this.optional(element) || (element.files[0].size <= param)
            }
            return true;


        }, function (params, element) {
            var message = lang.filesize_can_not_be_more_than
            return message + ' ' + params;
        });
    }


    return {
        //main function to initiate the theme
        init: function () {
            //handleRemoveImage();
            handleNewValidatorMethods();
        },
        print: function (div)
        {
            window.print();
            return false;
        },
        toast: function (message) {
            toastr.options = {
                "debug": false,
                "positionClass": "toast-bottom-left",
                "onclick": null,
                "fadeIn": 300,
                "fadeOut": 1000,
                "timeOut": 5000,
                "extendedTimeOut": 1000,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.success(message, lang.message);
        },
        getYoutubeEmbedUrl: function (url) {
            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            var match = url.match(regExp);

            if (match && match[2].length == 11) {
                return match[2];
            } else {
                return 'error';
            }
        },
        readImage: function (input) {

            $(document).on('click', "." + input, function () {
                $("#" + input).trigger('click');
            });
            $(document).on('change', "#" + input, function () {
                //alert($(this)[0].files.length);
                for (var i = 0; i < $(this)[0].files.length; i++) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.' + input + '_box').html('<img style="height:80px;width:150px;" id="image_upload_preview" class="' + input + '" src="' + e.target.result + '" alt="your image" />');
                    }
                 
                    reader.readAsDataURL($(this)[0].files[i]);
                }

            });



        },
        readImageMulti: function (input) {

            $(document).on('click', "." + input, function () {
                $("#" + input).trigger('click');
            });

            $(document).on('change', "#" + input, function () {
                //console.log($(this));
                for (var i = 0; i < $(this)[0].files.length; i++) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.' + input + '_box').html('<img style="height:80px;width:100px;" id="image_upload_preview" class="' + input + '" src="' + e.target.result + '" alt="your image" />');
                    }
                    reader.readAsDataURL($(this)[0].files[i]);
                }

            });



        },

        readImageMultiWithRemove: function (input) {

            $(document).on('click', "." + input, function () {
                $("#" + input).trigger('click');
            });

            $(document).on('change', "#" + input, function () {

                for (var i = 0; i < $(this)[0].files.length; i++) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.' + input + '_box').html('<a href="javascript:;" style="position: absolute; top: 10%; right:0; left: 0; bottom: 0; width:25px; height:25px;" onclick = "My.deleteImage(this);return false;"><img style="width:25px; height:25px;" src="' + config.url + '/delete-btn.png" /></a><img style="height:80px;width:100px;" id="image_upload_preview" class="" src="' + e.target.result + '" alt="your image" />');
                    }
                    reader.readAsDataURL($(this)[0].files[i]);
                }

            });
        },
        deleteImage:function(t){
            file_input = $(t).closest('.form-group').find('input[type="file"]');
            file_id = $(t).closest('.form-group').find('input[type="file"]').attr('id');
            var id = $('#id').val();
            if (id == 0) {
               
                file_input.val('');
                $(t).closest('.form-group .' + file_id + '_box').html('<img src="' + config.url + '/no-image.png" width="100" height="80" class="' + file_id + '" />');
                $(t).remove();
            }else{
               
                file_input.val('');
                $(t).closest('.form-group .' + file_id + '_box').html('<img src="' + config.url + '/no-image.png" width="100" height="80" class="' + file_id + '" />');
                $(t).remove();
                var action = config.admin_url + '/delete_image';
                $(t).unbind('click');
                $(t).html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>');
                var params = {
                    id: id,
                    model: $(t).data('model'),
                    folder: $(t).data('folder'),
                    col: $(t).data('col'),
                    image: $(t).data('image'),
                    _token: $('input[name="_token"]').val()
                }
                $.ajax({
                    url: action,
                    data: JSON.stringify(params),
                    success: function (data) {
                      
                       file_input.val('');
                      $(t).closest('.form-group .' + file_id + '_box').html('<img src="' + config.url + '/no-image.png" width="100" height="80" class="' + file_id + '" />');
                      $(t).remove();
                        if (data.type == 'success') {
                            console.log(data.message);
                        } else {
                            console.log('error');
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        $(t).html('<img style="width:25px; height:25px;" src="' + config.url + '/delete-btn.png" />');
                        console.log(errorThrown);
                        My.ajax_error_message(xhr);
                    },
                    dataType: "json",
                    type: "POST",
                    contentType: 'application/json; charset=utf-8',
                });
            }
           
              
        },
        takeSnapShot: function (t) {
            width = $(t).parent().parent().width();
            height = $(t).parent().parent().height();
            Webcam.set({
                width: width,
                height: 400,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            image_width = $(t).width();
            image_height = $(t).attr('height');
            class_name = $(t).attr('class').split(/\s+/)[0];
            $('.take_snapshot_' + class_name).show();
            $(t).hide();
            Webcam.attach('.' + class_name + '_box .snap-container');

            $('.take_snapshot_' + class_name).on('click', function () {
                Webcam.snap(function (data_uri) {
                    $("#" + class_name).val(data_uri);
                    $('.' + class_name + '_box').html('<a href="javascript:;" style="position: absolute; top: 10%; right:0; left: 0; bottom: 0; width:25px; height:25px;" onclick = "My.deleteSnapImage(this);return false;"><img style="width:25px; height:25px;" src="' + config.url + '/delete-btn.png" /></a><img src="' + data_uri + '" style="width:100%;" height="' + image_height + '"/>');
                });
                $('.take_snapshot_' + class_name).hide();
                $(".snap-camera-image").attr("onclick", "My.takeSnapShot(this)");
                Webcam.reset();

            });
        },
        deleteSnapImage: function (t) {
            image_input = $(t).closest('.form-group').find('input[type="hidden"]');
            image_id = $(t).closest('.form-group').find('input[type="hidden"]').attr('id');
            image_width = $(t).closest('.form-group').width();
            image_height = $(t).next('img').attr('height');
            image_input.val('');
            $(t).closest('.form-group .' + image_id + '_box').html('<div class="snap-container"></div><img src="' + config.url + '/no-image.png" style="width:100%;" height="' + image_height + '" class="' + image_id + ' snap-camera-image" onclick="My.takeSnapShot(this)"/>');
            $(t).remove();
        },
        number_format: function (number, decimals, dec_point, thousands_sep) {


            number = (number + '')
                    .replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    s = '',
                    toFixedFix = function (n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + (Math.round(n * k) / k)
                                .toFixed(prec);
                    };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
                    .split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '')
                    .length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1)
                        .join('0');
            }
            return s.join(dec);
        },

        ajax_error_message: function (xhr) {
            var message;
            if (xhr.status == 403) {
                message = 'The action you have requested is not allowed';
            } else {
                message = xhr.responseText;
                if (typeof xhr.responseJSON !== "undefined")
                {
                    message = xhr.responseJSON.message;
                }

            }
            swal.fire({
                html: message,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-primary"
                },
            })

        }
        ,
        set_error: function (id, msg) {
            $('[name="' + id + '"]')
                    .closest('.form-group').addClass('has-error').removeClass("has-info");
            $('#' + id).parent()

            if ($("#" + id).parent().hasClass("input-group"))
            {
                $help_block = $('#' + id).parent().parent().find('.help-block');
            } else {
                $help_block = $('#' + id).parent().find('.help-block');
            }


            if ($help_block.length)
            {
                $help_block.html(msg);
            } else {
                if ($("#" + id).parent().hasClass("input-group"))
                    $('#' + id).parent().parent().append('<span class="help-block">' + msg + '</span>');
                else
                    $('#' + id).parent().append('<span class="help-block">' + msg + '</span>');
            }
        }
        ,
        set_errors: function (errors) {
            for (var i in errors)
            {
                My.set_error(i, errors[i]);
            }
        }
        ,
        initCheckbox: function () {

            if ($('#checkAll').length == 0)
                return false;

            var checkboxes = document.querySelectorAll('input.check-me'),
                    checkall = document.getElementById('checkAll');

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].onclick = function () {
                    var checkedCount = document.querySelectorAll('input.check-me:checked').length;

                    checkall.checked = checkedCount > 0;
                    checkall.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
                    if (checkedCount > 0)
                    {
                        $('#delete-selected').prop("disabled", false);
                    } else {
                        $('#delete-selected').prop("disabled", true);
                    }
                    if (checkedCount > 0 && checkedCount < checkboxes.length)
                    {
                        $('#checkAll').parent().addClass("indeterminate").removeClass("checked");
                    } else {
                        $('#checkAll').parent().removeClass("indeterminate");
                    }
                    $('#delete-num').html(checkedCount)
                }
            }

            checkall.onclick = function () {

                var checkedCount = document.querySelectorAll('input.check-me:checked').length;
                if (checkedCount > 0 && checkedCount < checkboxes.length)
                {
                    this.checked = true;
                } else if (checkedCount == 0) {
                    this.checked = true;
                } else {
                    this.checked = false;
                }

                $('#checkAll').parent().addClass("checked").removeClass("indeterminate");

                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = this.checked;
                }

                if (document.querySelectorAll('input.check-me:checked').length > 0)
                {
                    $('#delete-selected').prop("disabled", false);
                } else {
                    $('#delete-selected').prop("disabled", true);
                }

                $('#delete-num').html(document.querySelectorAll('input.check-me:checked').length)
            }
        }
        ,
        emptyForm: function () {
            $('input[type="text"],input[type="email"],input[type="date"],input[type="password"],input[type="number"],textarea').val("");
        }
        ,
        scrollTo: function (el, offeset) {
            var pos = (el && el.size() > 0) ? el.offset().top : 0;

            if (el) {
                if ($('body').hasClass('page-header-fixed')) {
                    pos = pos - $('.page-header').height();
                } else if ($('body').hasClass('page-header-top-fixed')) {
                    pos = pos - $('.page-header-top').height();
                } else if ($('body').hasClass('page-header-menu-fixed')) {
                    pos = pos - $('.page-header-menu').height();
                }
                pos = pos + (offeset ? offeset : -1 * el.height());
            }

            $('html,body').animate({
                scrollTop: pos
            }, 'slow');
        }
        ,
        setModalTitle: function (id, title)
        {
            $(id + 'Label').html(title);
        },
        editForm: function (args) {
            $(args.element).html('<i class="fas fa-circle-notch fa-spin"></i>');
            var editIcon ='<span class="svg-icon svg-icon-md">' 
            editIcon +='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">'
            editIcon +='<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">'
            editIcon +='<rect x="0" y="0" width="24" height="24"></rect>'
            editIcon +='<path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path>'
            editIcon +='<rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect>'
            editIcon +='</g>'
            editIcon +='</svg>'
            editIcon +='</span>'
            $.ajax({
                url: args.url,
                data: args.data,
                success: function (data) {
                    $('[data-toggle="tooltip"]').tooltip('hide');
                    if (data.type == 'success') {
                       $(args.element).html(editIcon);
                        args.success(data);
                    } else {
                        $(args.element).html(editIcon);
                        swal.fire({
                            html: data.message,
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            },
                        });

                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('[data-toggle="tooltip"]').tooltip('hide');
                    $(args.element).html(editIcon);
                    My.ajax_error_message(xhr);
                },
                dataType: "json",
                type: "GET"
            })

        },
        deleteForm: function (args) {
            $(args.element).html('<i class="fas fa-circle-notch fa-spin"></i>');
            var deleteIcon = '<span class="svg-icon svg-icon-md">';
            deleteIcon += '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">'
            deleteIcon += '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">';
            deleteIcon += '<rect x="0" y="0" width="24" height="24"/>';
            deleteIcon += '<path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>';
            deleteIcon += '<path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>';
            deleteIcon += '</g>';
            deleteIcon += '</svg>';
            deleteIcon += '</span>'
            $.ajax({
                url: args.url,
                data: args.data,
                success: function (data) {
                    $('[data-toggle="tooltip"]').tooltip('hide');
                    if (data.type == 'success') {
                        $(args.element).closest('tr').fadeOut('slow');
                        args.success(data);
                    } else {
                        $(args.element).html(deleteIcon);
                        swal.fire({
                            html: data.message,
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            },
                        });

                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('[data-toggle="tooltip"]').tooltip('hide');
                    $(args.element).html(deleteIcon);
                    My.ajax_error_message(xhr);
                },
                dataType: "json",
                type: "post"
            })

        }
        ,
        multiDeleteForm: function (args) {

            My.clearToolTip();

            if ($(args.element).hasClass("has-confirm")) {
                $(args.element).confirmation('show');
                return false;
            }
            $(args.element).addClass("has-confirm");
            $(args.element).confirmation({
                href: "javascript:;",
                onConfirm: function () {

                    $.ajax({
                        url: config.site_url + args.url,
                        data: args.data,
                        success: function (data) {

                            if (data.type == 'success')
                            {
                                args.success(data);
                                $(args.element).prop("disabled", true);
                                $('#delete-num').html(0);
                                $('#checkAll').prop("indeterminate", false).parent().removeClass("indeterminate");

                            } else {
                                bootbox.dialog({
                                    message: data.message,
                                    title: lang.messages_error,
                                    buttons: {
                                        danger: {
                                            label: lang.close,
                                            className: "red"
                                        }
                                    }
                                });
                            }

                        },
                        error: function (xhr, textStatus, errorThrown) {

                            $('.loading').addClass('hide');
                            bootbox.dialog({
                                message: xhr.responseText,
                                title: lang.messages_error,
                                buttons: {
                                    danger: {
                                        label: lang.close,
                                        className: "red"
                                    }
                                }
                            });
                        },
                        dataType: "json",
                        type: "post"
                    })

                    return false;
                }
            }).confirmation('show');
        }
        ,
    }
    ;

}();

jQuery(document).ready(function () {
    My.init();
});

