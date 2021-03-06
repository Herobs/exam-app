@extends('layout.admin')

@section('title', '编辑教师信息')

@include('admin.drawers.index', ['active' => 'teachers'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/teachers">教师列表</a></li>
    <li class="active">编辑教师信息</li>
</ul>
<div class="row">
    <form class="col-md-8" action="/admin/teacher" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $teacher->id }}">
        <legend>编辑教师信息</legend>
        <div class="form-group{{ pif($errors->has('name'), ' has-error') }}">
            <label for="name">姓名</label>
            <input class="form-control" id="name" name="name" value="{{ por(old('name'), $teacher->name) }}" />
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('email'), ' has-error') }}">
            <label for="email">邮箱</label>
            <input class="form-control" type="email" id="email" name="email" value="{{ por(old('email'), $teacher->email) }}" />
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('tel'), ' has-error') }}">
            <label for="tel">手机号码</label>
            <input class="form-control" id="tel" name="tel" value="{{ por(old('tel'), $teacher->tel) }}" />
            @if ($errors->has('tel'))
                <span class="help-block">
                    <strong>{{ $errors->first('tel') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>权限</label>
            <div class="row checkboxs">
                <div class="checkbox col-md-3 col-sm-4 col-xs-6">
                    <label><input type="checkbox" name="rights[{{ config('rights.RIGHT_LOGIN') }}]"{!! pif(old('rights.'.config('rights.RIGHT_LOGIN')) || hasRight($teacher->rights, config('rights.RIGHT_LOGIN')), ' checked="checked"') !!}> 登录系统</label>
                </div>
                <div class="checkbox col-md-3 col-sm-4 col-xs-6">
                    <label><input type="checkbox" name="rights[{{ config('rights.RIGHT_ADMIN') }}]"{!! pif(old('rights.'.config('rights.RIGHT_ADMIN')) || hasRight($teacher->rights, config('rights.RIGHT_ADMIN')), ' checked="checked"') !!}> 管理教师</label>
                </div>
                <div class="checkbox col-md-3 col-sm-4 col-xs-6">
                    <label><input type="checkbox" name="rights[{{ config('rights.RIGHT_SUPER') }}]"{!! pif(old('rights.'.config('rights.RIGHT_SUPER')) || hasRight($teacher->rights, config('rights.RIGHT_SUPER')), ' checked="checked"') !!}> 超级管理员</label>
                </div>
                <div class="checkbox col-md-3 col-sm-4 col-xs-6">
                    <label><input type="checkbox" name="rights[{{ config('rights.RIGHT_EXAM') }}]"{!! pif(old('rights.'.config('rights.RIGHT_EXAM')) || hasRight($teacher->rights, config('rights.RIGHT_EXAM')), ' checked="checked"') !!}> 创建考试</label>
                </div>
                <div class="checkbox col-md-3 col-sm-4 col-xs-6">
                    <label><input type="checkbox" name="rights[{{ config('rights.RIGHT_QUESTION') }}]"{!! pif(old('rights.'.config('rights.RIGHT_QUESTION')) || hasRight($teacher->rights, config('rights.RIGHT_QUESTION')), ' checked="checked"') !!}> 创建题目</label>
                </div>
                <div class="checkbox col-md-3 col-sm-4 col-xs-6">
                    <label><input type="checkbox" name="rights[{{ config('rights.RIGHT_PROGRAM') }}]"{!! pif(old('rights.'.config('rights.RIGHT_PROGRAM')) || hasRight($teacher->rights, config('rights.RIGHT_PROGRAM')), ' checked="checked"') !!}> 创建编程题</label>
                </div>
                <div class="checkbox col-md-3 col-sm-4 col-xs-6">
                    <label><input type="checkbox" name="rights[{{ config('rights.RIGHT_PUBLIC') }}]"{!! pif(old('rights.'.config('rights.RIGHT_PUBLIC')) || hasRight($teacher->rights, config('rights.RIGHT_PUBLIC')), ' checked="checked"') !!}> 管理公共题库</label>
                </div>
            </div>
        </div>
        <div class="form-group{{ pif($errors->has('password'), ' has-error') }}">
            <label for="password">密码（留空表示不修改）</label>
            <input class="form-control" id="password" name="password" value="{{ old('password') }}" />
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">保存</button>
        </div>
    </form>
</div>
@endsection
