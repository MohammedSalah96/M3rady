var ContactMessages = function () {
    var ContactMessagesGrid, ids = [];

    var init = function () {
        $.extend(lang, new_lang);
        handleRecords();
        handleCheck();
        handleSubmit();
    };

    var handleCheck = function () {
        $(".group-checkable").on('change', function () {
            $('.checkable').not(this).prop('checked', this.checked);
            $('#kt_datatable tbody tr').toggleClass('selected');
            enableOrDisableDeleteBtn();
            getCheckedIds();
        });

        $(document).on('change', '.checkable', function () {
            if ($(".checkable:checked").length == 0) {
                $('.group-checkable').prop('checked', false);
            }
            enableOrDisableDeleteBtn();
            getCheckedIds();
        });
    }

    var enableOrDisableDeleteBtn = function () {
        if ($(document).find(".checkable:checked").length == 0) {
            $(document).find('#btn-delete').prop('disabled', true);
        } else {
            $(document).find('#btn-delete').prop('disabled', false);
        }
    }

    var getCheckedIds = function () {
        var checked_ids = [];
        $(".checkable").each(function () {
            if ($(this).is(':checked')) {
                checked_ids.push($(this).data('id'));
            }
        });
        ids = checked_ids;
    }

    var handleRecords = function () {
        ContactMessagesGrid = $('#kt_datatable').DataTable({
            //"processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/contact_messages/data",
                "type": "POST",
                "data": {
                    _token: $('input[name="_token"]').val()
                },
            },
            "columns": [
                {
                    "data": "select",
                    orderable: false,
                    orderable: false
                },
                {
                    "data": "name"
                },
                {
                    "data": "mobile"
                },
                {
                    "data": "type"
                },
                {
                    "data": "message"
                },
                {
                    "data": "created_at"
                },
                {
                    "data": "options",
                    orderable: false,
                    searchable: false
                }
            ],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
            "order": [
                [4, "desc"]
            ],
            select:{
                style:"multi",
                selector:"td:first-child .checkable"
            },
            'columnDefs': [
                { 
                    className: 'text-center', targets: [0,1,2,3,4,5,6] 
                }
            ],
            "oLanguage": {
                "sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json'
            }

        });
    }

    var handleSubmit = function () {
        $('#contactUsReplyForm').validate({
            rules: {
                reply: {
                    required: true,
                }
            },
             messages:{
                reply:{
                    required: lang.required_rule
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

        $('#viewMessage .submit-form').click(function () {

            if ($('#contactUsReplyForm').validate().form()) {
                $('#viewMessage .submit-form').prop('disabled', true);
                $('#viewMessage .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function () {
                    $('#contactUsReplyForm').submit();
                }, 1000);
            }
            return false;
        });
        $('#contactUsReplyForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#contactUsReplyForm').validate().form()) {
                    $('#viewMessage .submit-form').prop('disabled', true);
                    $('#viewMessage .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function () {
                        $('#contactUsReplyForm').submit();
                    }, 1000);
                }
                return false;
            }
        });


        $('#contactUsReplyForm').submit(function () {
            var id = $('#id').val();
            var formData = new FormData($(this)[0]);
            formData.append('_method', 'PATCH');
            action = config.admin_url + '/contact_messages/' + id;
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#viewMessage .submit-form').prop('disabled', false);
                    $('#viewMessage .submit-form').html(lang.send);
                    if (data.type == 'success') {
                        My.toast(data.message);
                        ContactMessagesGrid.ajax.reload();
                        ContactMessages.empty();
                        $('#viewMessage').modal('hide');
                    } else {
                        if (typeof data.errors !== 'undefined') {
                            for (i in data.errors) {
                                var message = data.errors[i];
                                $('[name="' + i + '"]').closest('.form-group').find('.invalid-feedback').html(message).show();
                            }
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#viewMessage .submit-form').prop('disabled', false);
                    $('#viewMessage .submit-form').html(lang.send);
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
        delete: function (t) {
            if(confirm(lang.are_you_sure_you_want_to_delete_this+'?')){
                var id = $(t).attr("data-id");
                My.deleteForm({
                    element: t,
                    url: config.admin_url + '/contact_messages',
                    data: {_method: 'DELETE',id: id, _token: $('input[name="_token"]').val()},
                    success: function (data)
                    {
                        ContactMessagesGrid.ajax.reload();
                        $('[data-toggle="tooltip"]').tooltip('hide');
                    }
                });
            }
            else{
                return false;
            }  
        },
        multipleDelete: function (t) {
            $(t).prop('disabled', true);
            $(t).html('<i class="fas fa-circle-notch fa-spin"></i>');
            var deleteIcon = '<span class="svg-icon svg-icon-md">';
            deleteIcon += '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">'
            deleteIcon += '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">';
            deleteIcon += '<rect x="0" y="0" width="24" height="24"></rect>';
            deleteIcon += '<path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>';
            deleteIcon += '<path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>';
            deleteIcon += '</g>';
            deleteIcon += '</svg>';
            deleteIcon += '</span>'
            if (ids.length > 0) {
                setTimeout(function () {
                    $.ajax({
                            url: config.admin_url + '/contact_messages',
                            data: {
                                _method: 'DELETE',
                                ids: ids,
                                _token: $('input[name="_token"]').val()
                            },
                            success: function (data) {
                                $(t).prop('disabled', false);
                                $(t).html(deleteIcon + ' ' + lang.delete_records);
                                if (data.type == 'success') {
                                    My.toast(data.message);
                                    ContactMessagesGrid.ajax.reload();
                                    $('[data-toggle="tooltip"]').tooltip('hide');
                                } else {
                                    $(t).prop('disabled', false);
                                    $(t).html(deleteIcon + ' ' + lang.delete_records);
                                    swal.fire({
                                        html: data.message,
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn font-weight-bold btn-light-primary"
                                        },
                                    })
                                }
                            },
                            error: function (xhr, textStatus, errorThrown) {
                                $(t).prop('disabled', false);
                                $(t).html(deleteIcon + ' ' + lang.delete_records);
                                My.ajax_error_message(xhr);
                            },
                            dataType: "json",
                            type: "post"
                        })
                }, 1000);
            } else {
                swal.fire({
                    text: lang.no_item_selected,
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    },
                })
            }
        },
        show: function (t) {
            $(t).prop('disabled', true);
            $(t).html('<i class="fas fa-circle-notch fa-spin"></i>');
            showIcon = '<span class="svg-icon svg-icon-md">';
            showIcon += '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">';
            showIcon += '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">';
            showIcon += '<rect x="0" y="0" width="24" height="24"/>';
            showIcon += '<path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>';
            showIcon += '<path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>';
            showIcon += '</g>';
            showIcon += '</svg>';
            showIcon += '</span>';
            var id = $(t).data('id');
            setTimeout(function () {
                $.ajax({
                    url: config.admin_url + "/contact_messages/" + id,
                    async: false,
                    success: function (data) {
                        $(t).prop('disabled', false);
                        $(t).html(showIcon);
                        if (data.type == 'success') {
                            $('#viewMessage').modal('show');
                            if (data.message.status == 0) {
                                $('#viewMessage #relpied').hide();
                                $('#viewMessage #contactUsReplyForm').show();
                                $('#viewMessage .submit-form').show();
                                $('#viewMessage #contactUsReplyForm #id').val(data.message.id)
                                $('#viewMessage #contactUsReplyForm #message').html(data.message.message);
                            }else{
                                $('#viewMessage #contactUsReplyForm').hide();
                                $('#viewMessage .submit-form').hide();
                                $('#viewMessage #relpied').show();
                                $('#viewMessage #message').html(data.message.message);
                                $('#viewMessage #reply').html(data.message.reply);
                            }
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        $(t).prop('disabled', false);
                        $(t).html(showIcon);
                        My.ajax_error_message(xhr);
                    },
                    dataType: "json",
                    type: "GET"
                });
            }, 1000);


        },
        empty: function () {
            $('#id').val(0)
            $('.invalid-feedback').html('');
            $('[data-toggle="tooltip"]').tooltip('hide');
            My.emptyForm();
        }
    };

}();
jQuery(document).ready(function () {
    ContactMessages.init();
});
