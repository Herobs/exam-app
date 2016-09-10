@extends('layout.app')

@section('title', '申请教师账号')

@section('content')
@include('layout.navbar', ['active' => 'register'])
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal register-form form-box" role="form" method="POST" action="/admin/register">
                {!! csrf_field() !!}
                <legend>申请教师账号</legend>
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

                <div class="form-group{{ pif($errors->has('tel'), ' has-error') }}">
                    <label class="col-md-2 control-label">手机号码 *</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="tel" value="{{ old('tel') }}">

                        @if ($errors->has('tel'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tel') }}</strong>
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
                        <button type="submit" class="btn btn-primary btn-block">提交申请</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layout.footer')
@endsection
