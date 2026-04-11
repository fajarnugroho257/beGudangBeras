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

            <!-- Default box -->
            <div class="card">

                <div class="card-header ">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('katalogProduk') }}" class="btn btn-block btn-success"><i
                                class="fa fa-arrow-left"></i>
                            Kembali</a>
                    </div>
                </div>
                <form action="{{ route('processAddkatalogProduk') }}" method="POST" enctype="multipart/form-data">
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
                                    <label>Nama Produk</label>
                                    <input name="produk_nama" value="{{ old('produk_nama') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rating Produk</label>
                                    <input type="number" name="produk_rating" value="{{ old('produk_rating') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Harga Produk</label>
                                    <input type="number" name="produk_harga" value="{{ old('produk_harga') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Penjelasan Singkat</label>
                                    <textarea name="produk_short_desc" id="" cols="5" rows="4" class="form-control">{{ old('produk_short_desc') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Lengkap</label>
                            <textarea name="produk_desc" id="" cols="10" rows="7" class="form-control">{{ old('produk_desc') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Lainnya</label>
                            <div id="dynamicMisiFields">
                                <div class="form-group row misi-input-group" id="misiGroup-1">
                                    <label class="col-2 col-form-label">Deskripsi 1</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" value="" name="detail_desc[]" placeholder="Deskripsi Produk Lainnya">
                                    </div>
                                    <div class="col-1 d-flex align-items-center justify-content-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-misi-field" data-id="1">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-10 text-right">
                                <button type="button" id="addMisiField" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Deskripsi Lain
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Informasi Tambahan</label>
                            <div id="dynamicSpesifikasi">
                                <div class="form-group row spesifikasi-input-group" id="spesifikasiGroup-1">
                                    <label class="col-2 col-form-label">Spesifikasi 1</label>
                                    <div class="col-4">
                                        <input type="text" class="form-control" value="" name="spesifikasi[]" placeholder="Nama Spesifikasi">
                                    </div>
                                    <div class="col-5">
                                        <input type="text" class="form-control" value="" name="spesifikasi_detail[]" placeholder="Uraian Spesifikasi">
                                    </div>
                                    <div class="col-1 d-flex align-items-center justify-content-center">
                                        <button type="button" class="btn btn-sm btn-danger spesifikasi-misi-field" data-id="1">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-10 text-right">
                                <button type="button" id="addSpesifikasiField" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Spesifikasi Lain
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gambar Awal</label>
                            <input type="file" value="{{ old('produk_image') }}" multiple name="produk_image" class="form-control" placeholder="Icon">
                        </div>
                        <div class="form-group">
                            <label>Gambar Lainnya</label>
                            <input type="file" value="{{ old('file') }}" multiple name="file[]" class="form-control" placeholder="Icon">
                            <small class="text-danger">*bisa memilih lebih dari 1 gambar</small>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            let misiCount = 1; // Penghitung jumlah input misi

            // Tampilkan tombol hapus untuk input misi pertama setelah DOM siap
            $('#misiGroup-' + misiCount).find('.remove-misi-field').show();

            // 1. FUNGSI MENAMBAH INPUT (Tombol +)
            $('#addMisiField').click(function() {
                misiCount++; // Tingkatkan penghitung

                // Tentukan ID baru untuk grup input
                const newMisiId = 'misiGroup-' + misiCount;

                // Tentukan nomor Misi untuk label
                const newMisiNumber = misiCount;

                // Template HTML untuk input baru
                const newMisiField = `
                    <div class="form-group row misi-input-group" id="${newMisiId}">
                        <label class="col-sm-2 col-form-label">Deskripsi #${newMisiNumber}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="detail_desc[]" 
                                placeholder="Deskripsi Produk Lainnya">
                        </div>
                        <div class="col-sm-1 d-flex align-items-center justify-content-center">
                            <button type="button" class="btn btn-sm btn-danger remove-misi-field" data-id="${newMisiId}">
                                &times;
                            </button>
                        </div>
                    </div>
                `;

                // Tambahkan template ke container
                $('#dynamicMisiFields').append(newMisiField);

                // Pastikan tombol hapus untuk input pertama (jika hanya ada 1 input sebelumnya) tetap terlihat
                updateRemoveButtons();
            });

            // 2. FUNGSI MENGHAPUS INPUT (Tombol X)
            $('#dynamicMisiFields').on('click', '.remove-misi-field', function() {
                // Cari group terdekat dan hapus
                $(this).closest('.misi-input-group').remove();
                
                // Perbarui penomoran dan tombol hapus setelah penghapusan
                reindexMisiFields();
                updateRemoveButtons();
            });

            // 3. FUNGSI UNTUK RE-INDEXING (memperbarui nomor urut Misi #X)
            function reindexMisiFields() {
                let index = 1;
                // Iterasi melalui semua grup input misi yang tersisa
                $('#dynamicMisiFields').find('.misi-input-group').each(function() {
                    // Update Label
                    $(this).find('label').text('Misi #' + index);
                    index++;
                });
                misiCount = index - 1; // Perbarui counter agar penambahan berikutnya benar
            }
            
            // 4. FUNGSI UNTUK MENYEMBUNYIKAN TOMBOL HAPUS JIKA HANYA ADA SATU FIELD
            function updateRemoveButtons() {
                const totalInputs = $('#dynamicMisiFields').find('.misi-input-group').length;
                if (totalInputs <= 1) {
                    // Sembunyikan semua tombol hapus jika hanya satu
                    $('.remove-misi-field').hide();
                } else {
                    // Tampilkan semua tombol hapus jika lebih dari satu
                    $('.remove-misi-field').show();
                }
            }
            
            // SPESIFIKASI

            let spesifikasiCount = 1;
            $('#spesifikasiGroup-' + spesifikasiCount).find('.remove-spesifikasi-field').show();
            // 1. FUNGSI MENAMBAH INPUT (Tombol +)
            $('#addSpesifikasiField').click(function() {
                spesifikasiCount++; // Tingkatkan penghitung

                // Tentukan ID baru untuk grup input
                const newSpesifikasiId = 'spesifikasiGroup-' + spesifikasiCount;

                // Tentukan nomor Misi untuk label
                const newSpesifikasiNumber = spesifikasiCount;

                // Template HTML untuk input baru
                const newSpesifikasiField = `
                    <div class="form-group row spesifikasi-input-group" id="${newSpesifikasiId}">
                        <label class="col-sm-2 col-form-label">Spesifikasi #${newSpesifikasiNumber}</label>
                        <div class="col-4">
                            <input type="text" class="form-control" value="" name="spesifikasi[]" placeholder="Nama Spesifikasi">
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" value="" name="spesifikasi_detail[]" placeholder="Uraian Spesifikasi">
                        </div>
                        <div class="col-sm-1 d-flex align-items-center justify-content-center">
                            <button type="button" class="btn btn-sm btn-danger spesifikasi-misi-field" data-id="${newSpesifikasiId}">
                                &times;
                            </button>
                        </div>
                    </div>
                `;

                // Tambahkan template ke container
                $('#dynamicSpesifikasi').append(newSpesifikasiField);

                // Pastikan tombol hapus untuk input pertama (jika hanya ada 1 input sebelumnya) tetap terlihat
                updateRemoveSpesifikasiButtons();
            });

            // 2. FUNGSI MENGHAPUS INPUT (Tombol X)
            $('#dynamicSpesifikasi').on('click', '.spesifikasi-misi-field', function() {
                // Cari group terdekat dan hapus
                $(this).closest('.spesifikasi-input-group').remove();
                
                // Perbarui penomoran dan tombol hapus setelah penghapusan
                reindexSpesifikasiFields();
                updateRemoveSpesifikasiButtons();
            });

            // 3. FUNGSI UNTUK RE-INDEXING (memperbarui nomor urut Misi #X)
            function reindexSpesifikasiFields() {
                let index = 1;
                // Iterasi melalui semua grup input misi yang tersisa
                $('#dynamicSpesifikasi').find('.spesifikasi-input-group').each(function() {
                    // Update Label
                    $(this).find('label').text('Spesifikasi #' + index);
                    index++;
                });
                spesifikasiCount = index - 1; // Perbarui counter agar penambahan berikutnya benar
            }
            
            // 4. FUNGSI UNTUK MENYEMBUNYIKAN TOMBOL HAPUS JIKA HANYA ADA SATU FIELD
            function updateRemoveSpesifikasiButtons() {
                const totalInputs = $('#dynamicSpesifikasi').find('.spesifikasi-input-group').length;
                if (totalInputs <= 1) {
                    // Sembunyikan semua tombol hapus jika hanya satu
                    $('.spesifikasi-misi-field').hide();
                } else {
                    // Tampilkan semua tombol hapus jika lebih dari satu
                    $('.spesifikasi-misi-field').show();
                }
            }
        });

    </script>
@endsection
