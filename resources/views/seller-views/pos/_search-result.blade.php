<link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/custom.css"/>
@if (count($products) > 0)
<ul class="list-group list-group-flush">
    @foreach($products as $i)
        <li class="list-group-item" >
            <a href="javascript:" onclick="$('.search-bar-input-mobile').val('{{$i['name']}}'); $('.search-bar-input').val('{{$i['name']}}'); quickView('{{$i->id}}')">
                {{$i['name']}}
            </a>
        </li>
    @endforeach
</ul>
@else

<div>
    <h5 class="m-0 text-muted">{{ translate('No_Product_Found') }}</h5>
</div>

@endif
