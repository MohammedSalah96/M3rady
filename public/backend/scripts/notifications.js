var Notifications = function () {
var NotificationsGrid;

    var init = function () {
        handleSubmit();
        handleRecords();
    };

    var handleRecords = function() {
        NotificationsGrid = $('#kt_datatable').DataTable({
            "processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/notifications/data",
                "type": "POST",
                data: { _token: $('input[name="_token"]').val() },
            },
            "columns": [
                {
                    "data": "body",
                    "name": "notification_translations.body",
                     orderable: false,
                },
                {
                    "data": "created_at",
                    "name": "notifications.created_at"
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
            "oLanguage": { "sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json' }

        });
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
                    $('#sendNotificationsForm .submit-form').prop('disabled', false);
                    $('#sendNotificationsForm .submit-form').html(lang.send);
                    if (data.type == 'success') {
                        My.toast(data.message);
                        NotificationsGrid.ajax.reload( null, false );
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
    };

    return {
        init: function () {
            init();
        },
        edit: function (t) {
           var id = $(t).attr("data-id");
           My.editForm({
               element: t,
               url: config.admin_url + '/notifications/' + id + '/edit',
               data: {},
               success: function (data) {
                   $('[data-toggle="tooltip"]').tooltip('hide');
                    /*var model = data.data.model;
                    for (var key in model) {
                       $('[name="'+key+'"]').val(model[key]);
                    }*/
                    var translations = data.data.translations;
                    for (var locale in translations) {
                        for(var key in translations[locale]){
                            if(key == 'locale') continue;
                            $('[name="'+key+'['+locale+']"]').val(translations[locale][key]);
                        } 
                    }
                    window.scrollTo({top: 0, behavior: 'smooth'});
               }
           });
        },
        empty:function(){
            My.emptyForm();
        }
    };

}();
jQuery(document).ready(function () {
    Notifications.init();
});