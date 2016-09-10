@extends('layout.admin')

@section('title', $meta->title)

@include('admin.drawers.question')

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/{{ $meta->category }}">{{ $meta->title }}</a></li>
    <li class="active">{{ $meta->question }}</li>
</ul>
<div class="btn-toolbar">
    <div class="btn-group">
        @if (($meta->category === 'private' && hasRight($auth->admin->rights, config('rights.RIGHT_PROGRAM'))) || ($meta->category === 'public' && hasRight($auth->admin->rights, config('rights.RIGHT_PUBLIC'))))
            <a href="/admin/{{ $meta->category.'/'.$meta->type }}/new" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> 新建</a>
        @endif
    </div>
</div>
<table class="table table-striped table-bordered mt10">
    <thead>
        <tr>
            <th style="width: 70%">描述</th>
            <th>分数</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($questions as $question)
            <tr>
                <td><a href="{{ '/admin/'.$meta->category.'/'.$meta->type.'/'.$question->id }}">{{ str_limit($question->description, 72) }}</a></td>
                <td>{{ $question->score }}</td>
                <td>
                    <a href="{{ '/admin/'.$meta->category.'/'.$meta->type.'/'.$question->id }}">编辑</a>
                    <a href="{{ '/admin/'.$meta->category.'/'.$meta->type.'/'.$question->id.'/testcase' }}">数据</a>
                    <a class="warning" data-warning="确定要删除该题吗？" href="{{ '/admin/'.$meta->category.'/'.$meta->type.'/'.$question->id.'/delete' }}">删除</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $questions->links() }}
@endsection
