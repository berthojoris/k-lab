@extends('layouts.template_admin')

@section('content')
<div class="layout-px-spacing">
    @include('flash::message')

    <div class="row layout-top-spacing">
        <div class="col-lg-6 col-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-6 col-md-12 col-sm-12 col-12">
                            <h4>Attach Permissions To Role</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table id="tbl_permission_to_role" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Guard</th>
                                    <th>Choose</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12  layout-spacing roleData">
            <form action="{{ route('permission_to_role_add') }}" method="POST">
                @csrf
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-6 col-md-12 col-sm-12 col-12">
                                <h4>Permission</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <input type="hidden" name="id_role" id="id_role">
                        <div id="dataPermission" class="parmissionPanel">
                            <div class="col-xl-12 mx-auto">
                                <blockquote class="blockquote">
                                   <p class="d-inline">Choose your role first to get all permission list</p>
                                    <small>Administrator</small>
                                </blockquote>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-success d-none btnSubmit" value="Submit">
                        <div class="col-md-12 text-center d-none" id="loadingPanel">
                            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('library_css')
<link rel="stylesheet" type="text/css" href="{{ secure_asset('template/plugins/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ secure_asset('template/plugins/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ secure_asset('template/plugins/sweetalerts/sweetalert2.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ secure_asset('template/plugins/sweetalerts/sweetalert.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ secure_asset('template/assets/css/components/custom-sweetalert.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ secure_asset('template/assets/css/forms/theme-checkbox-radio.css') }}">
<link rel="stylesheet" type="text/css" href="{{ secure_asset('template/assets/css/forms/switches.css') }}">
@endpush

@push('library_js')
<script src="{{ secure_asset('template/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ secure_asset('template/plugins/sweetalerts/sweetalert2.min.js') }}"></script>
@endpush

@push('page_css')
<link rel="stylesheet" href="{{ secure_asset('template/custom.css') }}">
@endpush

@push('page_js')
<script>
$(document).ready(function() {
    $('#tbl_permission_to_role').DataTable({
        processing: true,
        serverSide: true,
        ajax: route('role_data'),
        "oLanguage": {
            "oPaginate": {
                "sPrevious": '<i class="fas fa-arrow-circle-left dtIconSize"></i>',
                "sNext": '<i class="fas fa-arrow-circle-right dtIconSize"></i>'
            },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
            "sProcessing": '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>',
        },
        order: [
            [0, "desc"]
        ],
        columns: [
            {
                data: 'id',
                visible: false,
                searchable: false
            },
            {
                data: 'name',
            },
            {
                data: 'guard_name',
            },
            {
                data: 'id',
                render: function(data, type, row) {
                    return '<div class="icon-container"><a href="'+route('get_role_has_permission', data)+'" data-id="'+data+'" class="confirm"><i class="fas fa-link"></i><span class="icon-name"></span></a>';
                },
                searchable: false,
                sortable: false,
            }
        ]
    })
})

$('body').on('click', '.checkboxRole', function(e) {
    var id = $(this).find('.new-control-input').val()
})

$('#tbl_permission_to_role tbody').on('click', '.confirm', function(e) {
    e.preventDefault()
    var id = $(this).attr('data-id')
    $("#id_role").val(id)

    $("#dataPermission").empty()
    $("div.alert").hide()
    $("#dataPermission, .btnSubmit").addClass("d-none")

    $("#loadingPanel").removeClass("d-none")

    axios.get(route('get_permission_by_role', id)).then(function(response) {
        $.each(response.data.roles, function(key, value) {
            if(value.checked_if == "checked") {
                var html = `
                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <div class="n-chk align-self-end">
                                <label class="new-control new-checkbox checkbox-danger checkboxRole">
                                    <input type="checkbox" class="new-control-input" name="permissionid[]" value="`+value.id+`" checked>
                                    <span class="new-control-indicator"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Checkbox" aria-label="checkbox" readonly value="`+value.name+`">
                </div>
                `;
            } else {
                var html = `
                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <div class="n-chk align-self-end">
                                <label class="new-control new-checkbox checkbox-danger checkboxRole">
                                    <input type="checkbox" class="new-control-input" name="permissionid[]" value="`+value.id+`">
                                    <span class="new-control-indicator"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Checkbox" aria-label="checkbox" readonly value="`+value.name+`">
                </div>
                `;
            }

            $("#dataPermission").append(html)
        });

        $("#dataPermission").animate({ scrollTop: 0 }, "fast")
        $("#dataPermission, .btnSubmit").removeClass("d-none");
        $("#loadingPanel").addClass("d-none");

    })
    .catch(function(error) {
        $("#loadingPanel").addClass("d-none")
        swal("Error load user roles! Please refresh the page and try again", error.response.data.output, "error")
        if (error.response) {
            console.log(error.response.data)
            console.log(error.response.status)
            console.log(error.response.headers)
        } else if (error.request) {
            console.log(error.request)
        } else {
            console.log('Error', error.message)
        }
    })
})
</script>
@endpush