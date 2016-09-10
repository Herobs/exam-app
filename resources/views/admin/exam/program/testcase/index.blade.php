@extends('layout.admin')

@section('title', '测试数据')

@include('admin.drawers.exam', ['active' => 'program'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li><a href="{{ '/admin/exam/'.$exam->id.'/program' }}">程序设计题</a></li>
    <li class="active">测试数据</li>
</ul>
@if (count($messages))
    <div class="alert alert-warning">
        {{ implode('；', $messages) }}
    </div>
@endif
<div class="btn-toolbar">
    <div class="btn-group">
        @if (count($messages))
            <a href="{{ '/admin/exam/'.$exam->id.'/program/'.$question->id.'/testcase/new' }}" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> 添加</a>
        @endif
        <a href="{{ '/admin/exam/'.$exam->id.'/program/'.$question->id }}" class="btn btn-default"><i class="glyphicon glyphicon-pencil"></i> 编辑题目</a>
    </div>
</div>
<table class="table table-striped table-bordered mt10">
    <thead>
        <tr>
            <td>测试组</td>
            <td style="width: 60%">标准输入</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($question->files as $file)
            <tr>
                <td>{{ $file->case }}</td>
                <td>{{ str_limit($file->content, 48) }}</td>
                <td>
                    <a href="{{ '/admin/exam/'.$exam->id.'/program/'.$question->id.'/testcase/'.$file->case }}">编辑</a>
                    <a class="warning" data-warning="确定要删除该组数据吗？" href="{{ '/admin/exam/'.$exam->id.'/program/'.$question->id.'/testcase/'.$file->case.'/delete' }}">删除</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
