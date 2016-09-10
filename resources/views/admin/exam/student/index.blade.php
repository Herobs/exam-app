@extends('layout.admin')

@section('title', '学生信息')

@include('admin.drawers.exam', ['active' => 'student'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li class="active">学生信息</li>
</ul>
<div class="btn-toolbar">
    <div class="btn-group">
        @unless ($exam->type === 'password')
            <a href="/admin/exam/{{ $exam->id }}/student/new" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> 添加</a>
            <a href="/admin/exam/{{ $exam->id }}/student/import" class="btn btn-default"><i class="glyphicon glyphicon-import"></i> 导入</a>
        @endunless
        <a href="/admin/exam/{{ $exam->id }}/student/export" class="btn btn-default"><i class="glyphicon glyphicon-export"></i> 导出</a>
    </div>
    <div class="btn-group">
        <a href="/admin/exam/{{ $exam->id }}/student/clear" class="btn btn-default warn" data-content="确定要删除所有的学生信息吗？"><i class="glyphicon glyphicon-trash"></i> 清空</a>
    </div>
</div>
<table class="table table-striped table-bordered mt10">
    <thead>
        <tr>
            <th>姓名</th>
            <th>学号</th>
            <th>专业</th>
            <th class="hidden-xs">上次登录时间</th>
            <th class="hidden-xs">IP 地址</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
        <tr>
            <td>{{ $student->name }}</td>
            <td>{{ $student->student }}</td>
            <td>{{ $student->major }}</td>
            <td class="hidden-xs">{{ $student->last }}</td>
            <td class="hidden-xs">{{ $student->ip }}</td>
            <td>
            @if ($exam->type === 'account')
                <a href="/admin/exam/{{ $exam->id }}/student/{{ $student->id }}">编辑</a>
            @endif
                <a class="warning" data-warning="确定要删除该学生的信息吗？" href="/admin/exam/{{ $exam->id }}/student/{{ $student->id }}/delete">删除</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
