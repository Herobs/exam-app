@extends('layout.admin')

@section('title', '导入账号')

@include('admin.drawers.exam', ['active' => 'student'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li><a href="/admin/exam/{{ $exam->id }}/student">学生信息</a></li>
    <li class="active">导入账号</li>
</ul>
<div class="alert alert-info">
    @if ($exam->type === 'student')
        <p>导入文件第一行为标题行，应包含字段 <code>student</code>。其中 student 为学号，最长长度为 16。</p>
        <p>
            <a class="btn btn-primary" href="/downloads/accounts.csv"><i class="glyphicon glyphicon-download-alt"></i> 模板文件下载</a>
            模板文件为 csv 格式，也可以上传 xls 和 xlsx 格式的账号文件。
        </p>
    @elseif ($exam->type === 'account')
        <p>
            导入文件第一行为标题行，应包含字段 <code>name</code>、<code>student</code>、<code>major</code>、<code>password</code>。
            其中 name 为学生姓名，最长长度为 16；
            student 为学号，最长长度为 16；
            major 为专业，可以为空，最长长度为 32；
            password 为学生登录密码，长度应该要不少于 6 位。
        </p>
        <p>
            <a class="btn btn-primary" href="/downloads/students.csv"><i class="glyphicon glyphicon-download-alt"></i> 模板文件下载</a>
            模板文件为 csv 格式，也可以上传 xls 和 xlsx 格式的账号文件。
        </p>
    @endif
</div>
<div class="row">
    <form class="col-md-8" action="/admin/exam/{{ $exam->id }}/student/import" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group{{ pif($errors->has('students'), ' has-error') }}">
            <label for="students">账号文件</label>
            <input class="form-control" type="file" id="students" name="students">
            @if ($errors->has('students'))
                <span class="help-block">
                    <strong>{{ $errors->first('students') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">导入</button>
        </div>
    </form>
</div>
@endsection
