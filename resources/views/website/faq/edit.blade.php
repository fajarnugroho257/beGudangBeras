@extends('template.base.base')
@section('content')
    <div class="content-wrapper">
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
            </div>
        </section>
        <section class="content">
            <div class="card">
                <div class="card-header ">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('faq') }}" class="btn btn-block btn-success"><i class="fa fa-arrow-left"></i>  Kembali</a>
                    </div>
                </div>
                <form action="{{ route('processEditFaq', $detail->id) }}" method="POST">
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
                        @session('error')
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endsession
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Pertanyaan</label>
                                <input type="text" class="form-control" value="{{ old('title', $detail->title) }}" name="title" placeholder="Pertanyaan">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Deskripsi</label>
                                <textarea name="desc" class="form-control" cols="30" rows="4">{{ old('desc', $detail->desc) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
