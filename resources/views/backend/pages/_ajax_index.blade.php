@extends('backend.layouts.master')
@section('title')
    {{ $pageHeader['title'] }}
@endsection
@section('admin-content')
    @include('backend.layouts.partials.page-header', $pageHeader)
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $pageHeader['singular_name'] }}</h4>
                </div>

                {{-- View --}}
                @if (isset($view_data))
                    <div class="card-body">


                        <div class="row">
                            <div class="col-12">
                                <div class="me-1 mb-1 d-inline-block">
                                    <!-- Button trigger for large size modal -->
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                        data-bs-target="#large">
                                        Create
                                    </button>
                                    <!--Create Modal -->
                                    <div class="modal fade text-left" id="large" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel17" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                                            role="document">
                                            <div class="modal-content" style="overflow: auto;">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel17">Large Modal</h4>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <form method="POST" id="data-insert" enctype="multipart/form-data">
                                                    <div class="alert alert-danger" style="display:none"></div>



                                                    <div class="modal-body">
                                                        @foreach ($insert_fields as $input)
                                                            @include(
                                                                'backend.pages.components._inputs._input_1',
                                                                $input)
                                                        @endforeach


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="bx bx-x d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Close</span>
                                                        </button>
                                                        <button type="submit"
                                                            class="btn btn-primary add_student">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Update Modal --}}
                                    <div class="modal fade text-left" id="large1" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel17" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                                            role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel17">Large Modal</h4>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <form style="overflow: auto;" method="POST" id="data-update"
                                                    enctype="multipart/form-data">
                                                    <div class="modal-body">

                                                        @foreach ($update_fields as $input)
                                                            @include(
                                                                'backend.pages.components._inputs._input_1',
                                                                $input)
                                                        @endforeach
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="bx bx-x d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Close</span>
                                                        </button>
                                                        <button type="submit"
                                                            class="btn btn-primary update_student">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Delete Modal --}}

                                </div>
                            </div>
                        </div>


                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    @foreach ($show_fields as $column)
                                        <td>{{ $column['view_name'] }}</td>
                                    @endforeach
                                    <th>Action</th>


                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($view_data as $value)
                                    <tr id="table-data{{ $value->id }}">
                                        <td>{{ $loop->index + 1 }}</td>
                                        {{--                                        <td>{{ $value->admin->name }}</td> --}}
                                        @foreach ($show_fields as $column)
                                            @if ($column['type'] == 'image')
                                                <td><img style="width:50px;" src="/images/{{ $value->{$column['name']} }}">
                                                </td>
                                            @elseif($column['type'] == 'switch')
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input onclick="activeData({{ $value->id }})"
                                                            {{ $value->{$column['name']} == 1 ? 'checked' : '' }}
                                                            class="form-check-input" type="checkbox">
                                                    </div>
                                                </td>
                                            @else
                                                <td>{{ isset($column['base_array']) ? $value->{$column['base_array']}->{$column['name']} : $value->{$column['name']} }}
                                                </td>
                                            @endif
                                        @endforeach
                                        <td>
                                            @if (Auth::guard('admin')->user()->can($pageHeader['singular_name'] . '.edit'))
                                                <button id="editbtn" value="{{ $value->id }}" class="badge bg-info"
                                                    href="#">Edit</button>
                                            @endif
                                            @if (Auth::guard('admin')->user()->can($pageHeader['singular_name'] . '.delete'))
                                                <a class="badge bg-danger" href="#"
                                                    onclick="deleteData({{ $value->id }})">Delete</a>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                {{-- view --}}

            </div>
        </section>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Show data


            //Insert Data
            $("#data-insert").submit(function(e) {
                e.preventDefault();

                const fd = new FormData(this);
                // $(this).text('Creating..');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ $pageHeader['store_route'] }}   ',
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 422) {

                            $('#large').modal('show');

                            $(".alert").remove();
                            $.each(response.errors, function(key, value) {

                                $('[name=' + key + ']').after(
                                    "<div class='alert alert-danger'>" + value +
                                    "</div>");
                                // jQuery('.alert-danger').append('<p>'+value+'</p>');

                            });
                        } else {
                            var getid = $(".table tbody");
                            getid.prepend('<tr id="table-data' + response.id + '"><td>' +
                                response.id +
                                '</td>@foreach ($show_fields as $column)<td>' + response
                                .{{ $column['name'] }} +
                                '</td>@endforeach<td><button id="editbtn" value="' +
                                response.id +
                                '" class="badge bg-info" >Edit</button><a class="badge bg-danger" href="#"  onclick="deleteData(' +
                                response.id + ')">Delete</a></td></tr>')
                            $('#large').modal('hide');
                            @foreach ($insert_fields as $input)
                                $('#{{ $input['name'] }}').val('');
                            @endforeach
                        }
                        $('.add_student').text('Save');


                    }
                });
            });

            //Edit data

            $(document).on('click', '#editbtn', function(e) {
                e.preventDefault();
                var id = $(this).val();
                console.log(id)
                $('#large1').modal('show');
                $.ajax({
                    type: "GET",
                    url: "{{ $pageHeader['base_url'] }}/" + id + "/edit",
                    success: function(response) {
                        if (response.status == 404) {
                            $('#large1').modal('hide');
                        } else {

                            @foreach ($update_fields as $input)
                                @if ($input['type'] == 'datetime-local')
                                    $('.{{ $input['name'] }}').val(
                                        "{{ date('Y-m-d\TH:i:s', strtotime($input['name'])) }}"
                                        );
                                @else
                                    $('.{{ $input['name'] }}').val(response.student
                                        .{{ $input['name'] }});
                                @endif
                            @endforeach
                            // $('#id').val('id');
                        }
                    }
                });

            });


            //Update Data
            $("#data-update").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                e.preventDefault();
                // $(this).text('Updating..');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var id = $(this).closest("form").find('.id').val();
                console.log(id)


                $.ajax({
                    url: "{{ $pageHeader['base_url'] }}" + id,
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        // console.log(response);
                        if (response.status == 400) {
                            console.log(false);
                        } else {
                            deleteData1(id)
                            var getid = $(".table tbody");
                            getid.prepend('<tr id="table-data' + id + '"><td>' + id +
                                '</td>@foreach ($show_fields as $column)<td>' + response
                                .{{ $column['name'] }} +
                                '</td>@endforeach<td><button id="editbtn" value="' +
                                id +
                                '" class="badge bg-info" >Edit</button><a class="badge bg-danger" href="#"  onclick="deleteData(' +
                                id + ')">Delete</a></td></tr>')
                            $('#large1').modal('hide');
                            $('input').val('');

                        }
                        $('.update_student').text('Save');
                    }
                });

            });
        });

        function deleteData(id) {
            Swal.fire({
                title: "Are you Sure?",
                text: "If confirm data will deleted",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    $.ajax({
                        type: "DELETE",
                        url: "{{ $pageHeader['base_url'] }}/" + id,
                        data: {
                            _token: $("input[name=_token]").val()
                        },
                        success: function(response) {

                            Toast.fire({
                                icon: 'success',
                                title: 'Thank you'
                            })
                            $("#table-data" + id).remove();

                        },
                        error: function(response) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Something went wrong'
                            })
                        },
                    });
                }
            })
        }

        function deleteData1(id) {
            $("#table-data" + id).remove();
        }

        function activeData(id) {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            $.ajax({
                url: "{{ $pageHeader['base_url'] }}/" + id,
                type: "GET",
                data: {
                    _token: $("input[name=_token]").val()
                },
                success: function(response) {

                    Toast.fire({
                        icon: 'success',
                        title: 'Success !'
                    })

                },
                error: function(response) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Opps! Something Wrong.'
                    })
                },
            });


        }
    </script>

@endsection
