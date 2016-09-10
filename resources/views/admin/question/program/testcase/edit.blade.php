@extends('layout.admin')

@section('title', '编辑测试数据')

@include('admin.drawers.question')

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/{{ $meta->category }}">{{ $meta->title }}</a></li>
    <li><a href="/admin/{{ $meta->category.'/program' }}">{{ $meta->question }}</a></li>
    <li><a href="/admin/{{ $meta->category.'/program/'.$question->id.'/testcase' }}">测试数据</a></li>
    <li class="active">编辑测试数据</li>
</ul>
<div class="row">
    <form class="col-md-8" action="{{ '/admin/'.$meta->category.'/program/'.$question->id.'/testcase' }}" method="POST">
        {{ csrf_field() }}
        <legend>编辑数据</legend>
        <div class="form-group{{ pif($errors->has('testcase'), ' has-error') }}">
            <label for="testcase">测试组</label>
            <input class="form-control" id="testcase" name="testcase" value="{{ por(old('testcase'), $question->case) }}">
            @if ($errors->has('testcase'))
                <span class="help-block">
                    <strong>{{ $errors->first('testcase') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('input'), ' has-error') }}">
            <label for="input">标准输入</label>
            <textarea class="form-control" id="input" name="input" rows="8">{{ por(old('input'), $question->input) }}</textarea>
            @if ($errors->has('input'))
                <span class="help-block">
                    <strong>{{ $errors->first('input') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('output'), ' has-error') }}">
            <label for="output">标准输出</label>
            <textarea class="form-control" id="output" name="output" rows="8">{{ por(old('output'), $question->output) }}</textarea>
            @if ($errors->has('output'))
                <span class="help-block">
                    <strong>{{ $errors->first('output') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">保存</button>
        </div>
    </form>
</div>
@endsection
