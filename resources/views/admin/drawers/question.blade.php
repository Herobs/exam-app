@section('nav')
<li{!! pif($meta->type === 'true-false', ' class="active"') !!}><a href="/admin/{{ $meta->category }}/true-false"><i class="glyphicon glyphicon-ok"></i> 判断题</a></li>
<li{!! pif($meta->type === 'multi-choice', ' class="active"') !!}><a href="/admin/{{ $meta->category }}/multi-choice"><i class="glyphicon glyphicon-list"></i> 选择题</a></li>
<li{!! pif($meta->type === 'blank-fill', ' class="active"') !!}><a href="/admin/{{ $meta->category }}/blank-fill"><i class="glyphicon glyphicon-tasks"></i> 填空题</a></li>
<li{!! pif($meta->type === 'short-answer', ' class="active"') !!}><a href="/admin/{{ $meta->category }}/short-answer"><i class="glyphicon glyphicon-comment"></i> 简答题</a></li>
<li{!! pif($meta->type === 'general', ' class="active"') !!}><a href="/admin/{{ $meta->category }}/general"><i class="glyphicon glyphicon-align-justify"></i> 综合题</a></li>
<li{!! pif($meta->type === 'program', ' class="active"') !!}><a href="/admin/{{ $meta->category }}/program"><i class="glyphicon glyphicon-console"></i> 程序设计题</a></li>
<li class="page-drawer__divider"></li>
<li><a href="/admin"><i class="glyphicon glyphicon-home"></i> 管理首页</a></li>
@endsection
