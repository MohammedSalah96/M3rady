var PackageSubscriptions = function() {
var PackageSubscriptionsGrid, filterParams;

    var init = function() {
        $.extend(lang, newLang);
        $.extend(config, newConfig);
        handleRecords();
        handleDateRange('from','to');
        handleDateRange('start_date','end_date');
        handleMultiSelect();
        handleFilter();
        handleSubscriptionTypeChange();
        handleSubmit();
       
    };

    var handleMultiSelect = function(){
        $(".selectpicker").selectpicker({
            noneSelectedText : lang.choose,
        });

    }
    var handleSubscriptionTypeChange = function(){
        $('#addSubscriptionForm #type').on('change',function(e){
            if (e.target.value == 'trial') {
                $('#addSubscriptionForm #subscription-package').hide();
                $('#addSubscriptionForm #trial-duration').show();
            }else if (e.target.value == 'subscription'){
                $('#addSubscriptionForm #subscription-package').show();
                $('#addSubscriptionForm #trial-duration').hide();
            }else{
                $('#addSubscriptionForm #trial-duration').hide();
                $('#addSubscriptionForm #subscription-package').hide();
            }
        })
    }
    
    var handleDateRange = function(from_date , to_date){
        var arrows,rtl;
        if (config.lang_code == 'rtl') {
            rtl = true;
            arrows = {
                leftArrow: '<i class="la la-angle-right"></i>',
                rightArrow: '<i class="la la-angle-left"></i>'
            }
        } else {
            rtl = false;
            arrows = {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }
       
        $('#'+from_date).datepicker({
            format: 'yyyy-mm-dd',
            autoclose:true,
            rtl: rtl,
            templates: arrows,
            clearBtn: true,
            todayBtn: "linked"
        }).on('changeDate', function (date) {
            populateEndDate(from_date ,to_date);
        });

        if ($('#'+from_date).val()) {
            populateEndDate(from_date ,to_date);
        }

        $('#'+to_date).datepicker({
            format: 'yyyy-mm-dd',
            autoclose:true,
            rtl: rtl,
            templates: arrows,
            clearBtn: true,
            todayBtn: "linked"
        });
    }

    var populateEndDate =  function (from_date, to_date) {
        var date = $('#'+from_date).datepicker('getDate');
        date.setDate(date.getDate());
        $('#'+to_date).datepicker('setStartDate', date);
    }

    var handleFilter = function () {
       $('#filterForm .submit-form').on('click', function () {
            $(this).prop('disabled', true);
            $(this).html('<i class="fas fa-circle-notch fa-spin"></i>');
            setTimeout(() => {
                var data = $("#filterForm").serializeArray();
                filterParams = {};
                if (data.length > 0) {
                    $.each(data, function (i, field) {
                        var name = field.name;
                        var value = field.value;
                        if (value) {
                            filterParams[name] = value;
                        }
                    });
                }
                $('#kt_datatable').DataTable().destroy();
                $('#kt_datatable tbody').empty();
                handleRecords();
            }, 2000);
            
            return false;
        });
    }
   
    var handleRecords = function() {
        var data = {
            _token: $('input[name="_token"]').val(),
        }
        if (!jQuery.isEmptyObject(filterParams)) {
            data = filterParams;
        }
        PackageSubscriptionsGrid = $('#kt_datatable').DataTable({
            "processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/package_subscriptions/data",
                "type": "POST",
                data: data,
            },
            "columns": [
                {
                    "data": "company_id",
                    "name" : "company_details.company_id"
                }, 
                {
                    "data": "type"
                }, 
                {
                    "data": "package",
                    "name" : "package_translations.name"
                }, 
                {
                    "data": "price",
                    "name" : "package_subscriptions.price"
                },
                {
                    "data": "duration",
                    "name" : "package_subscriptions.duration"
                },
                {
                    "data": "start_date",
                    "name" : "package_subscriptions.start_date"
                },
                {
                    "data": "end_date",
                    "name" : "package_subscriptions.end_date"
                }, 
                {
                    "data": "options",
                    orderable: false,
                    searchable: false
                }
                
            ],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
                $("#filterForm .submit-form").prop('disabled', false);
                $("#filterForm .submit-form").html(lang.apply);
            },
            'columnDefs': [
                { 
                    className: 'text-center', targets: [0,1,2,3,4,5,6,7] 
                }
                
            ],
            "oLanguage": { "sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json' }

        });
    }

    var handleSubmit = function () {
        $('#addSubscriptionForm').validate({
            rules: {
                company: {
                    required: true
                },
                type: {
                    required: true
                },
                package: {
                    required: true
                },
                start_date: {
                    required: true
                },
                end_date: {
                    required: true
                },
            },
            messages:{
                company:{
                    required: lang.required_rule
                },
                type:{
                    required: lang.required_rule
                },
                package:{
                    required: lang.required_rule
                },
                start_date:{
                    required: lang.required_rule 
                },
                end_date:{
                    required: lang.required_rule 
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
        $('#addSubscription .submit-form').click(function () {
            if ($('#addSubscriptionForm').validate().form()) {
                $('#addSubscription .submit-form').prop('disabled', true);
                $('#addSubscription .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                setTimeout(function () {
                    $('#addSubscriptionForm').submit();
                }, 1000);

            }
            return false;
        });
        $('#addSubscriptionForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#addSubscriptionForm').validate().form()) {
                    $('#addSubscription .submit-form').prop('disabled', true);
                    $('#addSubscription .submit-form').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    setTimeout(function () {
                        $('#addSubscriptionForm').submit();
                    }, 1000);
                }
                return false;
            }
        });



        $('#addSubscriptionForm').submit(function () {
           
            var formData = new FormData($(this)[0]);
            var action = config.admin_url + '/package_subscriptions';
            
            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#addSubscription .submit-form').prop('disabled', false);
                    $('#addSubscription .submit-form').html(lang.save);

                    if (data.type == 'success')
                    {
                        My.toast(data.message);
                        PackageSubscriptionsGrid.ajax.reload();
                        $('#addSubscription').modal('hide');
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
                    $('#addSubscription .submit-form').prop('disabled', false);
                    $('#addSubscription .submit-form').html(lang.save);
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
        add: function () {
            PackageSubscriptions.empty();
            $('#addSubscription').modal('show');
        },
        delete: function(t) {
            if(confirm(lang.are_you_sure_you_want_to_delete_this+'?')){
                var id = $(t).attr("data-id");
                My.deleteForm({
                    element: t,
                    url: config.admin_url + '/package_subscriptions/' + id,
                    data: { _method: 'DELETE', _token: $('input[name="_token"]').val() },
                    success: function(data) {
                        PackageSubscriptionsGrid.ajax.reload( null, false );
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
            $('#addSubscriptionForm #start_date').val('');
            $('#addSubscriptionForm #end_date').val('');
            $('#addSubscriptionForm #company').find('option').eq(0).prop('selected', true);
            $('#addSubscriptionForm #type').find('option').eq(0).prop('selected', true);
            $('#addSubscriptionForm #package').find('option').eq(0).prop('selected', true);
            $('#addSubscriptionForm #trial-duration').hide();
            $('#addSubscriptionForm #subscription-package').hide();
            //My.emptyForm();
        }
    };

}();

jQuery(document).ready(function() {
    PackageSubscriptions.init();
});