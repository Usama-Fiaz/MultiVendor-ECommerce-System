@extends('layouts.front-end.app')

@section('title',translate('order_Details'))

@push('css_or_js')
    <style>
        .page-item.active .page-link {
            background-color: {{$web_config['primary_color']}}              !important;
        }

        .amount {
            margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 60px;

        }

        .w-49{
            width: 49% !important
        }

        a {
            color: {{$web_config['primary_color']}};
        }

        @media (max-width: 360px) {
            .for-glaxy-mobile {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 6px;
            }

        }

        @media (max-width: 600px) {

            .for-glaxy-mobile {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 6px;
            }

            .order_table_info_div_2 {
                text-align: {{Session::get('direction') === "rtl" ? 'left' : 'right'}}          !important;
            }

             {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 16px;
            }

            . {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 16px;
            }

            .amount {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0px;
            }

        }

        .btn-square {
            border-radius: 5px !important;
            border: 1px solid #E9F3FF;
            width: 40px;
            height: 40px;
            min-width: 40px;
            display: grid;
            place-items: center;
            padding: 0.5rem;
            color: #0286ff;
            line-height: 1;
            font-size: 1rem;
        }

        .bg-soft-danger {
            background-color: #FFF4F3;
        }

        .calculation-table th,
        .calculation-table td {
            padding: 0.5rem;
        }

        @media (min-width: 1200px) {
            .gap-xl-30 {
                gap: 30px !important;
            }
        }

        .nav-menu {
            display: flex;
        }
        .nav-menu > * {
            border: none;
            border-bottom: 2px solid transparent;
            background-color: transparent;
            padding: .5rem 0;
            color: #9B9B9B;
        }
        .nav-menu > *.active {
            border-color: #1455AC;
            color: #1455AC;
            font-weight: 700;
        }
        .h-40px {
            height: 40px !important;
        }

        .top-1 {
            top: .5rem;
        }
        .left-1 {
            left: .5rem;
        }
    </style>
    <style>
        .rating {
            --dir: right;
            --fill: #1455AC;
            --fillbg: rgba(100, 100, 100, 0.15);
            --star: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 17.25l-6.188 3.75 1.641-7.031-5.438-4.734 7.172-0.609 2.813-6.609 2.813 6.609 7.172 0.609-5.438 4.734 1.641 7.031z"/></svg>');
            --stars: 5;
            --starsize: 2.5rem;
            --symbol: var(--star);
            --value: 1;
            --w: calc(var(--stars) * var(--starsize));
            --x: calc(100% * (var(--value) / var(--stars)));
            block-size: var(--starsize);
            inline-size: var(--w);
            position: relative;
            touch-action: manipulation;
            -webkit-appearance: none;
        }
        [dir="rtl"] .rating {
            --dir: left;
        }
        .rating::-moz-range-track {
            background: linear-gradient(to var(--dir), var(--fill) 0 var(--x), var(--fillbg) 0 var(--x));
            block-size: 100%;
            mask: repeat left center/var(--starsize) var(--symbol);
        }
        .rating::-webkit-slider-runnable-track {
            background: linear-gradient(to var(--dir), var(--fill) 0 var(--x), var(--fillbg) 0 var(--x));
            block-size: 100%;
            mask: repeat left center/var(--starsize) var(--symbol);
            -webkit-mask: repeat left center/var(--starsize) var(--symbol);
        }
        .rating::-moz-range-thumb {
            height: var(--starsize);
            opacity: 0;
            width: var(--starsize);
        }
        .rating::-webkit-slider-thumb {
            height: var(--starsize);
            opacity: 0;
            width: var(--starsize);
            -webkit-appearance: none;
        }
        .rating, .rating-label {
            display: block;
            font-family: ui-sans-serif, system-ui, sans-serif;
        }
        .rating-label {
            margin-block-end: 1rem;
        }

        /* NO JS */
        .rating--nojs::-moz-range-track {
            background: var(--fillbg);
        }
        .rating--nojs::-moz-range-progress {
            background: var(--fill);
            block-size: 100%;
            mask: repeat left center/var(--starsize) var(--star);
        }
        .rating--nojs::-webkit-slider-runnable-track {
            background: var(--fillbg);
        }
        .rating--nojs::-webkit-slider-thumb {
            background-color: var(--fill);
            box-shadow: calc(0rem - var(--w)) 0 0 var(--w) var(--fill);
            opacity: 1;
            width: 1px;
        }
        [dir="rtl"] .rating--nojs::-webkit-slider-thumb {
            box-shadow: var(--w) 0 0 var(--w) var(--fill);
        }
    </style>
