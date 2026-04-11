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
                        <a href="javascript:;" data-toggle="modal" data-target="#modal-lg"
                            class="btn btn-block btn-success"><i class="fa fa-plus"></i>
                            Tambah</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 10px">No</th>
                                <th>Nama</th>
                                <th>Desc</th>
                                <th>Image</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($rs_data as $key => $data)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $data->author_name }}</td>
                                    <td>{{ $data->author_desc }}</td>
                                    <td width="20%" class="text-center">
                                        <img class="img-fluid img-thumbnail" width="100" height="100" src="{{ asset($data->author_path . "/" . $data->author_image) }}" alt="{{ $data->author_name }}">
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:;" class="btn btn-sm btn-warning edit" data-id="{{ $data->id }}"><i class="fa fa-pen"></i></a>
                                        <a href="{{ route('processDeleteArtikelAuthor', [$data->id]) }}" onclick="return confirm('Apakah anda yakin akan menghapus data ini ?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('processAddArtikelAuthor') }}" method="POST" enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Author</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input name="author_name" value="{{ old('author_name') }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <input name="author_desc" value="{{ old('author_desc') }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" value="{{ old('author_image') }}" name="author_image" class="form-control" placeholder="Icon" accept="image/png, image/gif, image/jpeg">
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
    {{-- edit --}}
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('processEditArtikelAuthor') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="">
                    @method('POST')
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Author</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input name="author_name" value="{{ old('author_name') }}" id="author_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <input name="author_desc" value="{{ old('author_desc') }}" id="author_desc" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" value="{{ old('author_image') }}" name="author_image" class="form-control" placeholder="Icon" accept="image/png, image/gif, image/jpeg">
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
@section('javascript')
<script>
    $('.edit').on('click', function() {
        const id = $(this).data('id');
        // console.log(id);
        $.ajax({
            url: "{{ route('getDetailAuthor') }}",
            type: "GET",
            dataType: "json",
            data: {
                id: id
            },
            success: function (res) {
                if(res.status){
                    $('#id').val(res.data.id);
                    $('#author_name').val(res.data.author_name);
                    $('#author_desc').val(res.data.author_desc);
                    $('#modal-edit').modal('show');
                } else {
                    console.log(res.message);
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    });
</script>
@endsection
