@extends('layout.admin')

@section('title', '管理考试')

@include('admin.drawers.index', ['active' => 'index'])

@section('content')
<h2>我的考试</h2>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>考试名称</th>
            <th>开始时间</th>
        </tr>
    </thead>
    <tbody>
        @foreach($exams as $exam)
            <tr>
                <td><a href="/admin/exam/{{ $exam->id }}">{{ $exam->name }}</a></td>
                <td>{{ $exam->start }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="see-all see-all--under-table">
    <a href="/admin/exams">全部考试 &gt;&gt;</a>
</div>
@endsection
