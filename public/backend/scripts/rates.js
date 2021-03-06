var Rates = function() {
var RatesGrid, filterParams, status;

    var init = function() {
        $.extend(lang, newLang);
        $.extend(config, newConfig);
        status = config.status
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
            status: status
        }
        if (!jQuery.isEmptyObject(filterParams)) {
            data = filterParams;
        }
        RatesGrid = $('#kt_datatable').DataTable({
            "processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/rates/data",
                "type": "POST",
                data: data,
            },
            "columns": [
                {
                    "data": "company",
                    "name": "companies.company_id"
                },
               {
                    "data": "name",
                    orderable: false
                }, 
                {
                    "data": "created_at",
                    "name": "rates.created_at"
                },
                {
                    "data": "status",
                    "name": "rates.status"
                }, 
                {
                    "data": "score",
                    "name": "rates.score"
                },
                {
                    "data": "comment",
                    "name": "rates.comment"
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
             "order": [[ 2, "desc" ]],
            'columnDefs': [
                { 
                    className: 'text-center', targets: [0,1,2,3,4,5,6] 
                },
                {
                    "width": "30%",
                    "targets": 5
                }
            ],
            "oLanguage": { "sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json' }

        });
    }

    return {
        init: function() {
            init();
        },
         accept: function (t) {
            var id = $(t).data('id');
            $(t).prop('disabled', true);
            $(t).html('<i class="fas fa-circle-notch fa-spin"></i>');
            var acceptIcon = '<span class="svg-icon svg-icon-md">';
            acceptIcon += '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">'
            acceptIcon += '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">';
            acceptIcon += '<rect x="0" y="0" width="24" height="24"/>';
            acceptIcon += '<path d="M4.875,20.75 C4.63541667,20.75 4.39583333,20.6541667 4.20416667,20.4625 L2.2875,18.5458333 C1.90416667,18.1625 1.90416667,17.5875 2.2875,17.2041667 C2.67083333,16.8208333 3.29375,16.8208333 3.62916667,17.2041667 L4.875,18.45 L8.0375,15.2875 C8.42083333,14.9041667 8.99583333,14.9041667 9.37916667,15.2875 C9.7625,15.6708333 9.7625,16.2458333 9.37916667,16.6291667 L5.54583333,20.4625 C5.35416667,20.6541667 5.11458333,20.75 4.875,20.75 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>';
            acceptIcon += '<path d="M2,11.8650466 L2,6 C2,4.34314575 3.34314575,3 5,3 L19,3 C20.6568542,3 22,4.34314575 22,6 L22,15 C22,15.0032706 21.9999948,15.0065399 21.9999843,15.009808 L22.0249378,15 L22.0249378,19.5857864 C22.0249378,20.1380712 21.5772226,20.5857864 21.0249378,20.5857864 C20.7597213,20.5857864 20.5053674,20.4804296 20.317831,20.2928932 L18.0249378,18 L12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.23590829,11 3.04485894,11.3127315 2,11.8650466 Z M6,7 C5.44771525,7 5,7.44771525 5,8 C5,8.55228475 5.44771525,9 6,9 L15,9 C15.5522847,9 16,8.55228475 16,8 C16,7.44771525 15.5522847,7 15,7 L6,7 Z" fill="#000000"/>';
            acceptIcon += '</g>';
            acceptIcon += '</svg>';
            acceptIcon += '</span>'
            action = config.admin_url + '/rates/' + id;
            $.ajax({
                    url: action,
                    data: {
                        _method: 'PATCH',
                        status: 1,
                        _token: $('input[name="_token"]').val()
                    },
                    success: function (data) {
                        $(t).prop('disabled', false);
                        $(t).html(acceptIcon);
                        if (data.type == 'success') {
                            My.toast(data.message);
                            RatesGrid.ajax.reload( null, false );
                            $('[data-toggle="tooltip"]').tooltip('hide');
                        } else {
                            swal.fire({
                                html: data.message,
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                },
                            })
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        $(t).prop('disabled', false);
                        $(t).html(acceptIcon);
                        My.ajax_error_message(xhr);
                    },
                    dataType: "json",
                    type: "post"
            })
        },
        delete: function(t) {
            if(confirm(lang.are_you_sure_you_want_to_delete_this+'?')){
                var id = $(t).attr("data-id");
                My.deleteForm({
                    element: t,
                    url: config.admin_url + '/rates/' + id,
                    data: { _method: 'DELETE', _token: $('input[name="_token"]').val() },
                    success: function(data) {
                        RatesGrid.ajax.reload( null, false );
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
    Rates.init();
});