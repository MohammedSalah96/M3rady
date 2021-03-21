var WelcomeScreens = function() {
var WelcomeScreensGrid;

    var init = function() {
        $.extend(lang, newLang);
        $.extend(config, newConfig);
        handleRecords();
        handleSubmit();
        kImage = new KTImageInput("kt_avatar");
    };
   
    var handleRecords = function() {
        WelcomeScreensGrid = $('#kt_datatable').DataTable({
            "processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/welcome_screens/data",
                "type": "POST",
                data: { _token: $('input[name="_token"]').val() },
            },
            "columns": [
                {
                    "data": "image",
                    "name": "welcome_screens.image",
                    orderable: false,
                    searchable: false
                },
                {
                    "data": "description",
                    "name": "welcome_screen_translations.description",
                    orderable: false                
                },
                {
                    "data": "active",
                    "name": "welcome_screens.active",
                     orderable: false,
                }, 
                {
                    "data": "position",
                    "name": "welcome_screens.position"
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
            'columnDefs': [
                { 
                    className: 'text-center', targets: [0,1,2,3,4] 
                },
                {
                    "width": "30%",
                    "targets": 0
                }
            ],
            "oLanguage": { "sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json' }

        });
    }

    var handleSubmit = function() {
        $('#addEditWelcomeScreensForm').validate({
            rules: {
               active: {
                    required: true
                },
                position: {
                    required: true
                },
                image:{
                    extension: "png|jpeg|jpg"
                }
            },
            messages:{
                active:{
                    required: lang.required_rule
                },
                position:{
                    required: lang.required_rule
                }
            },
            highlight: function(element) { // hightlight error inputs
            },
            unhighlight: function(element) {
              $(element).closest('.form-group').find('.invalid-feedback').html('').hide();
            },
            errorPlacement: function(error, element) {
               $(element).closest('.form-group').find('.invalid-feedback').html($(error).html()).show();
            }
        });

        var langs = JSON.parse(config.languages);
        for (var x = 0; x < langs.length; x++) {
            var description = "textarea[name='description[" + langs[x] + "]']";
            $(description).rules('add', {
                required: true
            });
        }

        $('#addEditWelcomeScreensForm .submit-form').click(function() {

            if ($('#addEditWelcomeScreensForm').validate().form()) {
                $('#addEditWelcomeScreensForm .submit-form').prop('disabled', true);
                $('#addEditWelcomeScreensForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function() {
                    $('#addEditWelcomeScreensForm').submit();
                }, 1000);
            }
            return false;
        });
        $('#addEditWelcomeScreensForm input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#addEditWelcomeScreensForm').validate().form()) {
                    $('#addEditWelcomeScreensForm .submit-form').prop('disabled', true);
                    $('#addEditWelcomeScreensForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function() {
                        $('#addEditWelcomeScreensForm').submit();
                    }, 1000);
                }
                return false;
            }
        });

        $('#addEditWelcomeScreensForm').submit(function() {
            var id = $('#id').val();
            var action = config.admin_url + '/welcome_screens';
            var formData = new FormData($(this)[0]);
            if (id != 0) {
                formData.append('_method', 'PATCH');
                action = config.admin_url + '/welcome_screens/' + id;
            }
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#addEditWelcomeScreensForm .submit-form').prop('disabled', false);
                    $('#addEditWelcomeScreensForm .submit-form').html(lang.save);
                    if (data.type == 'success') {
                        My.toast(data.message);
                        WelcomeScreensGrid.ajax.reload( null, false );
                        WelcomeScreens.empty();  
                        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
                    } else {
                        if (typeof data.errors !== 'undefined') {
                            for (i in data.errors) {
                                var message = data.errors[i];
                                if (i.startsWith('description')) {
                                    var key_arr = i.split('.');
                                    var key_text = key_arr[0] + '[' + key_arr[1] + ']';
                                    i = key_text;
                                }
                                $('[name="' + i + '"]').closest('.form-group').find('.invalid-feedback').html(message).show();
                            }
                        }
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#addEditWelcomeScreensForm .submit-form').prop('disabled', false);
                    $('#addEditWelcomeScreensForm .submit-form').html(lang.save);
                    My.ajax_error_message(xhr);
                },
                dataType: "json",
                type: "POST"
            });
            return false;
        })

    }

    return {
        init: function() {
            init();
        },
        edit: function (t) {
           var id = $(t).attr("data-id");
           My.editForm({
               element: t,
               url: config.admin_url + '/welcome_screens/' + id + '/edit',
               data: {},
               success: function (data) {
                   $('[data-toggle="tooltip"]').tooltip('hide');
                    var model = data.data.model;
                    for (var key in model) {
                        if (key == 'image') {
                            if (model[key] != "") {
                                $('.image-input-wrapper').css('background-image', 'url(' + config.url + '/public/uploads/welcome_screens/' + model[key] + ')');
                                $('#kt_avatar').removeClass();
                                $('#kt_avatar').addClass('image-input image-input-outline image-input-changed');
                                $('[data-action="cancel"]').css('display', 'flex');
                                $('[data-action="remove"]').css('display', 'flex');
                            }
                            continue;
                        }
                       $('[name="'+key+'"]').val(model[key]);
                    }
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
        delete: function(t) {
            if(confirm(lang.are_you_sure_you_want_to_delete_this+'?')){
                var id = $(t).attr("data-id");
                My.deleteForm({
                    element: t,
                    url: config.admin_url + '/welcome_screens/' + id,
                    data: { _method: 'DELETE', _token: $('input[name="_token"]').val() },
                    success: function(data) {
                        WelcomeScreensGrid.ajax.reload( null, false );
                        $('[data-toggle="tooltip"]').tooltip('hide');
                    }
                });
            }
            else{
                return false;
            }  
        },
        empty: function() {
            $('.invalid-feedback').html('');
            $('#id').val(0);
            $('#kt_avatar').removeClass();
            $('#kt_avatar').addClass('image-input image-input-empty image-input-outline');
            $('[data-action="cancel"]').css('display','');
            $('[data-action="remove"]').css('display','none');
            $('.image-input-wrapper').css('background-image','');
            $('input[name="profile_avatar_remove"]').val('');
            $('#image').val('');
            My.emptyForm();
        }
    };

}();

jQuery(document).ready(function() {
    WelcomeScreens.init();
});