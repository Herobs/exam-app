@extends('layout.admin')

@section('title', '编辑程序设计题')

@include('admin.drawers.question')

@section('content')
<ul class="breadcrumb">
    <li><a href="/admin/{{ $meta->category }}">{{ $meta->title }}</a></li>
    <li><a href="/admin/{{ $meta->category.'/'.$meta->type }}">{{ $meta->question }}</a></li>
    <li class="active">编辑题目</li>
</ul>
<div class="alert alert-info">
    <p>必须提供默认的时间限制和内存限制，为各种语言设置单独的限制不是必须的。</p>
    <p>但是推荐默认限制设置为一个较小的值，为 Java 这类的语言单独设置较大的限制（Java 的时间限制一般是 C/C++ 的 2 倍）。</p>
    <p>时间限制的推荐值为 1000 毫秒，内存限制的推荐值为 65535 字节。</p>
</div>
<div class="row">
    <form class="col-md-8" action="/admin/{{ $meta->category.'/'.$meta->type }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $question->id }}">
        <legend>编辑题目</legend>
        @if ($errors->has('judge'))
            <div class="alert alert-danger">
                {{ $errors->first('judge') }}
            </div>
        @endif
        <div class="form-group{{ pif($errors->has('title'), ' has-error') }}">
            <label for="title">标题（可为空）</label>
            <input class="form-control" id="title" name="title" value="{{ por(old('title'), $program->title) }}">
            @if ($errors->has('title'))
                <span class="help-block">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('description'), ' has-error') }}">
            <label for="description">描述</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ por(old('description'), $question->description) }}</textarea>
            @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group hidden" id="admin-exam-limit-example">
            <label></label>
            <div class="input-group">
                <input class="form-control">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default admin-exam-remove"><i class="glyphicon glyphicon-minus-sign"></i></button>
                </span>
            </div>
        </div>
        <div id="admin-exam-limits">
            @foreach($question->limits as $limit)
                <div class="form-group{{ pif($errors->has('limits.'.$limit->type.'.'.$limit->language), ' has-error') }}">
                    <label for="{{ 'limit-'.$limit->type.'-'.$limit->language }}">{{ pif($limit->language === 'default', '默认', studly_case($limit->language).' ').pif($limit->type === 'time', '时间限制（毫秒）', '内存限制（字节）') }} </label>
                    <div class="input-group">
                        <input class="form-control" id="{{ 'limit-'.$limit->type.'-'.$limit->language }}" name="{{ 'limits['.$limit->type.']['.$limit->language.']' }}" value="{{ por(old('limits.'.$limit->type.'.'.$limit->language), $limit->value) }}">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default{{ pif($limit->language !== 'default', ' admin-exam-remove') }}"><i class="glyphicon glyphicon-minus-sign"></i></button>
                        </span>
                    </div>
                    @if ($errors->has('limits.'.$limit->type.'.'.$limit->language))
                        <span class="help-block">
                            <strong>{{ $errors->first('limits.'.$limit->type.'.'.$limit->language) }}</strong>
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">类型</span>
                <select class="form-control">
                    <option value="time">时间限制</option>
                    <option value="memory">内存限制</option>
                </select>
                <span class="input-group-addon">语言</span>
                @include('layout.languages')
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="admin-exam-add-limit">添加</button>
                </span>
            </div>
        </div>
        <div class="form-group{{ pif($errors->has('outputlimit'), ' has-error') }}">
            <label for="outputlimit">输出限制（单位 KB，略大于最大的标准输出文件大小）</label>
            <input class="form-control" id="outputlimit" name="outputlimit" value="{{ por(old('outputlimit'), $program->output_limit) }}">
            @if ($errors->has('outputlimit'))
                <span class="help-block">
                    <strong>{{ $errors->first('outputlimit') }}</strong>
                </span>
            @endif
        </div>
        {{--}}
        <div class="form-group{{ pif($errors->has('specialjudge'), ' has-error') }}">
            <label for="specialjudge">特判答案</label>
            <select class="form-control" id="specialjudge" name="specialjudge">
                <option value="0"{!! pif(old('specialjudge') === 0 || $program->special_judge === 0, ' selected="selected"') !!}>否</option>
                <option value="1"{!! pif(old('specialjudge') === 1 || $program->special_judge === 1, ' selected="selected"') !!}>是</option>
            </select>
            @if ($errors->has('specialjudge'))
                <span class="help-block">
                    <strong>{{ $errors->first('specialjudge') }}</strong>
                </span>
            @endif
        </div>
        {{--}}
        <div class="form-group{{ pif($errors->has('testcase'), ' has-error') }}">
            <label for="testcase">测试数据组数</label>
            <input class="form-control" id="testcase" name="testcase" value="{{ por(old('testcase'), $program->test_case) }}">
            @if ($errors->has('testcase'))
                <span class="help-block">
                    <strong>{{ $errors->first('testcase') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ pif($errors->has('score'), ' has-error') }}">
            <label for="score">分数</label>
            <input class="form-control" id="score" name="score" value="{{ por(old('score'), $question->score) }}">
            @if ($errors->has('score'))
                <span class="help-block">
                    <strong>{{ $errors->first('score') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">保存</button>
        </div>
    </form>
</div>
@endsection
