@extends('layout.exam')

@section('title', $auth->exam->name)

@section('content')
    <h2>填空题（{{ count($questions) . ' 题 / ' . array_reduce($questions->all(), function($sum, $n) { return $sum + $n->score; }, 0) . ' 分' }}）</h2>
    <form class="exam-questions exam-questions--blank-fill" method="POST" action="{{ url()->current() }}">
        {!! csrf_field() !!}
        @foreach($questions as $i => $q)
            <a href="#" id="{{ $q->id }}"></a>
            <?php $order = 0; if (isset($answers[$q->id])) $q->answer = $answers[$q->id]->answer; ?>
            <div class="exam-questions__question">
                <div class="exam-questions__content markdown-inline" data-order="{{ $i + 1 }}.">
                    （{{ $q->score }}  分）{!! $q->description !!}
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
