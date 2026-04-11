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
                                <th>Warna</th>
                                <th>Status</th>
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
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->color }}</td>
                                    <td class="text-center">
                                        @if ($data->stastu == 'yes')
                                            <span class="badge badge-success">Digunakan</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Digunakan</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:;" class="btn btn-sm btn-warning edit" data-id="{{ $data->id }}"><i class="fa fa-pen"></i></a>
                                        <a href="{{ route('processDeleteArtikelKategori', [$data->id]) }}" onclick="return confirm('Apakah anda yakin akan menghapus data ini ?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
                <form action="{{ route('processAddArtikelKategori') }}" method="POST" enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Kategori</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input name="name" value="{{ old('name') }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Warna</label>
                            <input name="color" value="{{ old('color') }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="stastu" class="form-control" id="">
                                <option value=""></option>
                                <option value="yes" @selected(old('stastu') == 'yes')>Ya</option>
                                <option value="no" @selected(old('stastu') == 'no')>Tidak</option>
                            </select>
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
                <form action="{{ route('processEditArtikelKategori') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="">
                    @method('POST')
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Kategori</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input name="name" value="{{ old('name') }}" id="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Warna</label>
                            <input name="color" value="{{ old('color') }}" id="color" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="stastu" id="stastu" class="form-control" id="">
                                <option value=""></option>
                                <option value="yes" @selected(old('stastu') == 'yes')>Ya</option>
                                <option value="no" @selected(old('stastu') == 'no')>Tidak</option>
                            </select>
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
            url: "{{ route('getDetailKategori') }}",
            type: "GET",
            dataType: "json",
            data: {
                id: id
            },
            success: function (res) {
                if(res.status){
                    $('#id').val(res.data.id);
                    $('#name').val(res.data.name);
                    $('#color').val(res.data.color);
                    $('#stastu').val(res.data.stastu).change();
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
