@if (isset($auth->questions[$item]))
    <li{!! pif($active === $item, ' class="active"') !!}>
        <a href="{{ '/exam/'.$auth->exam->id.'/'.$item }}"><i class="glyphicon glyphicon-{{ $icon }}"></i> {{ trans('exam.info.'.$item) }}</a>
    </li>
@endif
