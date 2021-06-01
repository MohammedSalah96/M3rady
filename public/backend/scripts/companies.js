var Companies = function() {
var CompaniesGrid, type, subCount = 0;

    var init = function() {
        $.extend(lang, newLang);
        $.extend(config, newConfig);
        type = config.type;
        handleRecords();
        handleSubmit();
        handleCountryChange();
        handleMultiSelect();
        kImage = new KTImageInput("kt_avatar");
    };

    var handleMultiSelect = function(){
        $(".selectpicker").selectpicker({
            //actionsBox: true,
            noneSelectedText : lang.choose,
            multipleSeparator : ' - '
        });

        $('#categories').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            var selected = $('#categories').val();
            var parent = $('.selectpicker option').eq(clickedIndex).data('parent');
            if (parent == 1) {
                if (isSelected == false) {
                    if (subCount == 4) {
                        $("#categories option").each(function()
                        {
                            $(this).attr('disabled',false);
                        });
                        $('#categories').selectpicker('refresh');
                    }
                    subCount -= 1;
                }else{
                    subCount += 1;
                    if (subCount == 4) {
                        $("#categories option").each(function()
                        {
                            if(jQuery.inArray($(this).val(), selected) == -1){
                                $(this).attr('disabled',true);
                            }
                        });
                        $('#categories').selectpicker('refresh');
                    }
                }
            }
        });

    }


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
        CompaniesGrid = $('#kt_datatable').DataTable({
            "processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/companies/data",
                "type": "POST",
                data: { type: type, _token: $('input[name="_token"]').val() },
            },
            "columns": [
                {
                    "data": "company_id",
                    "name": "company_details.company_id"
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
                [5, "desc"]
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
        $('#addEditCompaniesForm').validate({
            rules: {
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
                },
                company_id: {
                    required: true
                },
                name_ar: {
                    required: true
                },
                name_en: {
                    required: true
                },
                description: {
                    required: true
                }
            },
            messages:{
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
                company_id:{
                    required: lang.required_rule
                },
                name_ar:{
                    required: lang.required_rule
                },
                name_en:{
                    required: lang.required_rule
                },
                description:{
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

        $('#addEditCompaniesForm .submit-form').click(function() {

            if ($('#addEditCompaniesForm').validate().form()) {
                $('#addEditCompaniesForm .submit-form').prop('disabled', true);
                $('#addEditCompaniesForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function() {
                    $('#addEditCompaniesForm').submit();
                }, 1000);
            }
            return false;
        });
        $('#addEditCompaniesForm input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#addEditCompaniesForm').validate().form()) {
                    $('#addEditCompaniesForm .submit-form').prop('disabled', true);
                    $('#addEditCompaniesForm .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function() {
                        $('#addEditCompaniesForm').submit();
                    }, 1000);
                }
                return false;
            }
        });

        $('#addEditCompaniesForm').submit(function() {
            var id = $('#id').val();
            var action = config.admin_url + '/companies';
            var formData = new FormData($(this)[0]);
            if (id != 0) {
                formData.append('_method', 'PATCH');
                action = config.admin_url + '/companies/' + id;
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
                    $('#addEditCompaniesForm .submit-form').prop('disabled', false);
                    $('#addEditCompaniesForm .submit-form').html(lang.save);
                    if (data.type == 'success') {
                        My.toast(data.message);
                        CompaniesGrid.ajax.reload( null, false );
                        Companies.empty();  
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
                    $('#addEditCompaniesForm .submit-form').prop('disabled', false);
                    $('#addEditCompaniesForm .submit-form').html(lang.save);
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
               url: config.admin_url + '/companies/' + id + '/edit',
               data: {},
               success: function (data) {
                   $('[data-toggle="tooltip"]').tooltip('hide');
                    var model = data.data.model;
                    var details = data.data.details;
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
                        else if (key == 'categories') {
                            var selected = model[key];
                            console.log(selected);
                            $('#categories').selectpicker('val', selected);
                            $('#categories').selectpicker('render');
                            subCount = model['subCategoryCount'];
                            if (subCount == 4) {
                                $("#categories option").each(function()
                                {
                                    if(jQuery.inArray(parseInt($(this).val()), selected) == -1){
                                        $(this).attr('disabled',true);
                                    }
                                });
                            }
                            $('#categories').selectpicker('refresh');
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

                    for (var key in details) {
                        if (key == 'id') {
                            continue;
                        }
                       $('[name="'+key+'"]').val(details[key]);
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
                    url: config.admin_url + '/companies/' + id,
                    data: { _method: 'DELETE', _token: $('input[name="_token"]').val() },
                    success: function(data) {
                        CompaniesGrid.ajax.reload( null, false );
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
            $('#main_category').find('option').eq(0).prop('selected', true);
            $('#sub_category').html('<option selected value="">' + lang.choose_category + '</option>');
            $('#kt_avatar').removeClass();
            $('#kt_avatar').addClass('image-input image-input-empty image-input-outline');
            $('[data-action="cancel"]').css('display','');
            $('[data-action="remove"]').css('display','none');
            $('.image-input-wrapper').css('background-image','');
            $('input[name="profile_avatar_remove"]').val('');
            $('#image').val('');
            $('.selectpicker').selectpicker('deselectAll');
            subCount = 0;
            My.emptyForm();
        }
    };

}();

jQuery(document).ready(function() {
    Companies.init();
});