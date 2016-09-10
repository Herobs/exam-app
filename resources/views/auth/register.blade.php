@extends('layout.app')

@section('title', '注册账号')

@section('content')
@include('layout.navbar', ['active' => 'register'])
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal register-form form-box" role="form" method="POST" action="{{ url('/register') }}">
                {!! csrf_field() !!}
                <legend>注册账号</legend>
                <div class="form-group{{ pif($errors->has('name'), ' has-error') }}">
                    <label class="col-md-2 control-label">姓名 *</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ pif($errors->has('email'), ' has-error') }}">
                    <label class="col-md-2 control-label">Email *</label>

                    <div class="col-md-8">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ pif($errors->has('student'), ' has-error') }}">
                    <label class="col-md-2 control-label">学号 *</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="student" value="{{ old('student') }}">

                        @if ($errors->has('student'))
                            <span class="help-block">
                                <strong>{{ $errors->first('student') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ pif($errors->has('major'), ' has-error') }}">
                    <label class="col-md-2 control-label">专业</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="major" value="{{ old('major') }}">

                        @if ($errors->has('major'))
                            <span class="help-block">
                                <strong>{{ $errors->first('major') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ pif($errors->has('password'), ' has-error') }}">
                    <label class="col-md-2 control-label">密码 *</label>

                    <div class="col-md-8">
                        <input type="password" class="form-control" name="password">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ pif($errors->has('password_confirmation'), ' has-error') }}">
                    <label class="col-md-2 control-label">确认密码 *</label>

                    <div class="col-md-8">
                        <input type="password" class="form-control" name="password_confirmation">

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="submit" class="btn btn-primary btn-block">注册</button>
                    </div>
                </div>
            </form>
            <div class="alert alert-warning">
                <ul>
                    <li>Email 将用来登录账号和找回密码。</li>
                    <li>请在姓名和学号处填写您的真实信息，否则老师将不能获取您的有效成绩。</li>
                    <li>请填写完整的专业名称。</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@include('layout.footer')
@endsection
