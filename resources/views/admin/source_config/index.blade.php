@extends('layouts.admin')
@section('title')
    Danh sách source config
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Danh sách source config</h1>
        </div>
    </section>
    <!-- /.content -->
    <div class="container-fluid" id="app">
        <admin-source-config-index />
    </div>
@endsection

@section('footer_scripts')
@stop
