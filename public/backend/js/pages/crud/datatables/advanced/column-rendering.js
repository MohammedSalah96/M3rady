"use strict";
var KTDatatablesAdvancedColumnRendering = {
    init: function () {
        $("#kt_datatable").DataTable({
            responsive: !0,
            paging: !0,
            columnDefs: [{
                targets: 0,
                title: "Agent",
                render: function (t, a, e, s) {
                    var l = KTUtil.getRandomInt(1, 14);
                    return l > 8 ? '\n                                <div class="d-flex align-items-center">\n                                    <div class="symbol symbol-50 flex-shrink-0">\n                                        <img src="assets/media/users/100_' + l + '.jpg" alt="photo">\n                                    </div>\n                                    <div class="ml-3">\n                                        <span class="text-dark-75 font-weight-bold line-height-sm d-block pb-2">' + e[2] + '</span>\n                                        <a href="#" class="text-muted text-hover-primary">' + e[3] + "</a>\n                                    </div>\n                                </div>" : '\n                                <div class="d-flex align-items-center">\n                                    <div class="symbol symbol-50 symbol-light-' + ["success", "light", "danger", "success", "warning", "dark", "primary", "info"][KTUtil.getRandomInt(0, 7)] + '" flex-shrink-0">\n                                        <div class="symbol-label font-size-h5">' + e[2].substring(0, 1) + '</div>\n                                    </div>\n                                    <div class="ml-3">\n                                        <span class="text-dark-75 font-weight-bold line-height-sm d-block pb-2">' + e[2] + '</span>\n                                        <a href="#" class="text-muted text-hover-primary">' + e[3] + "</a>\n                                    </div>\n                                </div>"
                }
            }, {
                targets: 1,
                render: function (t, a, e, s) {
                    return '<a class="text-dark-50 text-hover-primary" href="mailto:' + t + '">' + t + "</a>"
                }
            }, {
                targets: -1,
                title: "Actions",
                orderable: !1,
                render: function (t, a, e, s) {
                    return '\t\t\t\t\t\t\t<div class="dropdown dropdown-inline">\t\t\t\t\t\t\t\t<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">\t                                <i class="la la-cog"></i>\t                            </a>\t\t\t\t\t\t\t  \t<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\t\t\t\t\t\t\t\t\t<ul class="nav nav-hoverable flex-column">\t\t\t\t\t\t\t    \t\t<li class="nav-item"><a class="nav-link" href="#"><i class="nav-icon la la-edit"></i><span class="nav-text">Edit Details</span></a></li>\t\t\t\t\t\t\t    \t\t<li class="nav-item"><a class="nav-link" href="#"><i class="nav-icon la la-leaf"></i><span class="nav-text">Update Status</span></a></li>\t\t\t\t\t\t\t    \t\t<li class="nav-item"><a class="nav-link" href="#"><i class="nav-icon la la-print"></i><span class="nav-text">Print</span></a></li>\t\t\t\t\t\t\t\t\t</ul>\t\t\t\t\t\t\t  \t</div>\t\t\t\t\t\t\t</div>\t\t\t\t\t\t\t<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" title="Edit details">\t\t\t\t\t\t\t\t<i class="la la-edit"></i>\t\t\t\t\t\t\t</a>\t\t\t\t\t\t\t<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" title="Delete">\t\t\t\t\t\t\t\t<i class="la la-trash"></i>\t\t\t\t\t\t\t</a>\t\t\t\t\t\t'
                }
            }, {
                targets: 4,
                render: function (t, a, e, s) {
                    var l = {
                        1: {
                            title: "Pending",
                            class: "label-light-primary"
                        },
                        2: {
                            title: "Delivered",
                            class: " label-light-danger"
                        },
                        3: {
                            title: "Canceled",
                            class: " label-light-primary"
                        },
                        4: {
                            title: "Success",
                            class: " label-light-success"
                        },
                        5: {
                            title: "Info",
                            class: " label-light-info"
                        },
                        6: {
                            title: "Danger",
                            class: " label-light-danger"
                        },
                        7: {
                            title: "Warning",
                            class: " label-light-warning"
                        }
                    };
                    return void 0 === l[t] ? t : '<span class="label label-lg font-weight-bold' + l[t].class + ' label-inline">' + l[t].title + "</span>"
                }
            }, {
                targets: 5,
                render: function (t, a, e, s) {
                    var l = {
                        1: {
                            title: "Online",
                            state: "danger"
                        },
                        2: {
                            title: "Retail",
                            state: "primary"
                        },
                        3: {
                            title: "Direct",
                            state: "success"
                        }
                    };
                    return void 0 === l[t] ? t : '<span class="label label-' + l[t].state + ' label-dot mr-2"></span><span class="font-weight-bold text-' + l[t].state + '">' + l[t].title + "</span>"
                }
            }]
        }), $("#kt_datatable_search_status").on("change", (function () {
            datatable.search($(this).val().toLowerCase(), "Status")
        })), $("#kt_datatable_search_type").on("change", (function () {
            datatable.search($(this).val().toLowerCase(), "Type")
        })), $("#kt_datatable_search_status, #kt_datatable_search_type").selectpicker()
    }
};
jQuery(document).ready((function () {
    KTDatatablesAdvancedColumnRendering.init()
}));