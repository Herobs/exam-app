@extends('layout.app')

@section('title', '无法参加该场考试')

@section('content')
@include('layout.navbar', ['active' => 'exams'])
<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <h1>{{ $exam->name }}</h1>
        <h3>您不能参加该场考试。</h3>
        <p>由于我们未能在允许参加考试的学生名单中找到您的信息，所以您不能参加该场考试。</p>
        <p>您可以检查您的资料中学号是否填写正确，或者联系您的老师解决这个问题。</p>
        <p class="mt10">
            <a href="/" class="btn btn-primary">返回首页</a>
            <a href="/exams" class="btn btn-primary">考试列表</a>
        </p>
    </div>
</div>
@include('layout.footer')
@endsection