@endpush

@section('content')

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl __inline-47 text-start">
        <div class="row g-3">
            <!-- Sidebar-->
            @include('web-views.partials._profile-aside')
            <!-- Content -->
            <section class="col-lg-9">
                @include('web-views.users-profile.account-details.partial')
                <!-- Seller Info -->
                <div class="card mt-3">
                    <div class="card-body">
                        @if($order->seller_is =='seller')
                            <div class="media flex-wrap gap-2 gap-sm-3 border rounded p-3">
                                <img class="rounded border seller-info-img" src="{{ asset('storage/app/public/shop/'.$order->seller->shop->image)}}"
                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" alt="">
                                <div class="media-body">
                                    <div class="d-flex gap-2 gap-sm-3 align-items-sm-center justify-content-between">
                                        <div class="">
                                            <h6 class="text-capitalize seller-info-title mb-1 mb-sm-2">{{$order->seller->shop->name}}</h6>
                                            <div class="rating-show justify-content-between">
                                                <span class="d-inline-block font-size-sm text-body">
                                                    @for($inc=1;$inc<=5;$inc++)
                                                        @if ($inc <= (int)$avg_rating)
                                                            <i class="tio-star text-warning"></i>
                                                        @elseif ($avg_rating != 0 && $inc <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                            <i class="tio-star-half text-warning"></i>
                                                        @else
                                                            <i class="tio-star-outlined text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <label class="badge-style">( {{number_format($avg_rating,1)}} )</label>
                                                </span>
                                            </div>
                                            <ul class="list-unstyled list-inline-dot fs-12 mb-0">
                                                <li class="mb-0">{{$rating_count}} {{('reviews')}} </li>
                                            </ul>
                                        </div>

                                        <div>
                                            <button type="button" class="btn btn-soft-info text-capitalize px-2 px-sm-4" data-toggle="modal"
                                                data-target="#chatting_modal" {{ ($order->seller->shop->temporary_close || ($order->seller->shop->vacation_status && date('Y-m-d') >= date('Y-m-d', strtotime($order->seller->shop->vacation_start_date)) && date('Y-m-d') <= date('Y-m-d', strtotime($order->seller->shop->vacation_end_date)))) ? 'disabled' : '' }}>
                                                <img src="{{asset('/public/assets/front-end/img/seller-info-chat.png')}}" alt="">
                                                <span class="d-none d-sm-inline-block">{{translate('chat_with_seller')}}</span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="media flex-wrap gap-3 border rounded p-3">
                                <img class="rounded border" width="77" src="{{asset("storage/app/public/company")}}/{{$web_config['fav_icon']->value}}"
                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" alt="">
                                <div class="media-body">
                                    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
                                        <div class="">
                                            <h6 class="text-capitalize">{{$web_config['name']->value}}</h6>
                                            <div class="rating-show justify-content-between">
                                                <span class="d-inline-block font-size-sm text-body">
                                                    @for($inc=1;$inc<=5;$inc++)
                                                        @if ($inc <= (int)$avg_rating)
                                                            <i class="tio-star text-warning"></i>
                                                        @elseif ($avg_rating != 0 && $inc <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                            <i class="tio-star-half text-warning"></i>
                                                        @else
                                                            <i class="tio-star-outlined text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <label class="badge-style">( {{number_format($avg_rating,1)}} )</label>
                                                </span>
                                            </div>
                                            <ul class="list-unstyled list-inline-dot fs-12 mb-0">
                                                <li class="mb-0">{{$rating_count}} {{('reviews')}} </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- Modal -->
    @include('layouts.front-end.partials.modal._chatting',['seller'=>$order->seller])
@endsection


@push('script')
<script>
      $('#chat-form').on('submit', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: "post",
                url: '{{route('messages_store')}}',
                data: $('#chat-form').serialize(),
                success: function (respons) {

                    toastr.success('{{translate("message_send_successfully")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#chat-form').trigger('reset');
                }
            });

        });
</script>
@endpush

