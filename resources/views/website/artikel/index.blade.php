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
                        <a href="{{ route('addArtikelData') }}" class="btn btn-block btn-success"><i
                                class="fa fa-plus"></i>
                            Tambah</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th style="width: 10%">Judul</th>
                                <th>Deskripsi</th>
                                <th style="width: 5%">Views</th>
                                <th style="width: 10%">Post</th>
                                <th style="width: 25%">Image</th>
                                <th style="width: 8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rs_data as $key => $data)
                                <tr>
                                    <td class="text-center">{{ $rs_data->firstItem() + $key }}</td>
                                    <td>{{ $data->artikel_title }}</td>
                                    <td>{!! $data->artikel_desc !!}</td>
                                    <td class="text-center">{{ $data->artikel_views }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($data->artikel_date)->locale('id')->timezone('Asia/Jakarta')->translatedFormat('l, d F Y H:i') }}</td>
                                    <td>
                                        <img class="img-fluid img-thumbnail"
                                        src="{{ asset($data->artikel_path . '/' . $data->artikel_name) }}"
                                        alt="{{ $data->artikel_name }}" style="height: 100%; width: 100%">
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('editArtikelData', [$data->artikel_slug]) }}"
                                            class="btn btn-sm btn-warning"><i class="fa fa-pen"></i></a>
                                        <a href="{{ route('processDeleteArtikelData', [$data->artikel_slug]) }}"
                                            onclick="return confirm('Apakah anda yakin akan menghapus data ini ?')"
                                            class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        {{ $rs_data->links() }}
                    </ul>
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection
