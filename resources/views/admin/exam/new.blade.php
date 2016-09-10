@extends('layout.admin')

@section('title', '添加考试')

@include('admin.drawers.index', ['active' => 'exams'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin">管理首页</a></li>
    <li class="active">添加考试</li>
</ul>
<div class="row">
    <form class="col-md-8" action="{{ '/admin/exam' }}" method="POST">
        {{ csrf_field() }}
        <legend>添加考试</legend>
        <div class="form-group{{ pif($errors->has('name'), ' has-error') }}">
            <label for="name">考试名称</label>
            <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}">
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('start'), ' has-error') }}">
            <label for="start">开始时间 <i data-toggle="tooltip" data-placement="top" title="格式：xxxx-xxx-xx xx:xx:xx" class="glyphicon glyphicon-question-sign"></i></label>
            <input class="form-control" type="text" id="start" name="start" value="{{ por(old('start'), \Carbon\Carbon::now()) }}">
            @if ($errors->has('start'))
                <span class="help-block">
                    <strong>{{ $errors->first('start') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('hours') || $errors->has('minutes'), ' has-error') }}">
            <label for="hours">持续时间</label>
            <div class="input-group">
                <input type="text" class="form-control" id="hours" name="hours" value={{ por(old('hours'), 5) }}>
                <div class="input-group-addon">小时</div>
                <input type="text" class="form-control" id="minutes" name="minutes" value={{ por(old('minutes'), 0) }}>
                <div class="input-group-addon">分钟</div>
            </div>
            @if ($errors->has('hours') || $errors->has('minutes'))
                <span class="help-block">
                    <strong>{{ por($errors->first('hours'), $errors->first('minutes')) }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('type'), ' has-error') }}">
            <label for="admin-exam-type">考试类型</label>
            <select class="form-control" id="admin-exam-type" name="type">
                <option value="student"{!! pif(old('type') === 'student', ' selected="selected"') !!}>指定学号</option>
                <option value="account"{!! pif(old('type') === 'account', ' selected="selected"') !!}>导入账号</option>
                <option value="password"{!! pif(old('type') === 'password', ' selected="selected"') !!}>公共密码</option>
            </select>
            @if ($errors->has('type'))
                <span class="help-block">
                    <strong>{{ $errors->first('type') }}</strong>
                </span>
            @endif
        </div>
        <div id="admin-exam-password" class="form-group admin-exam-password{{ pif($errors->has('password'), ' has-error').pif(old('type') === 'password', ' admin-exam-password--show') }}">
            <label for="password">公共密码</label>
            <input class="form-control" type="text" id="password" name="password" value="{{ old('password') }}">
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('hidden'), ' has-error') }}">
            <label for="hidden">隐藏考试</label>
            <select class="form-control" id="hidden" name="hidden">
                <option value="false"{!! pif(old('type') === 'false', ' selected="selected"') !!}>否</option>
                <option value="true"{!! pif(old('type') === 'true', ' selected="selected"') !!}>是</option>
            </select>
            @if ($errors->has('hidden'))
                <span class="help-block">
                    <strong>{{ $errors->first('hidden') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">添加</button>
        </div>
    </form>
</div>
@endsection
