@extends('layouts.admin')
@section('title')
    Cập nhật gói đăng ký
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Thêm mới gói đăng ký</h1>
        </div>
    </section>
    <!-- /.content -->
    <div class="container-fluid" id="app">
        <admin-package-form :edit-model="true" :sources="{{ json_encode($sources) }}" :item="{{ json_encode($package) }}" />
    </div>
@endsection

@section('footer_scripts')
@stop
