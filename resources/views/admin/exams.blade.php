@extends('layout.admin')

@section('title', '我的考试')

@include('admin.drawers.index', ['active' => 'exams'])

@section('content')
<div class="btn-toolbar">
    <div class="btn-group">
        @if (hasRight($auth->admin->rights, config('rights.RIGHT_EXAM')))
            <a href="/admin/exam/new" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i>  添加</a>
        @endif
    </div>
</div>
<table class="table table-striped table-bordered mt10">
    <thead>
        <tr>
            <th style="width: 50%">考试名称</th>
            <th class="hidden-xs">开始时间</th>
            <th class="hidden-xs">持续时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($exams as $exam)
            <tr>
                <td><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></td>
                <td class="hidden-xs">{{ $exam->start }}</td>
                <td class="hidden-xs">{{ formatSeconds($exam->duration) }}</td>
                <td><a href="/admin/exam/{{ $exam->id }}">编辑</a> <a class="warning" data-warning="确定要删除该场考试吗？" href="/admin/exam/{{ $exam->id }}/delete">删除</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $exams->links() }}
@endsection
