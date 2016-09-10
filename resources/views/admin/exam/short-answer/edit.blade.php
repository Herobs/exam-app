@extends('layout.admin')

@section('title', '编辑简答题')

@include('admin.drawers.exam', ['active' => 'short-answer'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li><a href="/admin/exam/{{ $exam->id }}/short-answer">简答题</a></li>
    <li class="active">编辑题目</li>
</ul>
<div class="alert alert-info">
    简答题为主观类题目，需要学生在文本框中简述答案。本类题目需要手工评阅分数。
</div>
<div class="row">
    <form class="col-md-8" action="/admin/exam/{{ $exam->id }}/short-answer" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $question->id }}">
        <legend>编辑题目</legend>
        <div class="form-group{{ pif($errors->has('description'), ' has-error') }}">
            <label for="name">描述</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ por(old('description'), $question->description) }}</textarea>
            @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('score'), ' has-error') }}">
            <label for="score">分数</label>
            <input class="form-control" id="score" name="score" value="{{ por(old('score'), $question->score) }}">
            @if ($errors->has('score'))
                <span class="help-block">
                    <strong>{{ $errors->first('score') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">保存</button>
        </div>
    </form>
</div>
@endsection
