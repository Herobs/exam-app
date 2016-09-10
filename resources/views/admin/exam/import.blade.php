@extends('layout.admin')

@section('title', $meta->title)

@include('admin.drawers.exam', ['active' => $meta->type])

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></li>
    <li><a href="/admin/exam/{{ $exam->id.'/'.$meta->type }}">{{ $meta->question }}</a></li>
    <li class="active">{{ $meta->title }}</li>
</ul>
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
                    <a href="/admin/exam/{{ $exam->id }}/import/{{ $meta->category.'/'.$meta->type.'/'.$question->id }}">选择</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $questions->links() }}
@endsection
