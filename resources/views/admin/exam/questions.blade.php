@extends('layout.admin')

@section('title', $meta->question)

@include('admin.drawers.exam', ['active' => $meta->type])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li class="active">{{ $meta->question }}</li>
</ul>
<div class="btn-toolbar">
    <div class="btn-group">
        @if (hasRight($auth->admin->rights, config('rights.RIGHT_QUESTION')))
            <a href="/admin/exam/{{ $exam->id }}/{{ $meta->type }}/new" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> 新建</a>
        @endif
        <a href="/admin/exam/{{ $exam->id }}/import/private/{{ $meta->type }}" class="btn btn-default"><i class="glyphicon glyphicon-th-large"></i> 个人题库</a>
        <a href="/admin/exam/{{ $exam->id }}/import/public/{{ $meta->type }}" class="btn btn-default"><i class="glyphicon glyphicon-th"></i> 公共题库</a>
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
                <td><a href="{{ '/admin/exam/'.$exam->id.'/'.$meta->type.'/'.$question->id }}">{{ str_limit($question->description, 72) }}</a></td>
                <td>{{ $question->score }}</td>
                <td>
                    <a href="{{ '/admin/exam/'.$exam->id.'/'.$meta->type.'/'.$question->id }}">编辑</a>
                    <a class="warning" data-warning="确定要删除该题吗？" href="{{ '/admin/exam/'.$exam->id.'/'.$meta->type.'/'.$question->id.'/delete' }}">删除</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
