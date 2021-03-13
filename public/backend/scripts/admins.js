var Admins = function() {
    var adminsGrid, string_length = 8;
    var init = function() {
        handleRecords();
        handlePasswordActions(string_length);
        handleSubmit();
    };

    var handlePasswordActions = function (string_length) {
        $('#show-password').click(function () {
            if ($('#password').val() != '') {
                $("#password").attr("type", "text");

            } else {
                $("#password").attr("type", "password");

            }
        });
        $('#random-password').click(function () {
            $('[id^="password"]').closest('.form-group').find('.invalid-feedback').html('').hide();
            $('[id^="password"]').val(randomPassword(string_length));
        });
    }
    
    var randomPassword = function (string_length) {
        var chars = "0123456789!@#$%^&*abcdefghijklmnopqrstuvwxtzABCDEFGHIJKLMNOPQRSTUVWXTZ!@#$%^&*";
        var myrnd = [], pos;
        while (string_length--) {
            pos = Math.floor(Math.random() * chars.length);
            myrnd += chars.substr(pos, 1);
        }
        return myrnd;
    }
    
   
    var handleRecords = function() {
        var data = {
            _token: $('input[name="_token"]').val()
        }
        adminsGrid = $('#kt_datatable').DataTable({
            //"processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/admins/data",
                "data": data,
                "type": "POST"
            },
            "columns": [
                {
                    "data": "name",
                    "name": "admins.name"
                }, 
                {
                    "data": "email",
                    "name": "admins.email"
                }, 
                {
                    "data": "group",
                    "name": "groups.name"
                }, 
                {
                    "data": "active",
                    "name": "admins.active"
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
                [0, "asc"]
            ],
            'columnDefs': [
                { 
                    className: 'text-center', targets: [0,1,2,3] 
                },
            ],
            "oLanguage": { "sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json' }

        });
    }


    var handleSubmit = function () {

        $('#addEditAdminsForm').validate({
            rules: {
                name: {
                    required: true
                },
                group_id: {
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
                group_id:{
                    required: lang.required_rule
                },
                phone:{
                    required: lang.required_rule,
                    number:lang.number_rule
                },
                email:{
                    required: lang.required_rule,
                    email: lang.email_rule,
                },
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
        $('#addEditAdmins .submit-form').click(function () {
            if ($('#addEditAdminsForm').validate().form()) {
                $('#addEditAdmins .submit-form').prop('disabled', true);
                $('#addEditAdmins .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function () {
                    $('#addEditAdminsForm').submit();
                }, 1000);

            }
            return false;
        });
        $('#addEditAdminsForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#addEditAdminsForm').validate().form()) {
                    $('#addEditAdmins .submit-form').prop('disabled', true);
                    $('#addEditAdmins .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function () {
                        $('#addEditAdminsForm').submit();
                    }, 1000);
                }
                return false;
            }
        });



        $('#addEditAdminsForm').submit(function () {
            var id = $('#id').val();
            var formData = new FormData($(this)[0]);
            var action = config.admin_url + '/admins';
            if (id != 0) {
                formData.append('_method', 'PATCH');
                action = config.admin_url + '/admins/' + id;
            }
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#addEditAdmins .submit-form').prop('disabled', false);
                    $('#addEditAdmins .submit-form').html(lang.save);

                    if (data.type == 'success')
                    {
                        My.toast(data.message);
                        adminsGrid.ajax.reload();
                        if (id != 0) {
                            $('#addEditAdmins').modal('hide');
                        } else {
                            Admins.empty();
                        }

                    } else {
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
                    $('#addEditAdmins .submit-form').prop('disabled', false);
                    $('#addEditAdmins .submit-form').html(lang.save);
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
        },
        edit: function (t) {
            var id = $(t).attr("data-id");
            My.editForm({
                element: t,
                url: config.admin_url + '/admins/' + id,
                success: function (data)
                {
                    Admins.empty();
                    My.setModalTitle('#addEditAdmins', lang.edit_admin);
                    for (i in data.message)
                    {
                        if (i == 'password') {
                            continue;
                        }
                        $('#' + i).val(data.message[i]);
                    }
                    $('#addEditAdmins').modal('show');
                }
            });

        },
        delete: function (t) {
            if(confirm(lang.are_you_sure_you_want_to_delete_this+'?')){
                var id = $(t).attr("data-id");
                My.deleteForm({
                    element: t,
                    url: config.admin_url + '/admins/' + id,
                    data: {_method: 'DELETE', _token: $('input[name="_token"]').val()},
                    success: function (data)
                    {
                        adminsGrid.ajax.reload();
                        $('[data-toggle="tooltip"]').tooltip('hide');
                    }
                });
            }
            else{
                return false;
            }  
        },
        add: function () {
            Admins.empty();
            My.setModalTitle('#addEditAdmins', lang.add_admin);
            $('#addEditAdmins').modal('show');
        },
        empty: function () {
            $('#id').val(0);
            $('#active').find('option').eq(0).prop('selected', true);
            $('.invalid-feedback').html('');
            $('[data-toggle="tooltip"]').tooltip('hide');
            My.emptyForm();
        },
    };

}();
jQuery(document).ready(function() {
    Admins.init();
});