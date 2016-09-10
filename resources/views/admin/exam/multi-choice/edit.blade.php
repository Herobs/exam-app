@extends('layout.admin')

@section('title', '编辑选择题')

@include('admin.drawers.exam', ['active' => 'multi-choice'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li><a href="/admin/exam/{{ $exam->id }}/multi-choice">选择题</a></li>
    <li class="active">编辑题目</li>
</ul>
<div class="alert alert-info">
    选项前面的复选框表示该项是否为答案，所有题目答案都是多选，可在题目中标注是否为多选题。最多可以设置 26 个选项（A - Z）。
</div>
<div class="row">
    <form class="col-md-8" action="/admin/exam/{{ $exam->id }}/multi-choice" method="POST">
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
            <input type="hidden" id="admin-exam-orders" name="orders" value="{{ count($question->options) }}">
            @if ($errors->has('orders'))
                <span class="help-block">
                    <strong>{{ $errors->first('orders') }}</strong>
                </span>
            @endif
        </div>
        <div id="admin-exam-options">
            @foreach ($question->options as $option)
                <div class="form-group{{ pif($errors->has('options.'.$option->order), ' has-error') }}">
                    <label for="option-{{ $option->order }}">选项 {{ chr($option->order + 65) }}</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="{{ 'answers['.$option->order.']' }}"{!! pif(old('answers.'.$option->order) || $question->answer & 1 << $option->order, 'checked="checked"') !!}>
                        </span>
                        <input class="form-control" id="option-{{ $option->order }}" name="{{ 'options['.$option->order.']' }}" value="{{ por(old('options'.$option->order), $option->option) }}">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default admin-exam-remove"><i class="glyphicon glyphicon-minus-sign"></i></button>
                        </span>
                    </div>
                    @if ($errors->has('options.'.$option->order))
                        <span class="help-block">
                            <strong>{{ $errors->first('options.'.$option->order) }}</strong>
                        </span>
                    @endif
                </div>
            @endforeach
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
            <button type="button" class="btn btn-link" id="admin-exam-add-option"><i class="glyphicon glyphicon-plus"></i> 添加选项</button>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">保存</button>
        </div>
    </form>
</div>
@endsection
