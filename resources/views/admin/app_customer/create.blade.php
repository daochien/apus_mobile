@extends('layouts.admin')
@section('title')
    Thêm mới app customer
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Thêm mới app customer</h1>
        </div>
    </section>
    <!-- /.content -->
    <div class="container-fluid" id="app">
        <admin-app-customer-form :edit-model="false" :packages="{{ json_encode($packages) }}"/>
    </div>
@endsection

@section('footer_scripts')
@stop
