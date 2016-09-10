@extends('layout.admin')

@section('title', '编辑简答题')

@include('admin.drawers.question')

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/{{ $meta->category }}">{{ $meta->title }}</a></li>
    <li><a href="/admin/{{ $meta->category.'/'.$meta->type }}">{{ $meta->question }}</a></li>
    <li class="active">编辑题目</li>
</ul>
<div class="row">
    <form class="col-md-8" action="/admin/{{ $meta->category.'/'.$meta->type }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $question->id }}">
        <legend>编辑题目</legend>
        <div class="form-group{{ pif($errors->has('description'), ' has-error') }}">
            <label for="description">描述</label>
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
