@extends('layouts.admin')
@section('title')
    Danh sách
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Danh sách app khách hàng</h1>
        </div>
    </section>
    <!-- /.content -->
    <div class="container-fluid" id="app">
        <admin-app-customer-index :apps="{{ json_encode($items) }}" />
    </div>
@endsection

@section('footer_scripts')
@stop
