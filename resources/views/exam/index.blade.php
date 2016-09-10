@extends('layout.exam')

@section('title', $auth->exam->name)

@section('content')
<div class="exam-info">
    <div class="exam-info__header">
        <span class="h2">{{ $auth->exam->name }}</span>
        @if ($auth->pending)
            <span class="label label-info">准备中</span>
        @elseif ($auth->ended)
            <span class="label label-warning">已结束</span>
        @else
            <span class="label label-success">进行中</span>
        @endif
    </div>
    <div class="exam-info__time">
        考试时间：{{ $auth->exam->start }} - {{ $auth->exam->start->addSeconds($auth->exam->duration) }}
    </div>
    @if ($auth->pending)
        <div class="exam-info__countdown">
            <h1>距离考试开始</h1>
            <div class="countdown" data-seconds="{{ \Carbon\Carbon::now()->diffInSeconds($auth->exam->start) }}"></div>
        </div>
    @else
        <div class="exam-info__samples">
            <div class="exam-info__sample">
                <span class="exam-info__untouched"></span> 未完成
            </div>
            <div class="exam-info__sample">
                <span class="exam-info__touched"></span> 已完成
            </div>
            <div class="exam-info__sample">
                <span class="exam-info__accepted"></span> 正确
            </div>
            <div class="exam-info__sample">
                <span class="exam-info__wrong"></span> 错误
            </div>
        </div>
        <div class="exam-info__block">
            @foreach($questions as $type => $question)
                @include('exam.questions', ['type' => $type, 'questions' => $question])
            @endforeach
        </div>
    @endif
</div>
@endsection
