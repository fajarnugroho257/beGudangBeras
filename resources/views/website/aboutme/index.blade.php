@extends('template.base.base')
@section('plugin-css')
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection
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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
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
                        
                    </div>
                </div>
                <form action="{{ route('processEditTentangKami', $detail->id) }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="pusat_id" value="{{ $detail->pusat_id }}" id="">
                    @method('POST')
                    @csrf
                    <div class="card-body">
                        <textarea id="summernote" name="pref_value">
                            {{ $detail->pref_value }}
                        </textarea>
                        <div class="form-group">
                            <label>Aktivitas</label>
                            <input type="file" value="{{ old('pref_image') }}" name="pref_image[]" class="form-control" accept="image/*" placeholder="Icon">
                            {{-- <small class="text-danger">*bisa upload lebih dari satu image, hanya png|jpg|jpeg|<b>max:512</b></small> --}}
                            <small class="text-danger">*upload ulang jika ingin mengganti, hanya png|jpg|jpeg|<b>max:2mb</b></small>
                        </div>
                        <div class="form-group">
                            <label class="d-block">Image</label>
                            <div class="row">
                                @foreach ($rs_image as $img)
                                    <div class="position-relative" style="height: 200px; width: 300px">
                                        <img class="img-fluid img-thumbnail" src="{{ asset('image/aboutme/' . $img->pref_value) }}" alt="{{ $img->pref_value }}" style="height: 100%; width: 100%">
                                        {{-- <div class="position-absolute" style="top: 5px; right: 20px; cursor: pointer;">
                                            <a onclick="return confirm('Apakah anda yakin akan menghapus data ini ?')" href="{{ route('hapusImage', ['id' => $img->id, 'pusat_id' => $detail->pusat_id]) }}">
                                                <i  class="fa fa-times text-danger"></i>
                                            </a>
                                        </div> --}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
@section('javascript')
    <script>
        $(function() {
            // Summernote
            $('#summernote').summernote()

            // CodeMirror
            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                mode: "htmlmixed",
                theme: "monokai"
            });
        })
    </script>
@endsection
@section('plugin-js')
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
@endsection