<div class="exam-info__questions clearfix">
    <h3>{{ trans('exam.info.'.$type).'（'.pif($auth->ended, array_reduce($questions, function($sum, $n) { return $sum + $n->scoreGet; }, 0).' / ').array_reduce($questions, function($sum, $n) { return $sum + $n->score; }, 0).' 分）' }}</h3>
    @foreach($questions as $q)
        <a class="exam-info__question exam-info__question--{{ $q->status }}" href="{{ '/exam/'.$auth->exam->id.'/'.$type.pif($type === 'program', '/', '#').$q->id }}">
            {{ pif($auth->ended && $q->scoreGet !== null, $q->scoreGet.' / ').$q->score }}
        </a>
    @endforeach
</div>
