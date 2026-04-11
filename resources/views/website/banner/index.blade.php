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
                            <li class="breadcrumb-item active">{{ $title ?? '' }}</li>
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
            @session('error')
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endsession
            <!-- Default box -->
            <div class="card">
                <div class="card-header ">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('addBanner') }}" class="btn btn-block btn-success"><i class="fa fa-plus"></i> Tambah</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 10px">No</th>
                                <th width="5%">Urutan</th>
                                <th>Banner</th>
                                <th>Title</th>
                                <th>Desc</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rs_data as $key => $data)
                                <tr>
                                    <td class="text-center">{{ $rs_data->firstItem() + $key }}</td>
                                    <td class="text-center">{{ $data->banner_urut }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset($data->banner_path) }}" height="150" width="150" class="img-fluid img-thumbnail" alt="">
                                    </td>
                                    <td>{{ $data->banner_title }}</td>
                                    <td>{{ $data->banner_desc }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('editBanner', [$data->id]) }}" class="btn btn-sm btn-warning"><i class="fa fa-pen"></i></a>
                                        <a href="{{ route('processDeleteBanner', [$data->id]) }}" onclick="return confirm('Apakah anda yakin akan menghapus data ini ?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        {{ $rs_data->links() }}
                    </ul>
                </div>
            </div>
        </section>
    </div>
@endsection
