@extends('layout.exam')

@section('title', $auth->exam->name)

@section('content')
    <h2 class="exam-question-type">程序设计题（{{ count($questions) . ' 题 / ' . array_reduce($questions->all(), function($sum, $n) { return $sum + $n->score; }, 0) . ' 分' }}）</h2>
    <div class="exam-program-nav clearfix">
        @foreach($questions as $q)
            <a class="exam-program-nav__item exam-program-nav__item--{{ $q->status.pif($q->id === $question->id, ' is-active') }}" href="{{ '/exam/'.$auth->exam->id.'/program/'.$q->id }}">{{ $q->score }}</a>
        @endforeach
    </div>
    <form class="exam-questions exam-questions--program" method="POST" action="{{ url()->current() }}">
        {!! csrf_field() !!}
        <div class="exam-program">
            <div class="exam-program__title">
                {{ $question->title.'（'.$question->score.' 分' . '）' }}
            </div>
            <div class="exam-program__description markdown">{{ $question->description }}</div>
            <div class="exam-program__answer">
                <textarea class="form-control" name="code" rows="8" cols="40"{{ pif($auth->ended, ' disabled') }}>{{ $question->answer }}</textarea>
            </div>
            @if ($auth->running)
                <div class="exam-program__control">
                    <input class="btn btn-primary" type="submit" name="submit" value="运行">
                    <label for="">语言</label>
                    <select class="form-control exam-program__language" name="language" id="language">
                        <option value="c">C</option>
                        <option value="c++">C++</option>
                        <option value="java">Java</option>
                    </select>
                </div>
            @endif
        </div>
    </form>
@endsection
