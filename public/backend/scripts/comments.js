var Comments = function() {
var CommentsGrid, post;

    var init = function() {
        $.extend(lang, newLang);
        $.extend(config, newConfig);
        post = config.post;
        handleRecords();
    };
   
    var handleRecords = function() {
        CommentsGrid = $('#kt_datatable').DataTable({
            "processing": true,
            responsive: true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/comments/data",
                "type": "POST",
                data: { post: post, _token: $('input[name="_token"]').val() },
            },
            "columns": [
                {
                    "data": "comment",
                    "name" : "comments.comment",
                    orderable: false
                }, 
                {
                    "data": "name",
                    orderable: false
                }, 
                {
                    "data": "created_at",
                    "name": "comments.created_at"
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
            "order": [[ 2, "desc" ]],
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
    return {
        init: function() {
            init();
        },
        delete: function(t) {
            if(confirm(lang.are_you_sure_you_want_to_delete_this+'?')){
                var id = $(t).attr("data-id");
                My.deleteForm({
                    element: t,
                    url: config.admin_url + '/comments/' + id,
                    data: { _method: 'DELETE', _token: $('input[name="_token"]').val() },
                    success: function(data) {
                        CommentsGrid.ajax.reload( null, false );
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
    Comments.init();
});