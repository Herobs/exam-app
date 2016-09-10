@extends('layout.app')

@section('title', '首页')

@section('content')
@include('layout.navbar', ['active' => 'exams'])
<div class="container">
    index
</div>
@include('layout.footer')
@endsection
