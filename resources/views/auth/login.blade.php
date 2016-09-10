@extends('layout.app')

@section('title', '登录考试系统')

@section('content')
@include('layout.navbar', ['active' => 'login'])
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form class="form-horizontal login-form form-box" role="form" method="POST" action="/login">
                {!! csrf_field() !!}
                <legend>登录账号</legend>
                <div class="form-group{{ pif($errors->has('email'), ' has-error') }}">
                    <label class="col-md-2 control-label">Email</label>
                    <div class="col-md-8">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ pif($errors->has('password'), ' has-error') }}">
                    <label class="col-md-2 control-label">密码</label>
                    <div class="col-md-8">
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
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> 记住本次登录
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="glyphicon glyphicon-log-in"></i> 登录
                        </button>
                        <div>
                            <a class="btn btn-link" href="{{ url('/register') }}">立即注册</a>
                            <a class="btn btn-link pull-right" href="{{ url('/password/reset') }}">忘记密码？</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layout.footer')
@endsection
