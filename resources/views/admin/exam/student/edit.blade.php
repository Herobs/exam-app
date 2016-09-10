@extends('layout.admin')

@section('title', '编辑考生')

@include('admin.drawers.exam', ['active' => 'student'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li><a href="/admin/exam/{{ $exam->id }}/student">学生信息</a></li>
    <li class="active">编辑考生</li>
</ul>
<div class="row">
    <form class="col-md-8" action="/admin/exam/{{ $exam->id }}/student" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $student->id }}">
        <legend>编辑考生</legend>
        <div class="form-group{{ pif($errors->has('name'), ' has-error') }}">
            <label for="name">姓名</label>
            <input class="form-control" type="text" id="name" name="name" value="{{ por(old('name'), $student->name) }}">
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('student'), ' has-error') }}">
            <label for="student">学号</label>
            <input class="form-control" type="text" id="student" name="student" value="{{ por(old('student'), $student->student) }}">
            @if ($errors->has('student'))
                <span class="help-block">
                    <strong>{{ $errors->first('student') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('major'), ' has-error') }}">
            <label for="major">专业</label>
            <input class="form-control" type="text" id="major" name="major" value="{{ por(old('major'), $student->major) }}">
            @if ($errors->has('major'))
                <span class="help-block">
                    <strong>{{ $errors->first('major') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('password'), ' has-error') }}">
            <label for="password">密码（留空表示不更改密码）</label>
            <input class="form-control" type="text" id="password" name="password">
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
