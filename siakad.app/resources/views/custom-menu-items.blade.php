@foreach($items as $item)
	<li @if($item->hasChildren()) class="mm-dropdown {{$item->active()?'active':''}}" @endif>
		<a href="{!! $item->url() !!}"><span class="mm-text">{!! $item->title !!}</span></a>
		
		@if($item->hasChildren())
			<ul> @include('custom-menu-items', ['items' => $item->children()])</ul>
		@endif
	</li>
@endforeach
