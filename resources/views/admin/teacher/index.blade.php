@extends('layout.admin')

@section('title', '教师列表')

@include('admin.drawers.index', ['active' => 'teachers'])

@section('content')
<ul class="breadcrumb">
    <li class="active">教师列表</li>
</ul>
<div class="row">
    <div class="col-md-8 col-xs-4">
        <div class="btn-toolbar">
            <div class="btn-group">
                <a href="/admin/teacher/new" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i>  添加</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xs-8 text-right">
        <form class="form-inline" action="{{ url()->current() }}" method="GET">
            <div class="form-group">
                <div class="input-group">
                    <input class="form-control" name="keywords" value="{{ request('keywords') }}" placeholder="教师姓名、邮箱" />
                    <div class="input-group-btn">
                        <button class="btn btn-primary" type="submit">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<table class="table table-striped table-bordered mt10">
    <thead>
        <tr>
            <th>姓名</th>
            <th class="hidden-xs">邮箱</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($teachers as $teacher)
            <tr>
                <td><a href="/admin/teacher/{{ $teacher->id }}">{{ $teacher->name }}</a></td>
                <td class="hidden-xs">{{ $teacher->email }}</td>
                <td>
                    <a href="/admin/teacher/{{ $teacher->id }}">编辑</a>
                    <a class="warning" data-warning="确定要删除该教师账号吗？" href="/admin/teacher/{{ $teacher->id }}/delete">删除</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $teachers->links() }}
@endsection
