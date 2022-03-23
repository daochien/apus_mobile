@extends('layouts.admin')
@section('title')
    Chỉnh sửa source mẫu
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Chỉnh sửa source mẫu</h1>
        </div>
    </section>
    <!-- /.content -->
    <div class="container-fluid" id="app">
        <admin-source-form :source="{{ json_encode($source) }}" :edit-model="true" />
    </div>
@endsection

@section('footer_scripts')
@stop
