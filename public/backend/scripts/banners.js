var Banners = function() {
var BannersGrid,image;

    var init = function() {
        $.extend(lang, newLang);
        $.extend(config, newConfig);
        handleRecords();
        handleSubmit();
        kImage = new KTImageInput("kt_avatar");
    };
   
    var handleRecords = function() {
        BannersGrid = $('#kt_datatable').DataTable({
            "processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/banners/data",
                "type": "POST",
                data: { _token: $('input[name="_token"]').val() },
            },
            "columns": [
                {
                    "data": "image",
                    "name": "banners.image",
                    orderable: false,
                    searchable: false
                }, 
                {
                    "data": "active",
                    "name": "banners.active",
                     orderable: false,
                }, 
                {
                    "data": "position",
                    "name": "banners.position"
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
                    className: 'text-center', targets: [0,1,2,3] 
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
        $('#addEditBannersForm').validate({
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

        $('#addEditBannersForm .submit-form').click(function() {

            if ($('#addEditBannersForm').validate().form()) {
                $('#addEditBannersForm .submit-form').prop('disabled', true);
                $('#addEditBannersForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function() {
                    $('#addEditBannersForm').submit();
                }, 1000);
            }
            return false;
        });
        $('#addEditBannersForm input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#addEditBannersForm').validate().form()) {
                    $('#addEditBannersForm .submit-form').prop('disabled', true);
                    $('#addEditBannersForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function() {
                        $('#addEditBannersForm').submit();
                    }, 1000);
                }
                return false;
            }
        });

        $('#addEditBannersForm').submit(function() {
            var id = $('#id').val();
            var action = config.admin_url + '/banners';
            var formData = new FormData($(this)[0]);
            if (id != 0) {
                formData.append('_method', 'PATCH');
                action = config.admin_url + '/banners/' + id;
            }
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#addEditBannersForm .submit-form').prop('disabled', false);
                    $('#addEditBannersForm .submit-form').html(lang.save);
                    if (data.type == 'success') {
                        My.toast(data.message);
                        BannersGrid.ajax.reload( null, false );
                        Banners.empty();  
                    } else {
                        if (typeof data.errors !== 'undefined') {
                            for (i in data.errors) {
                                var message = data.errors[i];
                                $('[name="' + i + '"]').closest('.form-group').find('.invalid-feedback').html(message).show();
                            }
                        }
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#addEditBannersForm .submit-form').prop('disabled', false);
                    $('#addEditBannersForm .submit-form').html(lang.save);
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
               url: config.admin_url + '/banners/' + id + '/edit',
               data: {},
               success: function (data) {
                   $('[data-toggle="tooltip"]').tooltip('hide');
                    var model = data.data.model;
                    for (var key in model) {
                        if (key == 'image') {
                            if (model[key] != "") {
                                $('.image-input-wrapper').css('background-image','url('+config.url+'/public/uploads/Banners/'+model[key]+')');
                                $('#kt_avatar').removeClass();
                                $('#kt_avatar').addClass('image-input image-input-outline image-input-changed');
                                $('[data-action="cancel"]').css('display','flex');
                                $('[data-action="remove"]').css('display','flex');
                            }
                            continue;
                        }
                       $('[name="'+key+'"]').val(model[key]);
                    }
               }
           });
        },
        delete: function(t) {
            if(confirm(lang.are_you_sure_you_want_to_delete_this+'?')){
                var id = $(t).attr("data-id");
                My.deleteForm({
                    element: t,
                    url: config.admin_url + '/banners/' + id,
                    data: { _method: 'DELETE', _token: $('input[name="_token"]').val() },
                    success: function(data) {
                        BannersGrid.ajax.reload( null, false );
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
    Banners.init();
});