@section('nav')
<li{!! pif($active === 'index', ' class="active"') !!}><a href="/admin/exam/{{ $exam->id }}"><i class="glyphicon glyphicon-pencil"></i> 考试信息</a></li>
<li{!! pif($active === 'student', ' class="active"') !!}><a href="/admin/exam/{{ $exam->id }}/student"><i class="glyphicon glyphicon-option-horizontal"></i> 学生信息</a></li>
@if (ended($exam))
<li{!! pif($active === 'analyse', ' class="active"') !!}><a href="/admin/exam/{{ $exam->id }}/student"><i class="glyphicon glyphicon-stats"></i> 成绩分析</a></li>
@endif
<li class="page-drawer__divider"></li>
<li{!! pif($active === 'true-false', ' class="active"') !!}><a href="/admin/exam/{{ $exam->id }}/true-false"><i class="glyphicon glyphicon-ok"></i> 判断题</a></li>
<li{!! pif($active === 'multi-choice', ' class="active"') !!}><a href="/admin/exam/{{ $exam->id }}/multi-choice"><i class="glyphicon glyphicon-list"></i> 选择题</a></li>
<li{!! pif($active === 'blank-fill', ' class="active"') !!}><a href="/admin/exam/{{ $exam->id }}/blank-fill"><i class="glyphicon glyphicon-tasks"></i> 填空题</a></li>
<li{!! pif($active === 'short-answer', ' class="active"') !!}><a href="/admin/exam/{{ $exam->id }}/short-answer"><i class="glyphicon glyphicon-comment"></i> 简答题</a></li>
<li{!! pif($active === 'general', ' class="active"') !!}><a href="/admin/exam/{{ $exam->id }}/general"><i class="glyphicon glyphicon-align-justify"></i> 综合题</a></li>
<li{!! pif($active === 'program', ' class="active"') !!}><a href="/admin/exam/{{ $exam->id }}/program"><i class="glyphicon glyphicon-console"></i> 程序设计题</a></li>
<li class="page-drawer__divider"></li>
<li><a href="/admin"><i class="glyphicon glyphicon-home"></i> 管理首页</a></li>
@endsection
