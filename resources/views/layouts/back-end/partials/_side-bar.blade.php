<div id="sidebarMain" class="d-none">
    <aside
        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="bg-white js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <!-- Logo -->
                    @php($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
                    <a class="navbar-brand" href="{{route('admin.dashboard.index')}}" aria-label="Front">
                        <img onerror="this.src='{{asset('public/assets/back-end/img/900x400/img1.jpg')}}'"
                             class="navbar-brand-logo-mini for-web-logo max-h-30"
                             src="{{asset("storage/app/public/company/$e_commerce_logo")}}" alt="Logo">
                    </a>
                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                            class="d-none js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->

                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                           data-placement="right" title="" data-original-title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                           data-template="<div class=&quot;tooltip d-none d-sm-block&quot; role=&quot;tooltip&quot;><div class=&quot;arrow&quot;></div><div class=&quot;tooltip-inner&quot;></div></div>"
                           data-toggle="tooltip" data-placement="right" title="" data-original-title="Expand"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content">
                    <!-- Search Form -->
                    <div class="sidebar--search-form pb-3 pt-4">
                        <div class="search--form-group">
                            <button type="button" class="btn"><i class="tio-search"></i></button>
                            <input type="text" class="js-form-search form-control form--control" id="search-bar-input"
                                   placeholder="{{translate('search_menu')}}...">
                        </div>
                    </div>
                    <!-- End Search Form -->
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/dashboard')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               title="{{translate('dashboard')}}"
                               href="{{route('admin.dashboard.index')}}">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('dashboard')}}
                                </span>
                            </a>
                        </li>
                        <!-- End Dashboards -->

                        <!-- POS -->
                        <!-- @if (\App\CPU\Helpers::module_permission_check('pos_management'))
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/pos*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   title="{{translate('POS')}}" href="{{route('admin.pos.index')}}">
                                    <i class="tio-shopping nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('POS')}}</span>
                                </a>
                            </li>
                        @endif -->
                        <!-- End POS -->

                        <!-- Order Management -->
                        @if(\App\CPU\Helpers::module_permission_check('order_management'))
                            <li class="nav-item {{Request::is('admin/orders*')?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{translate('order_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <!-- Order -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/orders*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:void(0)" title="{{translate('orders')}}">
                                    <i class="tio-shopping-cart-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('orders')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/order*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/orders/list/all')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.orders.list',['all'])}}"
                                           title="{{translate('all')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('all')}}
                                                <span class="badge badge-soft-info badge-pill ml-1">
                                                    {{\App\Model\Order::count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/pending')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['pending'])}}"
                                           title="{{translate('pending')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{translate('pending')}}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'pending'])->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/confirmed')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['confirmed'])}}"
                                           title="{{translate('confirmed')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('confirmed')}}
                                                <span class="badge badge-soft-success badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'confirmed'])->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/processing')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['processing'])}}"
                                           title="{{translate('packaging')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{translate('packaging')}}
                                                <span class="badge badge-soft-warning badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'processing'])->count()}}
                                                </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/out_for_delivery')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['out_for_delivery'])}}"
                                           title="{{translate('out_for_delivery')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{translate('out_for_delivery')}}
                                                <span class="badge badge-soft-warning badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'out_for_delivery'])->count()}}
                                                </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/delivered')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['delivered'])}}"
                                           title="{{translate('delivered')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{translate('delivered')}}
                                                <span class="badge badge-soft-success badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'delivered'])->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/returned')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['returned'])}}"
                                           title="{{translate('returned')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('returned')}}
                                                    <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\Order::where('order_status','returned')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/failed')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['failed'])}}"
                                           title="{{translate('failed')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('failed_to_Deliver')}}
                                                <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'failed'])->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('admin/orders/list/canceled')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['canceled'])}}"
                                           title="{{translate('canceled')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('canceled')}}
                                                    <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'canceled'])->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/refund-section/refund/*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{translate('refund_Requests')}}">
                                    <i class="tio-receipt-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('refund_Requests')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/refund-section/refund*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/refund-section/refund/list/pending')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.refund-section.refund.list',['pending'])}}"
                                           title="{{translate('pending')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                              {{translate('pending')}}
                                                <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::where('status','pending')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('admin/refund-section/refund/list/approved')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.refund-section.refund.list',['approved'])}}"
                                           title="{{translate('approved')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                               {{translate('approved')}}
                                                <span class="badge badge-soft-info badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::where('status','approved')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/refund-section/refund/list/refunded')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.refund-section.refund.list',['refunded'])}}"
                                           title="{{translate('refunded')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                               {{translate('refunded')}}
                                                <span class="badge badge-soft-success badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::where('status','refunded')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/refund-section/refund/list/rejected')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.refund-section.refund.list',['rejected'])}}"
                                           title="{{translate('rejected')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                               {{translate('rejected')}}
                                                <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::where('status','rejected')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <!--Order Management Ends-->

                        <!--Product Management -->
                        @if(\App\CPU\Helpers::module_permission_check('product_management'))
                            <li class="nav-item {{(Request::is('admin/brand*') || Request::is('admin/category*') || Request::is('admin/sub*') || Request::is('admin/attribute*') || Request::is('admin/product*'))?'scroll-here':''}}">
                                <small class="nav-subtitle"
                                       title="">{{translate('product_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/category*') ||Request::is('admin/sub*')) ?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{translate('category_Setup')}}">
                                    <i class="tio-filter-list nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('category_Setup')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{(Request::is('admin/category*') ||Request::is('admin/sub*'))?'block':''}}">
                                    <li class="nav-item {{Request::is('admin/category/view')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.category.view')}}"
                                           title="{{translate('categories')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('categories')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/sub-category/view')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.sub-category.view')}}"
                                           title="{{translate('sub_Categories')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('sub_Categories')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/sub-sub-category/view')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.sub-sub-category.view')}}"
                                           title="{{translate('sub_Sub_Categories')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('sub_Sub_Categories')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/brand*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{translate('brands')}}">
                                    <i class="tio-star nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('brands')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/brand*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/brand/add-new')?'active':''}}"
                                        title="{{translate('add_new')}}">
                                        <a class="nav-link " href="{{route('admin.brand.add-new')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('add_new')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/brand/list')?'active':''}}"
                                        title="{{translate('list')}}">
                                        <a class="nav-link " href="{{route('admin.brand.list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('list')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/attribute*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.attribute.view')}}"
                                   title="{{translate('product_Attributes')}}">
                                    <i class="tio-category-outlined nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('product_Attributes')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/product/list/in_house') || Request::is('admin/product/bulk-import') || (Request::is('admin/product/add-new')) || (Request::is('admin/product/view/*')) || (Request::is('admin/product/barcode/*')))?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{translate('in-Hous_Products')}}">
                                    <i class="tio-shop nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        <span class="text-truncate">{{translate('in-house_Products')}}</span>
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{(Request::is('admin/product/list/in_house') || (Request::is('admin/product/stock-limit-list/in_house')) || (Request::is('admin/product/bulk-import')) || (Request::is('admin/product/add-new')) || (Request::is('admin/product/view/*')) || (Request::is('admin/product/barcode/*')))?'block':''}}">
                                    <li class="nav-item {{(Request::is('admin/product/list/in_house') || (Request::is('admin/product/view/*')) || (Request::is('admin/product/barcode/*')))?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.list',['in_house', ''])}}"
                                           title="{{translate('Product_List')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('Product_List')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/product/add-new') ? 'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.add-new')}}"
                                           title="{{translate('add_New_Product')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('add_New_Product')}}</span>
                                        </a>
                                    </li>
                                    <!-- <li class="nav-item {{Request::is('admin/product/bulk-import')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.bulk-import')}}"
                                           title="{{translate('bulk_import')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('bulk_import')}}</span>
                                        </a>
                                    </li> -->
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/product/list/seller*')||Request::is('admin/product/updated-product-list')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:"
                                   title="{{translate('seller_Products')}}">
                                    <i class="tio-airdrop nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('seller_Products')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/product/list/seller*')||Request::is('admin/product/updated-product-list')?'block':''}}">
                                    <li class="nav-item {{str_contains(url()->current().'?status='.request()->get('status'),'/admin/product/list/seller?status=0')==1?'active':''}}">
                                        <a class="nav-link"
                                           title="{{translate('new_Products_Requests')}}"
                                           href="{{route('admin.product.list',['seller', 'status'=>'0'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('new_Products_Requests')}} </span>
                                        </a>
                                    </li>
                                    @if (\App\CPU\Helpers::get_business_settings('product_wise_shipping_cost_approval')==1)
                                        <li class="nav-item {{Request::is('admin/product/updated-product-list')?'active':''}}">
                                            <a class="nav-link" title="{{translate('product_Updated_Requests')}}"
                                               href="{{route('admin.product.updated-product-list')}}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span
                                                    class="text-truncate">{{translate('product_Updated_Requests')}} </span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item {{str_contains(url()->current().'?status='.request()->get('status'),'/admin/product/list/seller?status=1')==1?'active':''}}">
                                        <a class="nav-link"
                                           title="{{translate('approved_Products')}}"
                                           href="{{route('admin.product.list',['seller', 'status'=>'1'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('approved_Products')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{str_contains(url()->current().'?status='.request()->get('status'),'/admin/product/list/seller?status=2')==1?'active':''}}">
                                        <a class="nav-link"
                                           title="{{translate('denied_Products')}}"
                                           href="{{route('admin.product.list',['seller', 'status'=>'2'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('denied_Products')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <!--Product Management Ends-->

                        @if(\App\CPU\Helpers::module_permission_check('promotion_management'))
                        <!--promotion management start-->
                        <li class="nav-item {{(Request::is('admin/banner*') || (Request::is('admin/coupon*')) || (Request::is('admin/notification*')) || (Request::is('admin/deal*')))?'scroll-here':''}}">
                            <small class="nav-subtitle" title="">{{translate('promotion_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/banner*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.banner.list')}}" title="{{translate('banners')}}">
                                <i class="tio-photo-square-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('banners')}}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/coupon*') || Request::is('admin/deal*')) ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{translate('offers_&_Deals')}}">
                                <i class="tio-users-switch nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('offers_&_Deals')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('admin/coupon*') || Request::is('admin/deal*'))?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/coupon*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.coupon.add-new')}}"
                                       title="{{translate('coupon')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('coupon')}}</span>
                                    </a>
                                </li>
                                <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/deal/flash') || (Request::is('admin/deal/update*')))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.deal.flash')}}"
                                       title="{{translate('flash_Deals')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('flash_Deals')}}</span>
                                    </a>
                                </li>
                                <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/deal/day') || (Request::is('admin/deal/day-update*')))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.deal.day')}}"
                                       title="{{translate('deal_of_the_day')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{translate('deal_of_the_day')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/deal/feature') || Request::is('admin/deal/edit*'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.deal.feature')}}"
                                       title="{{translate('featured_Deal')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{translate('featured_Deal')}}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- <li class="navbar-vertical-aside-has-menu {{Request::is('admin/notification*') ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{translate('notifications')}}">
                                <i class="tio-users-switch nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('notifications')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('admin/notification*') || Request::is('admin/business-settings/fcm-index')) ? 'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{!Request::is('admin/notification/push') && Request::is('admin/notification*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.notification.add-new')}}"
                                       title="{{translate('send_notification')}}">
                                        <img src="{{ asset('public/assets/back-end/img/icons/send-notification.svg') }}" alt="Send Notification.svg" width="15" class="mr-2">
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{translate('Send_Notification')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/fcm-index') || Request::is('admin/notification/push'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.notification.push')}}"
                                    title="{{translate('Push_Notification')}}">
                                        <img src="{{ asset('public/assets/back-end/img/icons/push-notification.svg') }}" alt="Push Notification.svg" width="15" class="mr-2">
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{translate('Push_Notification')}}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/announcement')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.business-settings.announcement')}}"
                               title="{{translate('announcements')}}">
                                <i class="tio-mic-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('announcements')}}
                                </span>
                            </a>
                        </li>
                        <!--promotion management end-->
                        @endif

                        @if(\App\CPU\Helpers::module_permission_check('system_settings'))
                            @if (count(config('get_theme_routes')) > 0)
                                <!-- Theme Menu Start-->
                                <li class="nav-item {{(Request::is('admin/banner*') || (Request::is('admin/coupon*')) || (Request::is('admin/notification*')) || (Request::is('admin/deal*')))?'scroll-here':''}}">
                                    <small class="nav-subtitle" title="">{{ config('get_theme_routes')['name'] }} {{translate('Menu')}}</small>
                                    <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                                </li>

                                @foreach (config('get_theme_routes')['route_list'] as $route)
                                    <li class="navbar-vertical-aside-has-menu {{ (Request::is($route['path']) || Request::is($route['path'].'*')) ? 'active':''}} @foreach ($route['route_list'] as $sub_route){{ (Request::is($sub_route['path']) || Request::is($sub_route['path'].'*')) ? 'active':''}}@endforeach">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link {{ count($route['route_list']) > 0 ? 'nav-link-toggle':'' }}"
                                           href="{{ count($route['route_list']) > 0 ? 'javascript:':$route['url'] }}" title="{{translate('offers_&_Deals')}}">
                                            {!! $route['icon'] !!}
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate($route['name'])}}</span>
                                        </a>

                                        @if (count($route['route_list']) > 0)
                                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                                style="display: @foreach ($route['route_list'] as $sub_route){{ (Request::is($sub_route['path']) || Request::is($sub_route['path'].'*')) ? 'block':'none'}}@endforeach">
                                                @foreach ($route['route_list'] as $sub_route)
                                                    <li class="navbar-vertical-aside-has-menu {{ (Request::is($sub_route['path']) || Request::is($sub_route['path'].'*')) ? 'active':''}}">
                                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                                           href="{{$sub_route['url']}}"
                                                           title="{{ translate($sub_route['name']) }}">
                                                            <span class="tio-circle nav-indicator-icon"></span>
                                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate($sub_route['name']) }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach

                                <!-- Theme Menu End-->
                            @endif
                        @endif

                        <!-- end refund section -->
                        <!-- @if(\App\CPU\Helpers::module_permission_check('support_section'))
                        <li class="nav-item {{(Request::is('admin/support-ticket*') || Request::is('admin/contact*'))?'scroll-here':''}}">
                            <small class="nav-subtitle"
                                   title="">{{translate('help_&_support')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/contact*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.contact.list')}}" title="{{translate('messages')}}">
                                <i class="tio-messages nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                <span class="position-relative">
                                    {{translate('messages')}}
                                    @php($message=\App\Model\Contact::where('seen',0)->count())
                                    @if($message!=0)
                                        <span
                                            class="btn-status btn-xs-status btn-status-danger position-absolute top-0 menu-status"></span>
                                    @endif
                                </span>
                            </span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/support-ticket*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.support-ticket.view')}}"
                               title="{{translate('support_Ticket')}}">
                                <i class="tio-chat nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                <span class="position-relative">
                                    {{translate('support_Ticket')}}
                                    @if(\App\Model\SupportTicket::where('status','open')->count()>0)
                                        <span
                                            class="btn-status btn-xs-status btn-status-danger position-absolute top-0 menu-status"></span>
                                    @endif
                                </span>
                            </span>
                            </a>
                        </li>
                        @endif -->
                        <!--support section ends here-->

                        <!--Reports & Analytics section-->
                        @if(\App\CPU\Helpers::module_permission_check('report'))
                        <li class="nav-item {{(Request::is('admin/report/earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/seller-report') || Request::is('admin/report/earning') || Request::is('admin/transaction/list') || Request::is('admin/refund-section/refund-list') || Request::is('admin/stock/product-in-wishlist') || Request::is('admin/reviews*') || Request::is('admin/stock/product-stock') || Request::is('admin/transaction/wallet-bonus') || Request::is('admin/report/order')) ? 'scroll-here':''}}">
                            <small class="nav-subtitle" title="">
                                {{translate('reports_&_Analysis')}}
                            </small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/seller-report') || Request::is('admin/report/earning') || Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list') || Request::is('admin/transaction/wallet-bonus')) ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{translate('sales_&_Transaction_Report')}}">
                                <i class="tio-chart-bar-4 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('sales_&_Transaction_Report')}}
                            </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/seller-report') || Request::is('admin/report/earning') || Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list') || Request::is('admin/transaction/wallet-bonus')) ?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.report.admin-earning')}}"
                                       title="{{translate('Earning_Reports')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                       {{translate('Earning_Reports')}}
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/report/inhoue-product-sale')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.report.inhoue-product-sale')}}"
                                       title="{{translate('inhouse_Sales')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{translate('inhouse_Sales')}}
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/report/seller-report')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.report.seller-report')}}"
                                       title="{{translate('seller_Sales')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize">
                                        {{translate('seller_Sales')}}
                                    </span>
                                    </a>
                                </li>
                                <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list') || Request::is('admin/transaction/wallet-bonus'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.transaction.order-transaction-list')}}"
                                       title="{{translate('transaction_Report')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                     {{translate('transaction_Report')}}
                                    </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{ (Request::is('admin/report/all-product') ||Request::is('admin/stock/product-in-wishlist') || Request::is('admin/stock/product-stock')) ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.report.all-product')}}" title="{{translate('product_Report')}}">
                                <i class="tio-chart-bar-4 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                            <span class="position-relative">
                                {{translate('product_Report')}}
                            </span>
                        </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/order')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.report.order')}}"
                               title="{{translate('order_Report')}}">
                                <i class="tio-chart-bar-1 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                             {{translate('Order_Report')}}
                            </span>
                            </a>
                        </li>
                        @endif
                        <!--Reports & Analytics section End-->

                        <!--User management-->
                        @if(\App\CPU\Helpers::module_permission_check('user_section'))
                        <li class="nav-item {{(Request::is('admin/customer/list') ||Request::is('admin/sellers/subscriber-list')||Request::is('admin/sellers/seller-add') || Request::is('admin/sellers/seller-list') || Request::is('admin/delivery-man*'))?'scroll-here':''}}">
                            <small class="nav-subtitle" title="">{{translate('user_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/customer/wallet*') || Request::is('admin/customer/list') || Request::is('admin/customer/view*') || Request::is('admin/reviews*') || Request::is('admin/customer/loyalty/report'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{translate('customers')}}">
                                <i class="tio-wallet nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('customers')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('admin/customer/wallet*') || Request::is('admin/customer/list') || Request::is('admin/customer/view*') || Request::is('admin/reviews*') || Request::is('admin/customer/loyalty/report'))?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/customer/list') || Request::is('admin/customer/view*')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.customer.list')}}"
                                       title="{{translate('Customer_List')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('customer_List')}} </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/reviews*')?'active':''}}">
                                    <a class="nav-link"
                                       href="{{route('admin.reviews.list')}}"
                                       title="{{translate('customer_Reviews')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('customer_Reviews')}}
                                </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/customer/wallet/report')?'active':''}}">
                                    <a class="nav-link" title="{{translate('wallet')}}"
                                       href="{{route('admin.customer.wallet.report')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{translate('wallet')}}
                                    </span>
                                    </a>
                                </li>
                                <!-- <li class="nav-item {{Request::is('admin/customer/wallet/bonus-setup')?'active':''}}">
                                    <a class="nav-link" title="{{translate('wallet_Bonus_Setup')}}"
                                       href="{{route('admin.customer.wallet.bonus-setup')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{translate('wallet_Bonus_Setup')}}
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/customer/loyalty/report')?'active':''}}">
                                    <a class="nav-link" title="{{translate('loyalty_Points')}}"
                                       href="{{route('admin.customer.loyalty.report')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{translate('loyalty_Points')}}
                                    </span>
                                    </a>
                                </li> -->
                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/seller*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{translate('sellers')}}">
                                <i class="tio-users-switch nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('sellers')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/seller*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/sellers/seller-add')?'active':''}}">
                                    <a class="nav-link" title="{{translate('add_New_Seller')}}"
                                       href="{{route('admin.sellers.seller-add')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{translate('add_New_Seller')}}
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/sellers/seller-list') ||Request::is('admin/sellers/view*') ?'active':''}}">
                                    <a class="nav-link" title="{{translate('seller_List')}}"
                                       href="{{route('admin.sellers.seller-list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{translate('seller_List')}}
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/sellers/withdraw_list')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.sellers.withdraw_list')}}"
                                       title="{{translate('withdraws')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('withdraws')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{(Request::is('admin/sellers/withdraw-method/list') || Request::is('admin/sellers/withdraw-method/*'))?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.sellers.withdraw-method.list')}}"
                                       title="{{translate('withdrawal_Methods')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('withdrawal_Methods')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/delivery-man*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{translate('delivery-man')}}">
                                <i class="tio-user nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('delivery-man')}}
                            </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/delivery-man*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/delivery-man/add')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.delivery-man.add')}}"
                                       title="{{translate('add_new')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('add_new')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/delivery-man/list')|| Request::is('admin/delivery-man/edit*')  || Request::is('admin/delivery-man/earning-statement*') || Request::is('admin/delivery-man/order-history-log*') || Request::is('admin/delivery-man/order-wise-earning*')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.delivery-man.list')}}"
                                       title="{{translate('list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('list')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/delivery-man/chat')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.delivery-man.chat')}}"
                                       title="{{translate('chat')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('chat')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/delivery-man/withdraw-list') || Request::is('admin/delivery-man/withdraw-view*')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.delivery-man.withdraw-list')}}"
                                       title="{{translate('withdraws')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('withdraws')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/delivery-man/emergency-contact')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.delivery-man.emergency-contact.index')}}"
                                       title="{{translate('emergency_contact')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('Emergency_Contact')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @if(auth('admin')->user()->admin_role_id==1)
                        <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/employee*') || Request::is('admin/custom-role*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{translate('employees')}}">
                                <i class="tio-user nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('employees')}}
                            </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/employee*') || Request::is('admin/custom-role*')?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/custom-role*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.custom-role.create')}}"
                                       title="{{translate('employee_Role_Setup')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('employee_Role_Setup')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{(Request::is('admin/employee/list') || Request::is('admin/employee/add-new') || Request::is('admin/employee/update*'))?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.employee.list')}}"
                                       title="{{translate('employees')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('employees')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/subscriber-list')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.customer.subscriber-list')}}"
                                   title="{{translate('subscribers')}}">
                                    <span class="tio-user nav-icon"></span>
                                    <span class="text-truncate">{{translate('subscribers')}} </span>
                                </a>
                            </li>
                        @endif
                        <!--User management end-->

                        <!--System Settings-->
                        @if(\App\CPU\Helpers::module_permission_check('system_settings'))
                        <li class="nav-item {{(Request::is('admin/business-settings/social-media') || Request::is('admin/business-settings/web-config/app-settings') || Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/fcm-index') || Request::is('admin/business-settings/mail')|| Request::is('admin/business-settings/web-config/login-url-setup') || Request::is('admin/business-settings/web-config/db-index')||Request::is('admin/business-settings/web-config/environment-setup') || Request::is('admin/business-settings/web-config') || Request::is('admin/business-settings/cookie-settings') || Request::is('admin/business-settings/otp-setup') || Request::is('admin/system-settings/software-update') || Request::is('admin/business-settings/web-config/theme/setup') || Request::is('admin/business-settings/delivery-restriction') || Request::is('admin/addon')) ? 'scroll-here' : '' }}">
                            <small class="nav-subtitle"
                                   title="">{{translate('system_Settings')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/web-config') || Request::is('admin/product-settings/inhouse-shop') || Request::is('admin/business-settings/seller-settings') || Request::is('admin/customer/customer-settings') || Request::is('admin/business-settings/delivery-man-settings') || Request::is('admin/refund-section/refund-index') || Request::is('admin/business-settings/shipping-method/setting') || Request::is('admin/business-settings/order-settings/index') || Request::is('admin/product-settings') || Request::is('admin/business-settings/web-config/delivery-restriction') || Request::is('admin/business-settings/delivery-restriction'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.business-settings.web-config.index')}}"
                               title="{{translate('business_Setup')}}">
                                <i class="tio-globe nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('business_Setup')}}
                            </span>
                            </a>
                        </li>

{{--                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/email-templates/*') ? 'active':''}}">--}}
{{--                            <a class="nav-link " href="{{route('admin.business-settings.email-templates.index')}}"--}}
{{--                               title="{{translate('email_templates')}}">--}}
{{--                                <span class="tio-email nav-icon"></span>--}}
{{--                                <span class="text-truncate">{{translate('email_templates')}}</span>--}}
{{--                            </a>--}}
{{--                        </li>--}}

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/mail') || Request::is('admin/business-settings/sms-module') || Request::is('admin/business-settings/captcha') || Request::is('admin/social-login/view') || Request::is('admin/social-media-chat/view') || Request::is('admin/business-settings/map-api') || Request::is('admin/business-settings/payment-method') || Request::is('admin/business-settings/payment-method/offline-payment*'))?'active':''}}">
                            <a class="nav-link " href="{{route('admin.business-settings.payment-method.index')}}"
                               title="{{translate('3rd_party')}}">
                                <span class="tio-key nav-icon"></span>
                                <span class="text-truncate">{{translate('3rd_party')}}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/social-media') || Request::is('admin/file-manager*') || Request::is('admin/business-settings/features-section') ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{translate('Pages_&_Media')}}">
                                <i class="tio-pages-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('Pages_&_Media')}}
                            </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/social-media') || Request::is('admin/file-manager*') || Request::is('admin/business-settings/features-section')?'block':'none'}}">
                                <li class="nav-item {{(Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list')|| Request::is('admin/business-settings/features-section'))?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.business-settings.terms-condition')}}"
                                       title="{{translate('pages')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                      {{translate('pages')}}
                                    </span>
                                    </a>
                                </li>
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/social-media')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.business-settings.social-media')}}"
                                       title="{{translate('social_Media_Links')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('social_Media_Links')}}
                                </span>
                                    </a>
                                </li>

                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/file-manager*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.file-manager.index')}}"
                                       title="{{translate('gallery')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('gallery')}}
                                    </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/web-config/environment-setup') || Request::is('admin/business-settings/web-config/mysitemap') || Request::is('admin/business-settings/analytics-index') || Request::is('admin/currency/view') || Request::is('admin/business-settings/web-config/db-index') || Request::is('admin/business-settings/language*') || Request::is('admin/business-settings/web-config/theme/setup')  || Request::is('admin/system-settings/software-update') || Request::is('admin/business-settings/cookie-settings') || Request::is('admin/business-settings/otp-setup') || Request::is('admin/business-settings/web-config/app-settings') || Request::is('admin/addon'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               title="{{translate('system_Setup')}}"
                               href="{{route('admin.business-settings.web-config.environment-setup')}}">
                                <i class="tio-labels nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('system_Setup')}}
                            </span>
                            </a>
                        </li>

                        @if(count(config('addon_admin_routes'))>0)
                            <li class="navbar-vertical-aside-has-menu
                                @foreach(config('addon_admin_routes') as $routes)
                                    @foreach($routes as $route)
                                        {{strstr(Request::url(), $route['path'])?'active':''}}
                                    @endforeach
                                @endforeach
                            ">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{translate('Pages_&_Media')}}">
                                    <i class="tio-puzzle nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('addon_Menus')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display:
                                    @foreach(config('addon_admin_routes') as $routes)
                                        @foreach($routes as $route)
                                            {{ strstr(Request::url(), $route['path'])?'block':'' }}
                                        @endforeach
                                    @endforeach
                                    ">
                                    @foreach(config('addon_admin_routes') as $routes)
                                        @foreach($routes as $route)
                                            <li class="navbar-vertical-aside-has-menu {{strstr(Request::url(), $route['path'])?'active':''}}">

                                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                                   href="{{ $route['url'] }}" title="{{ translate($route['name']) }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                        {{ translate($route['name']) }}
                                                    </span>
                                                </a>

                                            </li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </li>
                        @endif

{{--                            @if(count(config('addon_admin_routes'))>0)--}}
{{--                                <li class="nav-item--}}
{{--                                @foreach(config('addon_admin_routes') as $routes)--}}
{{--                                    @foreach($routes as $route)--}}
{{--                                        {{ strstr(Request::url(), $route['path']) ? 'scroll-here':''}}--}}
{{--                                    @endforeach--}}
{{--                                @endforeach--}}
{{--                                ">--}}
{{--                                    <small class="nav-subtitle"--}}
{{--                                           title="">{{translate('addon_menus')}}</small>--}}
{{--                                    <small class="tio-more-horizontal nav-subtitle-replacer"></small>--}}
{{--                                </li>--}}

{{--                                @foreach(config('addon_admin_routes') as $routes)--}}
{{--                                    @foreach($routes as $route)--}}
{{--                                        <li class="navbar-vertical-aside-has-menu {{strstr(Request::url(), $route['path'])?'active':''}}">--}}

{{--                                            <a class="js-navbar-vertical-aside-menu-link nav-link"--}}
{{--                                               href="{{ $route['url'] }}" title="{{ translate($route['name']) }}">--}}
{{--                                                <i class="tio-labels nav-icon"></i>--}}
{{--                                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">--}}
{{--                                                    {{ translate($route['name']) }}--}}
{{--                                                </span>--}}
{{--                                            </a>--}}

{{--                                        </li>--}}
{{--                                    @endforeach--}}
{{--                                @endforeach--}}

{{--                            @endif--}}
                        @endif
                        <!--System Settings end-->

                        <li class="nav-item pt-5">
                        </li>
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>

@push('script_2')
    <script>
        $(window).on('load' , function() {
            if($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });

        //Sidebar Menu Search
        var $rows = $('.navbar-vertical-content .navbar-nav > li');
        $('#search-bar-input').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });

    </script>
@endpush

