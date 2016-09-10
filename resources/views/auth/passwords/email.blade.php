@extends('layout.app')

@section('title', '请求密码重置')

@section('content')
@include('layout.navbar', ['active' => 'password/email'])
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <form class="form-horizontal form-box" role="form" method="POST" action="/password/email">
                <legend>请求密码重置</legend>
                {!! csrf_field() !!}
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

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="glyphicon glyphicon-envelope"></i> 发送密码重置链接
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layout.footer')
@endsection
