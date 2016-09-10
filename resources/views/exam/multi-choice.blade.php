@extends('layout.exam')

@section('title', $auth->exam->name)

@section('content')
    <h2>选择题（{{ count($questions) . ' 题 / ' . array_reduce($questions, function($sum, $n) { return $sum + $n->score; }, 0) . ' 分' }}）</h2>
    <form class="exam-questions exam-questions--multi-choice" method="POST" action="{{ url()->current() }}">
        {!! csrf_field() !!}
        <?php $index = 1 ?>
        @foreach($questions as $q)
            <a href="#" id="{{ $q->id }}"></a>
            <div class="exam-questions__question">
                <div class="exam-questions__content markdown-inline" data-order="{{ $index++ }}.">
                    （{{ $q->score.' 分）'.$q->description }}
                </div>
                <div class="exam-questions__answers clearfix">
                    @foreach($q->options as $v => $option)
                        <div class="exam-questions__answer col-sm-12 col-md-6 checkbox">
                            <label>
                                <input type="checkbox" name="{{ $q->id }}[]" value="{{ $v }}"{{ pif($q->answer & 1 << $v, ' checked="checked"').pif($auth->ended, ' disabled') }}>
                                <div class="exam-questions__option" data-order="{{ chr(65 + $option->order) }}.">
                                    {{ $option->option }}
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        @if ($auth->running)
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="保存">
            </div>
        @endif
    </form>
@endsection
