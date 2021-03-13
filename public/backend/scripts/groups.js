var Groups = function () {
    var groupsGrid;
    var init = function () {
        $.extend(lang, new_lang);
        handleRecords();
        handleSubmit();
    };

    var handleRecords = function () {

        groupsGrid = $('#kt_datatable').DataTable({
            //"processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/groups/data",
                "type": "POST",
                data: {_token: $('input[name="_token"]').val()},
            },
            "columns": [
                {
                    "data": "name"
                }, 
                {
                    "data": "active"
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
                [1, "desc"]
            ],
            'columnDefs': [
                { 
                    className: 'text-center', targets: [0,1,2] 
                }
            ],
            "oLanguage": {"sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json'}

        });
    }
    var handleSubmit = function () {

        $('#addEditGroupsForm').validate({
            rules: {
                name: {
                    required: true
                }
            },
            messages:{
                name:{
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
        $('#addEditGroups .submit-form').click(function () {
            if ($('#addEditGroupsForm').validate().form()) {
                $('#addEditGroups .submit-form').prop('disabled', true);
                $('#addEditGroups .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function () {
                    $('#addEditGroupsForm').submit();
                }, 1000);

            }
            return false;
        });
        $('#addEditGroupsForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#addEditGroupsForm').validate().form()) {
                    $('#addEditGroups .submit-form').prop('disabled', true);
                    $('#addEditGroups .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function () {
                        $('#addEditGroupsForm').submit();
                    }, 1000);

                }
                return false;
            }
        });

        $('#addEditGroupsForm').submit(function () {
            var id = $('#id').val();
            var action = config.admin_url + '/groups';
            var formData = new FormData($(this)[0]);
            if (id != 0) {
                formData.append('_method', 'PATCH');
                action = config.admin_url + '/groups/' + id;
            }
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    $('#addEditGroups .submit-form').prop('disabled', false);
                    $('#addEditGroups .submit-form').html(lang.save);

                    if (data.type == 'success')
                    {
                        My.toast(data.message);
                        groupsGrid.ajax.reload();
                        $('[data-toggle="tooltip"]').tooltip('hide');
                        if (id != 0) {
                            $('#addEditGroups').modal('hide');
                        } else {
                            Groups.empty();
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
                    $('#addEditGroups .submit-form').prop('disabled', false);
                    $('#addEditGroups .submit-form').html(lang.save);
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
                url: config.admin_url + '/groups/' + id,
                success: function (data)
                {
                    Groups.empty();
                    My.setModalTitle('#addEditGroups', lang.edit_group);

                    $('#id').val(data.message['id']);
                    $('#name').val(data.message['name']);
                    $('#active').val(data.message['active']);
                    var permissions = data.message['permissions'];
                    for (i in permissions)
                    {
                        var page_name = i;
                        var page_permissions = permissions[i];
                        for (x in page_permissions)
                        {
                            $('#' + page_name + '_' + x).prop("checked", true).trigger("change");

                        }

                    }
                    $('#addEditGroups').modal('show');
                }
            });

        },
        delete: function (t) {
            if(confirm(lang.are_you_sure_you_want_to_delete_this+'?')){
                var id = $(t).attr("data-id");
                My.deleteForm({
                    element: t,
                    url: config.admin_url + '/groups/' + id,
                    data: {
                        _method: 'DELETE',
                        _token: $('input[name="_token"]').val()
                    },
                    success: function (data) {
                        groupsGrid.ajax.reload();
                        $('[data-toggle="tooltip"]').tooltip('hide');
                    }
                });
            }else{
                return false;
            }  
            
        },
        add: function () {
            Groups.empty();
            My.setModalTitle('#addEditGroups', lang.add_group);
            $('#addEditGroups').modal('show');
        },
        empty: function () {
            $('#id').val(0);
            $('#active').find('option').eq(0).prop('selected', true);
            $('.invalid-feedback').html('');
            $('input[type="checkbox"]').prop("checked", false).trigger("change");
            $('[data-toggle="tooltip"]').tooltip('hide');
            My.emptyForm();
        },
    };
}();
$(document).ready(function () {
    Groups.init();
});