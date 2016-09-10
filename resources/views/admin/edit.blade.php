@extends('layout.admin')

@section('title', '编辑资料')

@include('admin.drawers.index', ['active' => ''])

@section('content')
    <div class="row">
        <form class="col-md-8" action="/admin/edit" method="POST">
            {{ csrf_field() }}
            <legend>编辑资料</legend>
            <div class="form-group{{ pif($errors->has('name'), ' has-error') }}">
                <label for="name">姓名</label>
                <input class="form-control" id="name" name="name" value="{{ por(old('name'), $auth->admin->name) }}" />
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label>邮箱</label>
                <input class="form-control" type="email"  value="{{ $auth->admin->email }}" disabled="disabled" />
            </div>
            <div class="form-group{{ pif($errors->has('tel'), ' has-error') }}">
                <label for="tel">手机号码</label>
                <input class="form-control" id="tel" name="tel" value="{{ por(old('tel'), $auth->admin->tel) }}" />
                @if ($errors->has('tel'))
                    <span class="help-block">
                        <strong>{{ $errors->first('tel') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ pif($errors->has('newpass'), ' has-error') }}">
                <label for="newpass">新密码（留空表示不修改）</label>
                <input class="form-control" type="password" id="newpass" name="newpass" />
                @if ($errors->has('newpass'))
                    <span class="help-block">
                        <strong>{{ $errors->first('newpass') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ pif($errors->has('newpass_confirmation'), ' has-error') }}">
                <label for="newpass_confirmation">确认密码</label>
                <input class="form-control" type="password" id="newpass_confirmation" name="newpass_confirmation" />
                @if ($errors->has('newpass_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('newpass_confirmation') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ pif($errors->has('password'), ' has-error') }}">
                <label for="password">原密码</label>
                <input class="form-control" type="password" id="password" name="password" />
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">保存</button>
            </div>
        </form>
    </div>
@endsection
