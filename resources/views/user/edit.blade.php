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
                            <li class="breadcrumb-item"><a href="#">Data User</a></li>
                            <li class="breadcrumb-item active">{{$title}}</li>
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
                        <a href="{{ route('dataUser') }}" class="btn btn-block btn-success"><i class="fa fa-arrow-left"></i>
                            Kembali</a>
                    </div>
                </div>
                <form action="{{route('aksiUpdateUser', [$detail->user_id])}}" method="POST">
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
                            {{session('success')}}
                       </div>
                       @endsession
                        <div class="form-group">
                            <label>Nama lengkap</label>
                            <input type="text" value="{{ old('name', $detail->name) }}" name="name" class="form-control"
                                placeholder="Nama lengkap">
                        </div>
                        <div class="form-group">
                            <label>User Role</label>
                            <select class="form-control select2" name="role_id" style="width: 100%;">
                                <option value=""></option>
                                @foreach ($rs_role as $role)
                                    <option value="{{ $role->role_id }}" {{ (old('role_id', $detail->role_id) == $role->role_id ? 'selected' : '' ) }}>{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" value="{{old('username', $detail->username)}}" name="username" class="form-control" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" value="{{old('password')}}" name="password" class="form-control" placeholder="Password">
                            <small class="text-danger"><i>Kosongi jika tidak ingin merubah password</i></small>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
@endsection
