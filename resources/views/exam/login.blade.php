@extends('layout.app')

@section('title', '登录 - '.$exam->name)

@section('content')
@include('layout.navbar', ['active' => ''])
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-1 col-md-push-4">
            <form class="form-horizontal exam-login-form form-box" role="form" method="POST" action="{{ url()->current() }}">
                {!! csrf_field() !!}
                <legend>{{ $exam->name }}</legend>
                @if ($exam->type === 'account')
                    <div class="form-group{{ pif($errors->has('student'), ' has-error') }}">
                        <label class="col-md-2 control-label">学号</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="student" value="{{ old('student') }}">
                            @if ($errors->has('student'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('student') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="form-group{{ pif($errors->has('password'), ' has-error') }}">
                    <label class="col-md-2 control-label">密码</label>
                    <div class="col-md-8">
                        @if ($exam->type === 'password')
                            <input type="hidden" name="student" value="{{ Auth::user()->student }}">
                        @endif
                        <input type="password" class="form-control" name="password">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="glyphicon glyphicon-log-in"></i> 登录
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-3 col-md-offset-1 col-md-pull-7 exam-login-info">
            <h3>{{ $exam->name }}</h3>
            <div>
                老师：{{ $teacher->name }}
            </div>
            <div class="exam-status">
                @if ($exam->start > \Carbon\Carbon::now())
                    <div class="exam-status-title">离考试开始</div>
                    <div class="exam-status-countdown">
                        {{ formatSeconds($exam->start->diffInSeconds(\Carbon\Carbon::now()), 'DHM') }}
                    </div>
                @elseif ($exam->start->addSeconds($exam->duration) < \Carbon\Carbon::now())
                    <div class="exam-status-title">考试已经结束</div>
                @else
                    <div class="exam-status-title">离考试结束</div>
                    <div class="exam-status-countdown">
                        {{ formatSeconds($exam->start->addSeconds($exam->duration)->diffInSeconds(\Carbon\Carbon::now()), 'DHM') }}
                    </div>
                @endif
            </div>
            <div class="exam-hint">
                @if ($exam->type === 'account')
                    本场考试需要您使用老师发放的账号进行登录。具体账号请从您的老师处获取。
                @else
                    本场考试需要您登录考试系统，并且输入公共密码。具体密码请从您的老师处获取。
                @endif
            </div>
        </div>
    </div>
</div>
@include('layout.footer')
@endsection
