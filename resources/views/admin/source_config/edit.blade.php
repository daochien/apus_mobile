@extends('layouts.admin')
@section('title')
    Cập nhật source config
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Cập nhật source config</h1>
        </div>
    </section>
    <!-- /.content -->
    <div class="container-fluid" id="app">
        <admin-source-config-form :sources="{{ json_encode($sources) }}" :config="{{ json_encode($config) }}" :edit-model="true" />
    </div>
@endsection

@section('footer_scripts')
@stop
