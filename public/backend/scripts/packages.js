var Packages = function() {
var PackagesGrid, parentId,image;

    var init = function() {
        $.extend(lang, newLang);
        $.extend(config, newConfig);
        handleRecords();
        handleSubmit();
    };
   
    var handleRecords = function() {
        PackagesGrid = $('#kt_datatable').DataTable({
            "processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/packages/data",
                "type": "POST",
                data: { _token: $('input[name="_token"]').val() },
            },
            "columns": [
                {
                    "data": "name",
                    "name": "package_translations.name"
                }, 
                {
                    "data": "duration",
                    "name": "packages.duration"
                }, 
                {
                    "data": "price",
                    "name": "packages.price"
                }, 
                {
                    "data": "active",
                    "name": "packages.active",
                     orderable: false,
                }, 
                {
                    "data": "position",
                    "name": "packages.position"
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
                    className: 'text-center', targets: [0,1,2,3,4,5] 
                }
            ],
            "oLanguage": { "sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json' }

        });
    }

    var handleSubmit = function() {
        $('#addEditPackagesForm').validate({
            rules: {
               active: {
                    required: true
                },
                position: {
                    required: true
                },
                duration: {
                    required: true
                },
                price: {
                    required: true
                }
            },
            messages:{
                active:{
                    required: lang.required_rule
                },
                position:{
                    required: lang.required_rule
                },
                duration:{
                    required: lang.required_rule
                },
                price:{
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
            var name = "input[name='name[" + langs[x] + "]']";
            var description = "textarea[name='description[" + langs[x] + "]']";
            $(name).rules('add', {
                required: true
            });
            $(description).rules('add', {
                required: true
            });
        }

        $('#addEditPackagesForm .submit-form').click(function() {

            if ($('#addEditPackagesForm').validate().form()) {
                $('#addEditPackagesForm .submit-form').prop('disabled', true);
                $('#addEditPackagesForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function() {
                    $('#addEditPackagesForm').submit();
                }, 1000);
            }
            return false;
        });
        $('#addEditPackagesForm input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#addEditPackagesForm').validate().form()) {
                    $('#addEditPackagesForm .submit-form').prop('disabled', true);
                    $('#addEditPackagesForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function() {
                        $('#addEditPackagesForm').submit();
                    }, 1000);
                }
                return false;
            }
        });

        $('#addEditPackagesForm').submit(function() {
            var id = $('#id').val();
            var action = config.admin_url + '/packages';
            var formData = new FormData($(this)[0]);
            if (id != 0) {
                formData.append('_method', 'PATCH');
                action = config.admin_url + '/packages/' + id;
            }
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#addEditPackagesForm .submit-form').prop('disabled', false);
                    $('#addEditPackagesForm .submit-form').html(lang.save);
                    if (data.type == 'success') {
                        My.toast(data.message);
                        PackagesGrid.ajax.reload( null, false );
                        Packages.empty();  
                        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
                    } else {
                        if (typeof data.errors !== 'undefined') {
                            for (i in data.errors) {
                                var message = data.errors[i];
                                if (i.startsWith('name') || i.startsWith('description')) {
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
                    $('#addEditPackagesForm .submit-form').prop('disabled', false);
                    $('#addEditPackagesForm .submit-form').html(lang.save);
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
               url: config.admin_url + '/packages/' + id + '/edit',
               data: {},
               success: function (data) {
                   $('[data-toggle="tooltip"]').tooltip('hide');
                    var model = data.data.model;
                    for (var key in model) {
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
                    url: config.admin_url + '/packages/' + id,
                    data: { _method: 'DELETE', _token: $('input[name="_token"]').val() },
                    success: function(data) {
                        PackagesGrid.ajax.reload( null, false );
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
            My.emptyForm();
        }
    };

}();

jQuery(document).ready(function() {
    Packages.init();
});