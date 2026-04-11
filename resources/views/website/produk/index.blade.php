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
                        <a href="{{ route('addkatalogProduk') }}" class="btn btn-block btn-success"><i class="fa fa-plus"></i> Tambah</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Rating</th>
                                <th>Harga</th>
                                <th style="width: 25%">Deskripsi Singkat</th>
                                <th style="width: 10%">Image</th>
                                <th style="width: 8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rs_data as $key => $data)
                                <tr>
                                    <td class="text-center">{{ $rs_data->firstItem() + $key }}</td>
                                    <td>{{ $data->produk_nama }}</td>
                                    <td width="5%" class="text-center">{{ $data->produk_rating }}</td>
                                    <td class="text-right">Rp {{ number_format($data->produk_harga, 0, ', ', '.') }}</td>
                                    <td width="35%">{{ $data->produk_short_desc }}</td>
                                    <td><img width="100" height="100" src="{{ asset( $data->produk_path . '/' . $data->produk_image) }}" alt="{{ $data->produk_nama }}"></td>
                                    <td class="text-center">
                                        <a href="{{ route('editkatalogProduk', $data->slug) }}" class="btn btn-sm btn-warning"><i class="fa fa-pen"></i></a>
                                        <a href="{{ route('processDeletekatalogProduk', $data->slug) }}" onclick="return confirm('Apakah anda yakin akan menghapus data ini ?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
