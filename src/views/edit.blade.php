@extends('panelViews::mainTemplate')
@section('page-wrapper')

    @if ($helper_message)
	<div>&nbsp;</div>
	<div class="alert alert-info">
		<h3 class="help-title">{{ trans('rapyd::rapyd.help') }}</h3>
		{{ $helper_message }}
	</div>
    @endif

    @if (isset($messages))
        <?php dd($messages); ?>
        @foreach($messages->all() as $message)
        <div>&nbsp;</div>
        <div class="alert alert-info">
            <h3 class="help-title">{{ trans('rapyd::rapyd.help') }}</h3>
            {{ $message }}
        </div>
        @endforeach
    @endif

    <p>
        {!! $edit !!}
    </p>
@stop
