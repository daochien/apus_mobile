@extends('layouts.admin')
@section('title')
    Tạo mới source config
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Tạo mới source config</h1>
        </div>
    </section>
    <!-- /.content -->
    <div class="container-fluid" id="app">
        <admin-source-config-form :sources="{{ json_encode($sources) }}" />
    </div>
@endsection

@section('footer_scripts')
@stop
