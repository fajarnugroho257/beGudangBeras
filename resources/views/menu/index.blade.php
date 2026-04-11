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
                {{session('success')}}
            </div>
            @endsession
            @session('error')
            <div class="alert alert-danger">
                {{session('error')}}
            </div>
            @endsession
            <!-- Default box -->
            <div class="card">
                <div class="card-header ">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{route('tambahMenuApp')}}" class="btn btn-block btn-success"><i class="fa fa-plus"></i> Tambah</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 10px">No</th>
                                <th>Nama Child Menu</th>
                                <th>Nama Induk Menu</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rs_menu as $key => $menu )
                                <tr>
                                    <td class="text-center">{{ $rs_menu->firstItem() + $key }}</td>
                                    <td>{{ $menu->menu_name }}</td>
                                    <td>{{ $menu->heading->app_heading_name }}</td>
                                    <td class="text-center">
                                        <a href="{{route('updateMenuApp', [$menu->menu_id])}}" class="btn btn-sm btn-warning"><i class="fa fa-pen"></i></a>
                                        <a href="{{route('deleteMenuApp', [$menu->menu_id])}}" onclick="return confirm('Apakah anda yakin akan menghapus data ini ?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        {{ $rs_menu->links() }}
                    </ul>
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection
