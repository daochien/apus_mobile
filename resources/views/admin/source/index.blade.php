@extends('layouts.admin')
@section('title')
    Danh sách source mẫu
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Danh sách source mẫu</h1>
        </div>
    </section>
    <!-- /.content -->
    <div class="container-fluid" id="app">
        <admin-source-index :sources="{{ json_encode($sources) }}" />
    </div>
@endsection

@section('footer_scripts')
@stop
