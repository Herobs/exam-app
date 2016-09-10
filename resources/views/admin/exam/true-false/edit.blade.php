@extends('layout.admin')

@section('title', '编辑判断题')

@include('admin.drawers.exam', ['active' => 'true-false'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li><a href="/admin/exam/{{ $exam->id }}/true-false">判断题</a></li>
    <li class="active">编辑题目</li>
</ul>
<div class="row">
    <form class="col-md-8" action="/admin/exam/{{ $exam->id }}/true-false" method="POST">
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
        <div class="form-group{{ pif($errors->has('answer'), ' has-error') }}">
            <label for="answer">答案</label>
            <select class="form-control" id="answer" name="answer">
                <option value="true"{!! pif(old('answer') === 'true' || $question->answer === 'true', ' selected="selected"') !!}>正确</option>
                <option value="false"{!! pif(old('answer') === 'false' || $question->answer === 'false', ' selected="selected"') !!}>错误</option>
            </select>
            @if ($errors->has('answer'))
                <span class="help-block">
                    <strong>{{ $errors->first('answer') }}</strong>
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
