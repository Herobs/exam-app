@extends('exam.master')

@section('title', $auth->exam->name)

@section('content')
    <h2>选择题（{{ count($questions) . ' 题 / ' . array_reduce($questions, function($sum, $n) { return $sum + $n->score; }, 0) . ' 分' }}）</h2>
    <form class="exam-questions exam-questions--multi-choice" method="POST" action="{{ url('exams/' . $auth->exam->id . '/multi-choice') }}">
        {!! csrf_field() !!}
        @foreach($questions as $i => $q)
            <div class="exam-questions__question">
                <div class="exam-questions__content" data-order="{{ $i + 1 }}.">
                    {{ trans('misc.leftBracket') . $q->score . ' 分' . trans('misc.rightBracket') . $q->description }}
                </div>
                <div class="exam-questions__answers clearfix">
                    @foreach($q->options as $v => $option)
                        <div class="exam-questions__answer col-sm-12 col-md-6 checkbox">
                            <label>
                                <input type="checkbox" name="{{ $q->id }}[]" value="{{ $v }}"{{ $q->answer & 1 << $v ? ' checked="checked"' : '' }}>
                                <div class="exam-questions__option" data-order="{{ chr(65 + $option->order) }}.">
                                    {{ $option->option }}
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <div class="form-group">
            <input class="btn btn-primary" type="submit" name="submit" value="保存">
        </div>
    </form>
@endsection
