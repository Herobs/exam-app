@section('nav')
<li{!! pif($active === 'index', ' class="active"') !!}><a href="/admin"><i class="glyphicon glyphicon-home"></i> 管理首页</a></li>
<li{!! pif($active === 'exams', ' class="active"') !!}><a href="/admin/exams"><i class="glyphicon glyphicon-list"></i> 我的考试</a></li>
<li{!! pif($active === 'private', ' class="active"') !!}><a href="/admin/private"><i class="glyphicon glyphicon-th-large"></i> 我的题库</a></li>
<li{!! pif($active === 'public', ' class="active"') !!}><a href="/admin/public"><i class="glyphicon glyphicon-th"></i> 公共题库</a></li>
@if (hasRight($auth->admin->rights, config('rights.RIGHT_ADMIN')))
    <li class="page-drawer__divider"></li>
    <li{!! pif($active === 'teachers', ' class="active"') !!}><a href="/admin/teachers"><i class="glyphicon glyphicon-user"></i> 教师管理</a></li>
@endif
@endsection
