<div id="sidebarMain" class="d-none">
    <aside style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <!-- Logo -->
                    @php($shop=\App\Model\Shop::where(['seller_id'=>auth('seller')->id()])->first())
                    <a class="navbar-brand" href="{{route('seller.dashboard.index')}}" aria-label="Front">
                        @if (isset($shop))
                            <img onerror="this.src='{{asset('public/assets/back-end/img/900x400/img1.jpg')}}'"
                                class="navbar-brand-logo-mini for-seller-logo"
                                src="{{asset("storage/app/public/shop/$shop->image")}}" alt="Logo">
                        @else
                            <img class="navbar-brand-logo-mini for-seller-logo"
                                src="{{asset('public/assets/back-end/img/900x400/img1.jpg')}}" alt="Logo">
                        @endif
                    </a>
                    <!-- End Logo -->

                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="d-none js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->

                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip" data-placement="right" title="" data-original-title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align" data-template="<div class=&quot;tooltip d-none d-sm-block&quot; role=&quot;tooltip&quot;><div class=&quot;arrow&quot;></div><div class=&quot;tooltip-inner&quot;></div></div>" data-toggle="tooltip" data-placement="right" title="" data-original-title="Expand"></i>
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
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/dashboard')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.dashboard.index')}}">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('dashboard')}}
                                </span>
                            </a>
                        </li>
                        <!-- End Dashboards -->
                        @php($seller = auth('seller')->user())
                        <!-- POS -->
                        @php($sellerId = $seller->id)
                        @php($seller_pos=\App\Model\BusinessSetting::where('type','seller_pos')->first()->value)
                        @if ($seller_pos==1)
                            @if ($seller->pos_status == 1)
                                <li class="nav-item">
                                    <small
                                        class="nav-subtitle">{{translate('pos')}} {{translate('system')}} </small>
                                    <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                                </li>
                                <li class="navbar-vertical-aside-has-menu {{Request::is('seller/pos')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('seller.pos.index')}}">
                                        <i class="tio-shopping nav-icon"></i>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('POS')}}</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                        <!-- End POS -->

                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('order_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/orders*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <i class="tio-shopping-cart nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('orders')}}
                                </span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('seller/order*')?'block':'none'}}">

                                <li class="nav-item {{Request::is('seller/orders/list/all')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['all'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{translate('all')}}
                                            <span class="badge badge-soft-info badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/pending')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['pending'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{translate('pending')}}
                                            <span class="badge badge-soft-info badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'pending'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/confirmed')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['confirmed'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{translate('confirmed')}}
                                            <span
                                                class="badge badge-soft-info badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'confirmed'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/orders/list/processing')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['processing'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{translate('packaging')}}
                                            <span class="badge badge-soft-warning badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'processing'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/orders/list/out_for_delivery')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['out_for_delivery'])}}"
                                       title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{translate('out_For_Delivery')}}
                                            <span class="badge badge-soft-warning badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'out_for_delivery'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/orders/list/delivered')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['delivered'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{translate('delivered')}}
                                            <span class="badge badge-soft-success badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'delivered'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/returned')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['returned'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{translate('returned')}}
                                            <span class="badge badge-soft-danger badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'returned'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/failed')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['failed'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{translate('failed To Deliver')}}
                                            <span class="badge badge-soft-danger badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'failed'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/canceled')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['canceled'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{translate('canceled')}}
                                            <span class="badge badge-soft-danger badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                                {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'canceled'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/refund*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:">
                                <i class="tio-receipt-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('refund_Request_List')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('seller/refund*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('seller/refund/list/pending')?'active':''}}">
                                    <a class="nav-link"
                                       href="{{route('seller.refund.list',['pending'])}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                          {{translate('pending')}}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{\App\Model\RefundRequest::whereHas('order', function ($query) {
                                                    $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
                                                        })->where('status','pending')->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/refund/list/approved')?'active':''}}">
                                    <a class="nav-link"
                                       href="{{route('seller.refund.list',['approved'])}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                           {{translate('approved')}}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Model\RefundRequest::whereHas('order', function ($query) {
                                                    $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
                                                        })->where('status','approved')->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/refund/list/refunded')?'active':''}}">
                                    <a class="nav-link"
                                       href="{{route('seller.refund.list',['refunded'])}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                           {{translate('refunded')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                                {{\App\Model\RefundRequest::whereHas('order', function ($query) {
                                                    $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
                                                        })->where('status','refunded')->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/refund/list/rejected')?'active':''}}">
                                    <a class="nav-link"
                                       href="{{route('seller.refund.list',['rejected'])}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                           {{translate('rejected')}}
                                            <span class="badge badge-danger badge-pill ml-1">
                                                {{\App\Model\RefundRequest::whereHas('order', function ($query) {
                                                    $query->where('seller_is', 'seller')->where('seller_id',auth('seller')->id());
                                                        })->where('status','rejected')->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- End Pages -->

                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('product_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('seller/product*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <i class="tio-premium-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('products')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('seller/product*'))?'block':''}}">
                                <li class="nav-item {{Request::is('seller/product/list') || Request::is('seller/product/stock-limit-list/in_house')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.product.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('product_List')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/product/add-new')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.product.add-new')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('add_New_Product')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/product/bulk-import')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.product.bulk-import')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('bulk_import')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/reviews/list*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.reviews.list')}}">
                                <i class="tio-star nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('product_Reviews')}}
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('promotion_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>


                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/coupon*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{translate('offers_&_Deals')}}">
                                <i class="tio-users-switch nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('offers_&_Deals')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('seller/coupon*')?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{Request::is('seller/coupon*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('seller.coupon.add-new')}}"
                                       title="{{translate('coupon')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('coupon')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('Help_&_Support_Section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>


                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/messages*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:">
                                <i class="tio-user nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('messages')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('seller/messages*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('seller/messages/chat/customer')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.messages.chat', ['type' => 'customer'])}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('customer')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/messages/chat/delivery-man')?'active':''}}">
                                    <a class="nav-link" href="{{route('seller.messages.chat', ['type' => 'delivery-man'])}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('delivery-Man')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item {{(Request::is('seller/transaction/order-list')) ? 'scroll-here':''}}">
                            <small class="nav-subtitle" title="">
                                {{translate('Reports_&_Analysis')}}
                            </small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('seller/transaction/order-list') || Request::is('seller/transaction/expense-list')) ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{translate('Sales_&_Transaction_Report')}}">
                                <i class="tio-chart-bar-4 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('Sales_&_Transaction_Report')}}
                            </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('seller/transaction/order-list') || Request::is('seller/transaction/expense-list')) ?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{(Request::is('seller/transaction/order-list') || Request::is('seller/transaction/expense-list') || Request::is('seller/transaction/order-history-log*'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('seller.transaction.order-list')}}"
                                       title="{{translate('transaction_Report')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                     {{translate('transaction_Report')}}
                                    </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{ (Request::is('seller/report/all-product') ||Request::is('seller/report/stock-product-report')) ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.report.all-product')}}" title="{{translate('product_Report')}}">
                                <i class="tio-chart-bar-4 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                            <span class="position-relative">
                                {{translate('product_Report')}}
                            </span>
                        </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/report/order-report')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.report.order-report')}}"
                               title="{{translate('order_Report')}}">
                                <i class="tio-chart-bar-1 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                             {{translate('order_Report')}}
                            </span>
                            </a>
                        </li>


                        <!-- End Pages -->
                        <li class="nav-item {{( Request::is('seller/business-settings*'))?'scroll-here':''}}">
                            <small class="nav-subtitle" title="">{{translate('business_section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        @php($shippingMethod = \App\CPU\Helpers::get_business_settings('shipping_method'))
                        @if($shippingMethod=='sellerwise_shipping')
                            <li class="navbar-vertical-aside-has-menu {{Request::is('seller/business-settings/shipping-method*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('seller.business-settings.shipping-method.add')}}">
                                    <i class="tio-settings nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">
                                        {{translate('shipping_method')}}
                                    </span>
                                </a>
                            </li>
                        @endif

                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/business-settings/withdraw*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.business-settings.withdraw.list')}}">
                                <i class="tio-wallet-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">
                                        {{translate('withdraws')}}
                                    </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/profile/view') || Request::is('seller/profile/bank-edit/*') ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.profile.view')}}">
                                <i class="tio-shop nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('My_Bank_Info')}}
                                </span>
                            </a>
                        </li>


                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/shop*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.shop.view')}}">
                                <i class="tio-home nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('My_Shop')}}
                                </span>
                            </a>
                        </li>

                        @php( $shipping_method = \App\CPU\Helpers::get_business_settings('shipping_method'))
                        @if($shipping_method=='sellerwise_shipping')
                            <li class="nav-item {{Request::is('seller/delivery-man*')?'scroll-here':''}}">
                                <small class="nav-subtitle">{{translate('delivery_man_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('seller/delivery-man*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-user nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('Delivery-Man')}}
                                </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('seller/delivery-man*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('seller/delivery-man/add')?'active':''}}">
                                        <a class="nav-link " href="{{route('seller.delivery-man.add')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('add_New')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('seller/delivery-man/list') || Request::is('seller/delivery-man/earning-statement*') || Request::is('seller/delivery-man/earning-active-log*') || Request::is('seller/delivery-man/order-wise-earning*')?'active':''}}">
                                        <a class="nav-link" href="{{route('seller.delivery-man.list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('list')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('seller/delivery-man/withdraw-list') || Request::is('seller/delivery-man/withdraw-view*')?'active':''}}">
                                        <a class="nav-link " href="{{route('seller.delivery-man.withdraw-list')}}"
                                           title="{{translate('withdraws')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('withdraws')}}</span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('seller/delivery-man/emergency-contact/') ? 'active' : ''}}">
                                        <a class="nav-link " href="{{route('seller.delivery-man.emergency-contact.index')}}"
                                           title="{{translate('withdraws')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('emergency_Contact')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
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
        var $rows = $('.navbar-vertical-content li');
        $('#search-bar-input').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
@endpush
