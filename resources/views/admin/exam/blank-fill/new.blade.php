@extends('layout.admin')

@section('title', '创建填空题')

@include('admin.drawers.exam', ['active' => 'blank-fill'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li><a href="/admin/exam/{{ $exam->id }}/blank-fill">填空题</a></li>
    <li class="active">创建题目</li>
</ul>
<div class="alert alert-info">
    <code>@@</code> 作为空格占位符，提供的答案数量必须和<code>@@</code>数量相同。
    答案可以使用正则表达式（PHP），形式为<code>r'/regexp/'</code>，比如<b>子类</b>和<b>派生类</b>均为正确答案，则可以使用<code>r'/^(基类|派生类)$/'</code>。
</div>
<div class="row">
    <form class="col-md-8" action="/admin/exam/{{ $exam->id }}/blank-fill" method="POST">
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
        <div class="form-group hidden" id="admin-exam-answer-example">
            <label>答案</label>
            <div class="input-group">
                <input class="form-control">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default admin-exam-remove"><i class="glyphicon glyphicon-minus-sign"></i></button>
                </span>
            </div>
        </div>
        <div id="admin-exam-answers">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-link" id="admin-exam-add-answer"><i class="glyphicon glyphicon-plus"></i> 添加答案</button>
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
