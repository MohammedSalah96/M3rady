var Locations = function() {
var LocationsGrid, parentId,image;

    var init = function() {
        $.extend(lang, newLang);
        $.extend(config, newConfig);
        parentId = config.parentId;
        handleRecords();
        handleSubmit();
    };
   
    var handleRecords = function() {
        var columns = [
                {
                    "data": "name",
                    "name": "location_translations.name"
                }, 
                {
                    "data": "active",
                    "name": "locations.active",
                     orderable: false,
                }, 
                {
                    "data": "position",
                    "name": "locations.position"
                }, 
                {
                    "data": "options",
                    orderable: false,
                    searchable: false
                }
        ];
        var targets = [0,1,2,3];
        if (parentId == 0) {
            columns.splice(1, 0, 
                {
                    "data": "dial_code",
                    "name": "locations.dial_code"
                });
                targets = [0,1,2,3,4];
        }
        LocationsGrid = $('#kt_datatable').DataTable({
            "processing": true,
            responsive: true,
            "serverSide": true,
            stateSave: true,
            "ajax": {
                "url": config.admin_url + "/locations/data",
                "type": "POST",
                data: { parent_id: parentId, _token: $('input[name="_token"]').val() },
            },
            "columns": columns,
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
            'columnDefs': [
                { 
                    className: 'text-center', targets: targets
                }
            ],
            "oLanguage": { "sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json' }

        });
    }

    var handleSubmit = function() {
        $('#addEditLocationsForm').validate({
            rules: {
               active: {
                    required: true
                },
                position: {
                    required: true
                },
                dial_code: {
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
                dial_code:{
                    required: lang.required_rule
                },
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
            $(name).rules('add', {
                required: true
            });
        }

        $('#addEditLocationsForm .submit-form').click(function() {

            if ($('#addEditLocationsForm').validate().form()) {
                $('#addEditLocationsForm .submit-form').prop('disabled', true);
                $('#addEditLocationsForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function() {
                    $('#addEditLocationsForm').submit();
                }, 1000);
            }
            return false;
        });
        $('#addEditLocationsForm input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#addEditLocationsForm').validate().form()) {
                    $('#addEditLocationsForm .submit-form').prop('disabled', true);
                    $('#addEditLocationsForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function() {
                        $('#addEditLocationsForm').submit();
                    }, 1000);
                }
                return false;
            }
        });

        $('#addEditLocationsForm').submit(function() {
            var id = $('#id').val();
            var action = config.admin_url + '/locations';
            var formData = new FormData($(this)[0]);
            if (id != 0) {
                formData.append('_method', 'PATCH');
                action = config.admin_url + '/locations/' + id;
            }
            formData.append('parent_id', parentId);
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#addEditLocationsForm .submit-form').prop('disabled', false);
                    $('#addEditLocationsForm .submit-form').html(lang.save);
                    if (data.type == 'success') {
                        My.toast(data.message);
                        LocationsGrid.ajax.reload( null, false );
                        Locations.empty();  
                    } else {
                        if (typeof data.errors !== 'undefined') {
                            for (i in data.errors) {
                                var message = data.errors[i];
                                if (i.startsWith('name')) {
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
                    $('#addEditLocationsForm .submit-form').prop('disabled', false);
                    $('#addEditLocationsForm .submit-form').html(lang.save);
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
               url: config.admin_url + '/locations/' + id + '/edit',
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
               }
           });
        },
        delete: function(t) {
            if(confirm(lang.are_you_sure_you_want_to_delete_this+'?')){
                var id = $(t).attr("data-id");
                My.deleteForm({
                    element: t,
                    url: config.admin_url + '/locations/' + id,
                    data: { _method: 'DELETE', _token: $('input[name="_token"]').val() },
                    success: function(data) {
                        LocationsGrid.ajax.reload( null, false );
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
    Locations.init();
});