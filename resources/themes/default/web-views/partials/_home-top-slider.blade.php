<style>
.category-link:hover,
.sub-category-link:hover,
.sub-sub-category-link:hover,
.view-all-link:hover {
    color: inherit;
    text-decoration: none;
    opacity: 1;
    visibility: visible;
}

.mega_menu {
    display: none;
    position: absolute;
    background-color: #fff;
    border: 1px solid #ddd;
    z-index: 1000;
}
</style>

<div class="row no-gutters position-relative rtl">
    @if ($categories->count() > 0 )
        <div class="col-xl-3 position-static d-none d-xl-block __top-slider-cate">
            <div class="category-menu-wrap position-static">
                <ul class="category-menu mt-0">
                    @foreach ($categories as $key=>$category)
                        <li>
                            <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}" class="category-link">{{$category->name}}</a>
                            @if ($category->childes->count() > 0)
                                <div class="mega_menu z-2">
                                    @foreach ($category->childes as $key=>$sub_category)
                                        <div class="mega_menu_inner">
                                            <h6><a href="{{route('products',['id'=> $sub_category['id'],'data_from'=>'category','page'=>1])}}" class="sub-category-link">{{$sub_category->name}}</a></h6>
                                            @if ($sub_category->childes->count() >0)
                                                @foreach ($sub_category->childes as $sub_sub_category)
                                                    <div><a href="{{route('products',['id'=> $sub_sub_category['id'],'data_from'=>'category','page'=>1])}}" class="sub-sub-category-link">{{$sub_sub_category->name}}</a></div>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </li>
                    @endforeach
                    <li class="text-center"><a href="{{route('categories')}}" class="text-primary font-weight-bold justify-content-center text-capitalize view-all-link">{{translate('view_all')}}</a></li>
                </ul>
            </div>
        </div>
    @endif

    <div class="col-12 col-xl-9 __top-slider-images">
        <div class="{{Session::get('direction') === "rtl" ? 'pr-xl-2' : 'pl-xl-2'}}">
            <div class="owl-theme owl-carousel hero-slider">
                @foreach($main_banner as $key=>$banner)
                <a href="{{$banner['url']}}" class="d-block">
                    <img class="w-100 __slide-img"
                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                        src="{{asset('storage/app/public/banner')}}/{{$banner['photo']}}"
                        alt="">
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
