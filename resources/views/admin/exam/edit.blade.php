@extends('layout.admin')

@section('title', $exam->name)

@include('admin.drawers.exam', ['active' => 'index'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li class="active">编辑考试</li>
</ul>
<div class="alert alert-info">
@if ($exam->type === 'student')
    <p>本场考试类型为指定学号，导入可参加学生的学号即可。考生信息在登录时会自动填充，不可以更改。</p>
@elseif ($exam->type === 'account')
    <p>本场考试类型为导入账号，只有通过导入的账号才能参加本场考试。</p>
@else
    <p>本场考试类型为公共密码，知道密码即可参加本场考试。请设置较为复杂的密码，以免过多的非您的学生账号影响成绩统计。</p>
@endif
    <p>建议不要在考试开始后更改考试类型，以免引起未知的错误。</p>
</div>
<div class="row">
    <form class="col-md-8" action="/admin/exam" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $exam->id }}">
        <legend>{{ $exam->name }}</legend>
        <div class="form-group{{ pif($errors->has('name'), ' has-error') }}">
            <label for="name">考试名称</label>
            <input class="form-control" type="text" id="name" name="name" value="{{ por(old('name'), $exam->name) }}">
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('start'), ' has-error') }}">
            <label for="start">开始时间 <i data-toggle="tooltip" data-placement="top" title="格式：年-月-日 时:分:秒" class="glyphicon glyphicon-question-sign"></i></label>
            <input class="form-control" type="text" id="start" name="start" value="{{ por(old('start'), $exam->start) }}">
            @if ($errors->has('start'))
                <span class="help-block">
                    <strong>{{ $errors->first('start') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('hours') || $errors->has('minutes'), ' has-error') }}">
            <label for="hours">持续时间</label>
            <div class="input-group">
                <input type="text" class="form-control" id="hours" name="hours" value={{ por(old('hours'), floor($exam->duration / 3600)) }}>
                <div class="input-group-addon">小时</div>
                <input type="text" class="form-control" id="minutes" name="minutes" value={{ por(old('minutes'), floor($exam->duration % 3600 / 60)) }}>
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
                <option value="student"{!! pif(old('type') === 'student' || $exam->type === 'student', ' selected="selected"') !!}>指定学号</option>
                <option value="account"{!! pif(old('type') === 'account' || $exam->type === 'account', ' selected="selected"') !!}>导入账号</option>
                <option value="password"{!! pif(old('type') === 'password' || $exam->type === 'password', ' selected="selected"') !!}>公共密码</option>
            </select>
            @if ($errors->has('type'))
                <span class="help-block">
                    <strong>{{ $errors->first('type') }}</strong>
                </span>
            @endif
        </div>
        <div id="admin-exam-password" class="form-group admin-exam-password{{ pif($errors->has('password'), ' has-error').pif(old('type') === 'password' || $exam->type === 'password', ' admin-exam-password--show') }}">
            <label for="password">公共密码</label>
            <input class="form-control" type="text" id="password" name="password" value="{{ por(old('password'), $exam->password) }}">
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('hidden'), ' has-error') }}">
            <label for="hidden">隐藏考试</label>
            <select class="form-control" id="hidden" name="hidden">
                <option value="false"{!! pif(old('type') === 'false' || $exam->type === 'false', ' selected="selected"') !!}>否</option>
                <option value="true"{!! pif(old('type') === 'true' || $exam->type === 'true', ' selected="selected"') !!}>是</option>
            </select>
            @if ($errors->has('hidden'))
                <span class="help-block">
                    <strong>{{ $errors->first('hidden') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">保存</button>
        </div>
    </form>
</div>
@endsection
