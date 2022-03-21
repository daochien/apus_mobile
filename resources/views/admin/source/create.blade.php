@extends('layouts.admin')
@section('title')
    Thêm mới source mẫu
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Thêm mới source mẫu</h1>
        </div>
    </section>
    <!-- /.content -->
    <div class="container-fluid" id="app">
        <admin-source-form :edit-model="false" />
    </div>
@endsection

@section('footer_scripts')
@stop
