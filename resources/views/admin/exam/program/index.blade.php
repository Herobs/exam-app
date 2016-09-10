@extends('layout.admin')

@section('title', '程序设计题')

@include('admin.drawers.exam', ['active' => 'program'])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li class="active">程序设计题</li>
</ul>
<div class="btn-toolbar">
    <div class="btn-group">
        @if (hasRight($auth->admin->rights, config('rights.RIGHT_PROGRAM')))
            <a href="/admin/exam/{{ $exam->id }}/program/new" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> 新建</a>
        @endif
        <a href="/admin/exam/{{ $exam->id }}/import/private/program" class="btn btn-default"><i class="glyphicon glyphicon-th-large"></i> 个人题库</a>
        <a href="/admin/exam/{{ $exam->id }}/import/public/program" class="btn btn-default"><i class="glyphicon glyphicon-th"></i> 公共题库</a>
    </div>
</div>
<table class="table table-striped table-bordered mt10">
    <thead>
        <tr>
            <th style="width: 70%">描述</th>
            <th class="hidden-xs">分数</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($questions as $question)
            <tr>
                <td><a href="{{ '/admin/exam/'.$exam->id.'/program/'.$question->id }}">{{ str_limit($question->description, 72) }}</a></td>
                <td class="hidden-xs">{{ $question->score }}</td>
                <td>
                    <a href="{{ '/admin/exam/'.$exam->id.'/program/'.$question->id }}">编辑</a>
                    <a href="{{ '/admin/exam/'.$exam->id.'/program/'.$question->id.'/testcase' }}">数据</a>
                    <a class="warning" data-warning="确定要删除该题吗？" href="{{ '/admin/exam/'.$exam->id.'/program/'.$question->id.'/delete' }}">删除</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
