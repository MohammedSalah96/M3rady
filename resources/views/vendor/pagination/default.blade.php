@if ($paginator->hasPages())
<ul class="list-inline text-center">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
<li><a href="{{ $paginator->previousPageUrl() }}"><i class="{{$lang_code=='ar'?'ion-arrow-right-c':'ion-arrow-left-c'}}"></i></a></li>

    @else

    <li><a href="{{ $paginator->previousPageUrl() }}"></a></li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
    {{-- "Three Dots" Separator --}}
    @if (is_string($element))
<li><a href="#">{{$element}}</a></li>
    @endif

    {{-- Array Of Links --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <li><a class="active" href="#">{{$page}}</a></li>
    @else
    <li ><a href="{{ $url }}">{{ $page }}</a></li>
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())

    <li><a href="{{ $paginator->nextPageUrl() }}"><i class="{{$lang_code=='ar'?'ion-arrow-left-c':'ion-arrow-right-c'}}"></i></a></li>
    @else
    <li ><a  href="" rel="next">&raquo;</a></li>

    @endif
</ul>
@endif
