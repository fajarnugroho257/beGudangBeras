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
                <form action="{{ route('processAddvisiMisi') }}" method="POST">
                    @method('POST')
                    @csrf
                    <div class="card-body">
                        <h5><b>Visi</b></h5>
                        <textarea id="summernote" name="pref_misi">
                            {{ $detail->pref_value }}
                        </textarea>
                        <div id="dynamicMisiFields">
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($rs_data as $key => $data)
                                <div class="form-group row misi-input-group" id="misiGroup-1">
                                    <label class="col-2 col-form-label">Misi #{{ $no }}</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" value="{{ $data->misi_value }}" name="misi[]" placeholder="Deskripsi Misi" required>
                                    </div>
                                    @if ($key == 0)
                                        <div class="col-1 d-flex align-items-center justify-content-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-misi-field" style="display: none;" data-id="{{ $no }}">
                                                &times;
                                            </button>
                                        </div>
                                    @else
                                        <div class="col-1 d-flex align-items-center justify-content-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-misi-field" data-id="{{ $no }}">
                                                &times;
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                @php
                                    $no++;
                                @endphp
                            @endforeach
                        </div>
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-10 text-right">
                                <button type="button" id="addMisiField" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Misi Lain
                                </button>
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
                        <label class="col-sm-2 col-form-label">Misi #${newMisiNumber}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="misi[]" 
                                placeholder="Deskripsi Misi" required>
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

            // Contoh: Handle Submission (data yang diambil)
            $('#misiForm').submit(function(e) {
                e.preventDefault();
                
                const visi = $('#visi').val();
                // Ambil semua nilai dari input misi[]
                const misiArray = $('input[name="misi[]"]').map(function(){
                    return $(this).val();
                }).get();
                
                console.log("--- DATA YANG DIKIRIM ---");
                console.log("Visi:", visi);
                console.log("Misi (Array):", misiArray);
                
                alert("Data berhasil dikonsol (Silakan cek Console Browser).");
                // Di sini Anda akan mengirim data ke server menggunakan AJAX
            });
        });

    </script>
@endsection
@section('plugin-js')
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
@endsection