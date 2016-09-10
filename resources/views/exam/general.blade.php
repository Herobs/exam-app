@extends('layout.exam')

@section('title', $auth->exam->name)

@section('content')
    <h2 class="exam-question-type">综合题（{{ count($questions) . ' 题 / ' . array_reduce($questions->all(), function($sum, $n) { return $sum + $n->score; }, 0) . ' 分' }}）</h2>
    <form class="exam-questions exam-questions--general" method="POST" enctype="multipart/form-data" action="{{ url()->current() }}">
        {!! csrf_field() !!}
        @foreach($questions as $i => $q)
            <?php if (isset($answers[$q->id])) $q->answer = $answers[$q->id]->answer ?>
            <a href="#" id="{{ $q->id }}"></a>
            <div class="exam-questions__question">
                <div class="exam-questions__content markdown-inline" data-order="{{ $i + 1 }}.">
                    （{{ $q->score . ' 分' . '）' . $q->description }}
                </div>
                <div class="exam-questions__file">
                    <label for="file{{ $q->id }}" {!! pif($auth->ended, ' class="disabled"') !!}>
                        @if ($q->answer)
                            已经提交文件：{{ $q->answer }}
                        @else
                            <i class="glyphicon glyphicon-open"></i> 选择文件
                        @endif
                    </label>
                    <input type="file" id="file{{ $q->id }}" name="{{ $q->id }}"{{ pif($auth->ended, ' disabled') }}>
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
