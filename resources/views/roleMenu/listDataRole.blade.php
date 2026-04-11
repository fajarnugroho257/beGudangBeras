@extends('template.base.base')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $title }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            @session('success')
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endsession
            <div id="errors"></div>
            <!-- Default box -->
            <div class="card">
                <div class="card-header ">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('roleMenu') }}" class="btn btn-block btn-success"><i class="fa fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th width="10%">Heading ID</th>
                                <th>Nama Induk</th>
                                <th>Nama Menu</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rs_role_menu as $key => $role_menu)
                                <tr>
                                    <td class="text-center"><b>{{ $role_menu->heading->app_heading_id }}</b></td>
                                    <td>{{ $role_menu->heading->app_heading_name }}
                                    </td>
                                    <td>{{ $role_menu->menu_name }}</td>
                                    <td class="text-center">
                                        @if ($role_menu->role_menu_id == null)
                                            <input type="checkbox" class="form-control check-menu"
                                                data-menu_id="{{ $role_menu->menu_id }}">
                                        @else
                                            <input type="checkbox" class="form-control check-menu"
                                                data-menu_id="{{ $role_menu->menu_id }}" checked>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                    </ul>
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            //
            $('input[type="checkbox"]').click(function() {
                var menu_id = $(this).data('menu_id');
                if ($(this).is(":checked")) {
                    var status = 'tambah';
                } else if ($(this).is(":not(:checked)")) {
                    var status = 'hapus';
                }
                let token   = '{{ csrf_token() }}';
                $.ajax({
                    url: `{{route('tambahRoleMenu')}}`,
                    type: "POST",
                    cache: false,
                    data: {
                        "role_id": `{{$detail->role_id}}`,
                        "menu_id": menu_id,
                        "status": status,
                        "_token": token
                    },
                    success: function(response) {
                        $('#errors').html('');
                        alert(response.message);
                    },
                    error: function(error) {
                        var data_error = error.responseJSON.errors;
                        console.log(data_error);
                        var errorString = '';
                        var errorString = '<div class="alert alert-danger"> <ul>';
                        $.each( data_error, function( key, value) {
                            errorString += '<li>' + value + '</li>';
                        });
                        errorString += '</ul></div>';
                        $('#errors').html(errorString);
                    }

                });
            });
        });
    </script>
@endsection
