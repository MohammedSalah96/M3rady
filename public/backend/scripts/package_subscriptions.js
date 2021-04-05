var PackageSubscriptions = function() {
var PackageSubscriptionsGrid, filterParams;

    var init = function() {
        $.extend(lang, newLang);
        $.extend(config, newConfig);
        handleRecords();
        handleFilterDate();
        handleFilter();
    };

    var handleFilterDate = function(){
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
       
        $('#from').datepicker({
            format: 'yyyy-mm-dd',
            autoclose:true,
            rtl: rtl,
            templates: arrows,
            clearBtn: true,
            todayBtn: "linked"
        }).on('changeDate', function (date) {
            populateEndDate();
        });

        if ($('#from').val()) {
            populateEndDate();
        }

        $('#to').datepicker({
            format: 'yyyy-mm-dd',
            autoclose:true,
            rtl: rtl,
            templates: arrows,
            clearBtn: true,
            todayBtn: "linked"
        });
    }

    var populateEndDate =  function () {
        var date = $('#from').datepicker('getDate');
        date.setDate(date.getDate());
        $('#to').datepicker('setStartDate', date);
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
                }
                
            ],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
                $("#filterForm .submit-form").prop('disabled', false);
                $("#filterForm .submit-form").html(lang.apply);
            },
            'columnDefs': [
                { 
                    className: 'text-center', targets: [0,1,2,3,4,5] 
                }
                
            ],
            "oLanguage": { "sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json' }

        });
    }

    return {
        init: function() {
            init();
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
            $('#id').val(0);
            My.emptyForm();
        }
    };

}();

jQuery(document).ready(function() {
    PackageSubscriptions.init();
});