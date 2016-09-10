
@extends('layout.admin')

@section('title', '创建选择题')

@include('admin.drawers.question')

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/{{ $meta->category }}">{{ $meta->title }}</a></li>
    <li><a href="/admin/{{ $meta->category.'/'.$meta->type }}">{{ $meta->question }}</a></li>
    <li class="active">创建题目</li>
</ul>
<div class="alert alert-info">
    选项前面的复选框表示该项是否为答案，所有题目答案都是多选，可在题目中标注是否为多选题。最多可以设置 26 个选项（A - Z）。
</div>
<div class="row">
    <form class="col-md-8" action="/admin/{{ $meta->category.'/'.$meta->type }}" method="POST">
        {{ csrf_field() }}
        <legend>创建题目</legend>
        <div class="form-group{{ pif($errors->has('description'), ' has-error') }}">
            <label for="description">描述</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
            @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group hidden" id="admin-exam-option-example">
            <label>选项</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <input type="checkbox">
                </span>
                <input class="form-control" type="text">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default admin-exam-remove"><i class="glyphicon glyphicon-minus-sign"></i></button>
                </span>
            </div>
        </div>
        <div class="form-group{{ pif($errors->has('orders'), ' has-error') }}">
            <input type="hidden" id="admin-exam-orders" name="orders" value="4">
            @if ($errors->has('orders'))
                <span class="help-block">
                    <strong>{{ $errors->first('orders') }}</strong>
                </span>
            @endif
        </div>
        <div id="admin-exam-options">
            <div class="form-group{{ pif($errors->has('options.0'), ' has-error') }}">
                <label for="option-0">选项 A</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" name="answers[0]"{!! pif(old('answers.0'), 'checked="checked"') !!}>
                    </span>
                    <input class="form-control" id="option-0" name="options[0]" value="{{ old('options.0') }}">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default admin-exam-remove"><i class="glyphicon glyphicon-minus-sign"></i></button>
                    </span>
                </div>
                @if ($errors->has('options.0'))
                    <span class="help-block">
                        <strong>{{ $errors->first('options.0') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ pif($errors->has('options.1'), ' has-error') }}">
                <label for="option-1">选项 B</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" name="answers[1]"{!! pif(old('answers.1'), 'checked="checked"') !!}>
                    </span>
                    <input class="form-control" id="option-1" name="options[1]" value="{{ old('options.1') }}">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default admin-exam-remove"><i class="glyphicon glyphicon-minus-sign"></i></button>
                    </span>
                </div>
                @if ($errors->has('options.1'))
                    <span class="help-block">
                        <strong>{{ $errors->first('options.1') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ pif($errors->has('options.2'), ' has-error') }}">
                <label for="option-2">选项 C</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" name="answers[2]"{!! pif(old('answers.2'), 'checked="checked"') !!}>
                    </span>
                    <input class="form-control" id="option-2" name="options[2]" value="{{ old('options.2') }}">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default admin-exam-remove"><i class="glyphicon glyphicon-minus-sign"></i></button>
                    </span>
                </div>
                @if ($errors->has('options.2'))
                    <span class="help-block">
                        <strong>{{ $errors->first('options.2') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ pif($errors->has('options.3'), ' has-error') }}">
                <label for="option-3">选项 D</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" name="answers[3]"{!! pif(old('answers.3'), 'checked="checked"') !!}>
                    </span>
                    <input class="form-control" id="option-3" name="options[3]" value="{{ old('options.3') }}">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default admin-exam-remove"><i class="glyphicon glyphicon-minus-sign"></i></button>
                    </span>
                </div>
                @if ($errors->has('options.3'))
                    <span class="help-block">
                        <strong>{{ $errors->first('options.3') }}</strong>
                    </span>
                @endif
            </div>
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
            <button type="button" class="btn btn-link" id="admin-exam-add-option"><i class="glyphicon glyphicon-plus"></i> 添加选项</button>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">创建</button>
        </div>
    </form>
</div>
@endsection
