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
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="card">
                <div class="card-header ">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('artikelData') }}" class="btn btn-block btn-success"><i class="fa fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
                <form action="{{ route('processAddArtikelData') }}" method="POST" enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    <div class="card-body">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="kategori_id" id="" class="form-control">
                                        <option value=""></option>
                                        @foreach ($rs_kategori as $kategori)
                                            <option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id )>{{ $kategori->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Author</label>
                                    <select name="author_id" id="" class="form-control">
                                        <option value=""></option>
                                        @foreach ($rs_author as $author)
                                            <option value="{{ $author->id }}" @selected(old('author_id') == $author->id )>{{ $author->author_name }} - {{ $author->author_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Judul Artikel</label>
                            <input name="artikel_title" value="{{ old('artikel_title') }}" class="form-control" placeholder="Judul artikel yang identik">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Artikel</label>
                        </div>
                        <textarea id="summernote" name="artikel_desc">
                            {{ old('artikel_desc') }}
                        </textarea>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" value="{{ old('artikel_image') }}" name="artikel_image" class="form-control"
                                placeholder="Icon">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" value="{{ old('artikel_date') }}" name="artikel_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jam</label>
                                    <input type="time" value="{{ old('artikel_time') }}" name="artikel_time" class="form-control">
                                </div>
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
@section('plugin-js')
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
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