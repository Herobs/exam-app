@extends('layout.app')

@section('title', '密码重置')

@section('content')
@include('layout.navbar', ['active' => 'password/reset'])
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal form-box" role="form" method="POST" action="/admin/password/reset">
                <legend>密码重置</legend>
                {!! csrf_field() !!}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group{{ pif($errors->has('email'), ' has-error') }}">
                    <label class="col-md-2 control-label">电子邮箱地址</label>

                    <div class="col-md-6">
                        <input type="email" class="form-control" name="email" value="{{ $email or old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ pif($errors->has('password'), ' has-error') }}">
                    <label class="col-md-2 control-label">新密码</label>

                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ pif($errors->has('password_confirmation'), ' has-error') }}">
                    <label class="col-md-2 control-label">确认密码</label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password_confirmation">

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="glyphicon glyphicon-refresh"></i> 确认重置
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
