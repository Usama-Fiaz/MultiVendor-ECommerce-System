@extends('layouts.front-end.app')

@section('title', translate('flash_Deal_Products'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Deals of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Deals of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <style>
        .countdown-background{
            background: {{$web_config['primary_color']}};
        }
        .cz-countdown-days {
            border: .5px solid{{$web_config['primary_color']}};
        }

        .cz-countdown-hours {
            border: .5px solid{{$web_config['primary_color']}};
        }

        .cz-countdown-minutes {
            border: .5px solid{{$web_config['primary_color']}};
        }
        .cz-countdown-seconds {
            border: .5px solid{{$web_config['primary_color']}};
        }
        .flash_deal_product_details .flash-product-price {
            color: {{$web_config['primary_color']}};
        }
    </style>
@endpush

@section('content')
@php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
<div class="__inline-59 pt-md-3">
    @if(file_exists('storage/app/public/deal/'.$deal['banner']))
        @php($deal_banner = asset('storage/app/public/deal/'.$deal['banner']))
    @else
        @php($deal_banner = asset('public/assets/front-end/img/flash-deals.png'))
    @endif
    <div class="container md-4 mt-3 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
         <div class="__flash-deals-bg" style="background: url({{$deal_banner}}) no-repeat center center / cover">
             <div class="row g-3 flex-center align-items-center">
                 @php($flash_deals=\App\Model\FlashDeal::with(['products.product.reviews'])->where(['status'=>1])->where(['deal_type'=>'flash_deal'])->whereDate('start_date','<=',date('Y-m-d'))->whereDate('end_date','>=',date('Y-m-d'))->first())
                 <div class="col-lg-4 col-md-6 text-center {{Session::get('direction') === "rtl" ? 'text-sm-right' : 'text-sm-left'}}">
                     <div class="flash_deal_title text-base">
                        {{$web_config['flash_deals']->title}}
                     </div>
                     <span class="text-base">{{translate('hurry_Up')}} ! {{translate('the_offer_is_limited')}}. {{translate('grab_while_it_lasts')}}</span>
                 </div>
                 <div class="col-lg-4 col-md-6">
                     <div class="countdown-card bg-transparent">
                         <div class="text-center text-white">
                             <div class="countdown-background">
                                 <span class="cz-countdown d-flex justify-content-center align-items-center"
                                     data-countdown="{{$web_config['flash_deals']?date('m/d/Y',strtotime($web_config['flash_deals']['end_date'])):''}} 23:59:00">
                                     <span class="cz-countdown-days">
                                         <span class="cz-countdown-value"></span>
                                         <span class="cz-countdown-text">{{ translate('days')}}</span>
                                     </span>
                                     <span class="cz-countdown-value p-1">:</span>
                                     <span class="cz-countdown-hours">
                                         <span class="cz-countdown-value"></span>
                                         <span class="cz-countdown-text">{{ translate('hrs')}}</span>
                                     </span>
                                     <span class="cz-countdown-value p-1">:</span>
                                     <span class="cz-countdown-minutes">
                                         <span class="cz-countdown-value"></span>
                                         <span class="cz-countdown-text">{{ translate('min')}}</span>
                                     </span>
                                     <span class="cz-countdown-value p-1">:</span>
                                     <span class="cz-countdown-seconds">
                                         <span class="cz-countdown-value"></span>
                                         <span class="cz-countdown-text">{{ translate('sec')}}</span>
                                     </span>
                                 </span>
                                 <div class="progress __progress">
                                 <div class="progress-bar flash-deal-progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
    </div>
    <!-- Toolbar-->

    <!-- Products grid-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <section class="col-lg-12">
                <div class="row g-3 mt-2">
                    @if($discountPrice)
                        @foreach($deal->products as $dp)
                            @if (isset($dp->product))
                                <div class="col--xl-2 col-sm-4 col-lg-3 col-6">
                                    @include('web-views.partials._single-product',['product'=>$dp->product,'decimal_point_settings'=>$decimal_point_settings])
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        /*--flash deal Progressbar --*/
        function update_flash_deal_progress_bar(){
            const current_time_stamp = new Date().getTime();
            const start_date = new Date('{{$web_config['flash_deals']['start_date'] ?? ''}}').getTime();
            const countdownElement = document.querySelector('.cz-countdown');
            const get_end_time = countdownElement.getAttribute('data-countdown');
            const end_time = new Date(get_end_time).getTime();
            let time_progress = ((current_time_stamp - start_date) / (end_time - start_date))*100;
            const progress_bar = document.querySelector('.flash-deal-progress-bar');
            progress_bar.style.width = time_progress + '%';
        }
        update_flash_deal_progress_bar();
        setInterval(update_flash_deal_progress_bar, 10000);
        /*-- end flash deal Progressbar --*/
    </script>
@endpush
