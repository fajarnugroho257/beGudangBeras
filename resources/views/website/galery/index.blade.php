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
                        <a href="javascript:;" data-toggle="modal" data-target="#modal-lg"
                            class="btn btn-block btn-success"><i class="fa fa-plus"></i>
                            Tambah</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        @foreach ($rs_galery as $galery)
                            <div class="position-relative" style="height: 150px; width: 150px">
                                <img class="img-fluid img-thumbnail"src="{{ asset($galery->image_path . '/' . $galery->image_name) }}" alt="{{ $galery->deskripsi }}" style="height: 100%; width: 100%">
                                <small class="d-flex justify-content-center">{{ $galery->deskripsi }}</small>
                                <div class="position-absolute" style="top: 5px; right: 20px; cursor: pointer;">
                                    <a href="{{ route('deleteGalery', [$galery->id]) }}">
                                        <i class="fa fa-times text-danger" onclick="return confirm('Apakah anda yakin akan menghapus data ini ?')"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('addProsesGalery') }}" method="POST" enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Gambar</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Image</label>
                            <input name="deskripsi" value="{{ old('deskripsi') }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" value="{{ old('file') }}" multiple name="file[]" class="form-control"
                                placeholder="Icon" accept="image/png, image/gif, image/jpeg">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
