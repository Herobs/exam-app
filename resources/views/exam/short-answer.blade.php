@extends('layout.exam')

@section('title', $auth->exam->name)

@section('content')
    <h2 class="exam-question-type">简答题（{{ count($questions) . ' 题 / ' . array_reduce($questions->all(), function($sum, $n) { return $sum + $n->score; }, 0) . ' 分' }}）</h2>
    <form class="exam-questions exam-questions--short-answer" method="POST" action="{{ url()->current() }}">
        {!! csrf_field() !!}
        @foreach($questions as $i => $q)
            <?php if (isset($answers[$q->id])) $q->answer = $answers[$q->id]->answer ?>
            <a href="#" id="{{ $q->id }}"></a>
            <div class="exam-questions__question">
                <div class="exam-questions__content markdown-inline" data-order="{{ $i + 1 }}.">
                    （{{ $q->score . ' 分' . '）' . $q->description }}
                </div>
                <div class="exam-questions__answer">
                    <textarea class="form-control" name="{{ $q->id }}" rows="8" cols="40"{{ pif($auth->ended, ' disabled') }}>{{ $q->answer }}</textarea>
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
