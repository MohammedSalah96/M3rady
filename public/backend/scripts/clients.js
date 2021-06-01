var Clients = function() {
var ClientsGrid, type,image;

    var init = function() {
        $.extend(lang, newLang);
        $.extend(config, newConfig);
        type = config.type;
        handleRecords();
        handleSubmit();
        handleCountryChange();
        kImage = new KTImageInput("kt_avatar");
    };

   var handleCountryChange = function () {
        $('#country').on('change', function (e) {
            var data = {};
            var country = $(this).val();
            $('#city').html('<option selected value="">' + lang.choose_city + '</option>');
            if (country) {
                $.ajax({
                    url: config.admin_url + '/get_locations/' + country,
                    data: data,
                    success: function (data) {
                        var options = '';
                        var cities = data.data.cities;
                       if (cities.length != 0) {
                           for (var i = 0; i < cities.length; i++) {
                               var item = cities[i];
                               options += '<option value="' + item.id + '">' + item.name + '</option>';
                           }
                           $('#city').append(options);
                       }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        My.ajax_error_message(xhr);
                    },
                    dataType: "json",
                    type: "GET"
                });
            }
        });
    }
    var handleRecords = function() {
        ClientsGrid = $('#kt_datatable').DataTable({
            "processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/clients/data",
                "type": "POST",
                data: { type: type, _token: $('input[name="_token"]').val() },
            },
            "columns": [
                {
                    "data": "name",
                    "name": "users.name"
                },
                {
                    "data": "email",
                    "name": "users.email"
                }, 
                {
                    "data": "mobile",
                    "name": "users.mobile"
                },
                {
                    "data": "country",
                    "name": "country_translations.name"
                },
                 {
                    "data": "city",
                    "name": "city_translations.name"
                },
                {
                    "data": "active",
                    "name": "users.active",
                     orderable: false,
                }, 
                {
                    "data": "created_at",
                    "name": "users.created_at"
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
                [6, "desc"]
            ],
            'columnDefs': [
                { 
                    className: 'text-center', targets: [0,1,2,3,4,5] 
                },
                {
                    "width": "20%",
                    "targets": 1
                }
            ],
            "oLanguage": { "sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json' }

        });
    }

    var handleSubmit = function() {
        $('#addEditClientsForm').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email:true
                },
                mobile: {
                    required: true
                },
                country: {
                    required: true
                },
                city: {
                    required: true
                },
                active: {
                    required: true
                },
                image:{
                    extension: "png|jpeg|jpg"
                }
            },
            messages:{
                name:{
                    required: lang.required_rule
                },
                email:{
                    required: lang.required_rule
                },
                mobile:{
                    required: lang.required_rule
                },
                country:{
                    required: lang.required_rule
                },
                city:{
                    required: lang.required_rule
                },
                active:{
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

        $('#addEditClientsForm .submit-form').click(function() {

            if ($('#addEditClientsForm').validate().form()) {
                $('#addEditClientsForm .submit-form').prop('disabled', true);
                $('#addEditClientsForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function() {
                    $('#addEditClientsForm').submit();
                }, 1000);
            }
            return false;
        });
        $('#addEditClientsForm input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#addEditClientsForm').validate().form()) {
                    $('#addEditClientsForm .submit-form').prop('disabled', true);
                    $('#addEditClientsForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function() {
                        $('#addEditClientsForm').submit();
                    }, 1000);
                }
                return false;
            }
        });

        $('#addEditClientsForm').submit(function() {
            var id = $('#id').val();
            var action = config.admin_url + '/clients';
            var formData = new FormData($(this)[0]);
            if (id != 0) {
                formData.append('_method', 'PATCH');
                action = config.admin_url + '/clients/' + id;
            }
            formData.append('type', type);
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#addEditClientsForm .submit-form').prop('disabled', false);
                    $('#addEditClientsForm .submit-form').html(lang.save);
                    if (data.type == 'success') {
                        My.toast(data.message);
                        ClientsGrid.ajax.reload( null, false );
                        Clients.empty();  
                        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
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
                    $('#addEditClientsForm .submit-form').prop('disabled', false);
                    $('#addEditClientsForm .submit-form').html(lang.save);
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
               url: config.admin_url + '/clients/' + id + '/edit',
               data: {},
               success: function (data) {
                   $('[data-toggle="tooltip"]').tooltip('hide');
                    var model = data.data.model;
                    var city = model['city_id'];
                    for (var key in model) {
                        if (key == 'image') {
                            if (model[key] != "") {
                                $('.image-input-wrapper').css('background-image','url('+config.url+'/public/uploads/users/'+model[key]+')');
                                $('#kt_avatar').removeClass();
                                $('#kt_avatar').addClass('image-input image-input-outline image-input-changed');
                                $('[data-action="cancel"]').css('display','flex');
                                $('[data-action="remove"]').css('display','flex');
                            }
                            continue;
                        }else if(key == 'password'){
                            continue;
                        }
                        else if (key == 'country_id' || key == 'city_id') {
                            $('[name="'+key.replace('_id', '')+'"]').val(model[key]);
                            if (key == 'country_id') {
                                    $('#city').html('<option selected value="">' + lang.choose_city + '</option>');
                                    $.ajax({
                                        url: config.admin_url + '/get_locations/' + model[key],
                                        data: data,
                                        success: function (data) {
                                            var options = '';
                                            var cities = data.data.cities;
                                            if (cities.length != 0) {
                                                for (var i = 0; i < cities.length; i++) {
                                                    var item = cities[i];
                                                    var selected = "";
                                                    if (item.id == city) {
                                                        selected = "selected";
                                                    }
                                                    options += '<option value="' + item.id + '" ' + selected + '>' + item.name + '</option>';
                                                }
                                                $('#city').append(options);
                                            }
                                        },
                                        error: function (xhr, textStatus, errorThrown) {
                                            My.ajax_error_message(xhr);
                                        },
                                        dataType: "json",
                                        type: "GET"
                                    });
                            }
                            continue;
                        }
                       $('[name="'+key+'"]').val(model[key]);
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
                    url: config.admin_url + '/clients/' + id,
                    data: { _method: 'DELETE', _token: $('input[name="_token"]').val() },
                    success: function(data) {
                        ClientsGrid.ajax.reload( null, false );
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
            $('#country').find('option').eq(0).prop('selected', true);
            $('#city').html('<option selected value="">' + lang.choose_city + '</option>');
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
    Clients.init();
});