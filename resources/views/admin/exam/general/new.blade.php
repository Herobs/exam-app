@extends('layout.admin')

@section('title', '创建综合题')

@include('admin.drawers.exam', ['active' => 'general'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li><a href="/admin/exam/{{ $exam->id }}/general">综合题</a></li>
    <li class="active">创建题目</li>
</ul>
<div class="alert alert-info">
    综合题为主观类题目，需要学生根据题目要求上传文件作为答案。本类题目需要手工评阅分数。
</div>
<div class="row">
    <form class="col-md-8" action="/admin/exam/{{ $exam->id }}/general" method="POST">
        {{ csrf_field() }}
        <legend>创建题目</legend>
        <div class="form-group{{ pif($errors->has('description'), ' has-error') }}">
            <label for="name">描述</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
            @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('score'), ' has-error') }}">
            <label for="score">分数</label>
            <input class="form-control" id="score" name="score" value="{{ old('score') }}">
            @if ($errors->has('score'))
                <span class="help-block">
                    <strong>{{ $errors->first('score') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">创建</button>
        </div>
    </form>
</div>
@endsection
