@extends('layouts.admin')
@section('title')
    Danh sách packages
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Danh sách packages</h1>
        </div>
    </section>
    <!-- /.content -->
    <div class="container-fluid" id="app">
        <admin-package-index :packages="{{ json_encode($packages) }}" />
    </div>
@endsection

@section('footer_scripts')
@stop
