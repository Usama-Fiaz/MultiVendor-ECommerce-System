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
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl __inline-47"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row g-3">
            <!-- Sidebar-->
            @include('web-views.partials._profile-aside')
            {{-- Content --}}
            <section class="col-lg-9">
                @include('web-views.users-profile.account-details.partial',['order'=>$order])
                @if ($order->delivery_type == 'self_delivery')
                    <!--Self Delivery Man Info -->
                    <div class="bg-sm-white mt-3">
                        <div class="p-sm-3">
                            {{-- Deliveryman Profile --}}
                            <div class="delivery-man-info-box bg-white media gap-2 gap-sm-3 shadow-sm rounded p-3">
                                <img class="rounded-circle" width="77" src="{{ asset('storage/app/public/delivery-man/'.$order->delivery_man->image)}}"
                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" alt="">
                                <div class="media-body">
                                    <div class="d-flex gap-2 gap-sm-3 align-items-start align-items-sm-center justify-content-between">
                                        <div class="">
                                            <h6 class="text-capitalize mb-2">{{$order->delivery_man->f_name}}&nbsp{{$order->delivery_man->l_name}}</h6>
                                            <div class="rating-show justify-content-between fs-12">
                                                <span class="d-inline-block text-body">
                                                    @php($avg_rating = isset($order->delivery_man->rating[0]->average) ? $order->delivery_man->rating[0]->average : 0)
                                                    @for($inc=1;$inc<=5;$inc++)
                                                        @if ($inc <= (int)$avg_rating)
                                                            <i class="tio-star text-warning"></i>
                                                        @elseif ($avg_rating != 0 && $inc <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                            <i class="tio-star-half text-warning"></i>
                                                        @else
                                                            <i class="tio-star-outlined text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <label class="badge-style fs-12">( {{number_format($avg_rating,1)}} )</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end flex-wrap gap-3 gap-sm-3">
                                            <button type="button" class="btn btn-soft-info text-capitalize px-2 px-md-4" data-toggle="modal"
                                                data-target="#chatting_modal">
                                                <img src="{{asset('/public/assets/front-end/img/seller-info-chat.png')}}" alt="">
                                                <span class="d-none d-md-inline-block">{{translate('chat_with_delivery_man')}}</span>
                                            </button>
                                            @if($order->order_type == 'default_type' && $order->order_status=='delivered' && $order->delivery_man_id)
                                                <button type="button" class="btn btn-sm btn-warning px-2 px-md-4" data-toggle="modal"
                                                    data-target="#submitReviewModal">
                                                    <i class="tio-star-half"></i>
                                                    @if(isset($order->delivery_man_review->comment) || isset($order->delivery_man_review->rating))
                                                        {{translate('Update_Review')}}
                                                    @else
                                                        {{translate('review')}}
                                                    @endif
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Deliveryman Review -->
                            @isset($order->delivery_man_review)
                                <div class="shadow-sm bg-white rounded p-3 mt-3">
                                    <div class="d-flex align-items-center flex-wrap justify-content-between gap-2 mb-3">
                                        <h6 class="d-flex gap-2 mb-0 review-item-title"><span>{{number_format($order->delivery_man_review?->rating ,1)}}<i class="tio-star-half text-warning text-capitalize"></i></span> {{translate('delivery_man_review')}}</h6>
                                        <div class="fs-12 text-muted">{{date('M d , Y h:i A',strtotime($order->delivery_man_review->updated_at))}}</div>
                                    </div>
                                    <p class="fs-12 text-muted">{{$order->delivery_man_review->comment}}</p>
                                </div>
                            @endisset
                            <!-- Deliveryman Picture -->
                            @if ($order->verification_images->count()>0)
                                <div class="shadow-sm rounded bg-white p-3 mt-3">
                                    <h6 class="mb-0 fs-12 d-flex align-items-center gap-2 lh-1 mb-3">
                                        <i class="tio-photo-camera fs-16 text-primary text-capitalize"></i>
                                        {{translate('picture_upload_by')}} {{$order->delivery_man->f_name}}&nbsp{{$order->delivery_man->l_name}}
                                    </h6>
                                    @foreach ($order->verification_images as $image)
                                        @if(file_exists(base_path("storage/app/public/delivery-man/verification-image/".$image->image)))
                                            <img class="rounded" width="100" src="{{asset('public/assets/front-end/img/cod.png')}}" alt="">
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif ($order->delivery_type == 'third_party_delivery')
                    <!--Third Party Delivery Man Info -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="border rounded bg-white p-2">
                                <div class="row g-2">
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="media gap-3">
                                            <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                src="{{asset('public/assets/front-end/img/icons/van.png')}}"
                                                alt="VR Collection" width="20">
                                            <div class="media-body">
                                                <div class="text-muted text-capitalize">{{translate('delivery_service_name')}}</div>
                                                <div class="font-weight-bold">{{$order->delivery_service_name}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="media gap-3">
                                            <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                src="{{asset('public/assets/front-end/img/icons/track_order.png')}}"
                                                alt="VR Collection" width="20">
                                            <div class="media-body">
                                                <div class="text-muted">{{translate('tracking_ID')}} </div>
                                                <div class="font-weight-bold">{{$order->third_party_delivery_tracking_id ?? ''}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="login-card">
                        <div class="text-center pt-5 text-capitalize">

                            <img src="{{asset('public/assets/front-end/img/icons/delivery-man.svg')}}" alt="">
                            <p class="opacity-60 mt-3">
                                @if ($order->order_type == "POS")
                                    <span>{{translate('this_order_is_a_POS_order.delivery_man_is_not_assigned_to_POS_orders')}}</span>
                                @else
                                    @if ($order->product_type_check =='digital')
                                        {{translate('this_order_contains_one_or_more_digital_products.')}}
                                        {{translate('delivery_man_is_not_assigned_for_digital_products')}}!
                                    @else
                                        {{translate('no_delivery_man_assigned_yet')}}!
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

            </section>
        </div>
    </div>
    <!-- Submit a Review Modal -->
    <div class="modal fade" id="submitReviewModal" tabindex="-1" aria-labelledby="submitReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h6 class="text-center text-capitalize">{{translate('submit_a_review')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body d-flex flex-column gap-3">
                    <form action="{{route('submit-deliveryman-review')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input name="order_id" value="{{$order->id}}" hidden>
                        <div class="d-flex flex-column gap-2 align-items-center my-4">
                            <h5 class="text-center text-capitalize">{{translate('rate_the_delivery_quality')}}</h5>
                            <div class="rating-label-wrap position-relative">
                                <label class="rating-label">
                                    <input
                                        name="rating"
                                        class="rating"
                                        max="5"
                                        min="1"
                                        oninput="this.style.setProperty('--value', `${this.valueAsNumber}`)"
                                        step="1"
                                        style="--value:{{isset($order->delivery_man_review) ? $order->delivery_man_review->rating : '4'}}"
                                        type="range"
                                        value="5">
                                </label>
                                @php($style = '')
                                @if(isset($order->delivery_man_review))
                                    <?php
                                        $style = '';
                                        $rating = $order->delivery_man_review->rating;
                                        $style = match ($rating) {
                                            1 => 'left:5px',
                                            2 => 'left:36px',
                                            3 => 'left:85px',
                                            4 => 'left:112px',
                                            default => 'left:155px',
                                        };
                                    ?>
                                @endif
                                <span class="rating_content_delivery_man text-primary fs-12 text-nowrap" style="{{$style}}">
                                    @if(isset($order->delivery_man_review))
                                        <?php
                                            $rating = $order->delivery_man_review->rating;
                                            $rating_status = match ($rating) {
                                                1 => translate('poor'),
                                                2 => translate('average'),
                                                3 => translate('good'),
                                                4 => translate('very_good'),
                                                default => translate('excellent'),
                                            };
                                        ?>
                                        {{$rating_status}}
                                    @else
                                        {{ translate('excellent') }}!
                                    @endif
                                </span>
                            </div>
                        </div>

                        <h6 class="cursor-pointer mb-2">{{translate('have_thoughts_to_share')}}?</h6>
                        <div class="">
                            <textarea rows="4" name="comment" class="form-control text-area-class" placeholder="{{translate('best_delivery_service,_highly_recommended')}}">{{$order->delivery_man_review->comment ?? ''}}</textarea>
                        </div>

                        <div class="mt-3 d-flex justify-content-end">
                            @if(isset($order->delivery_man_review->comment) || isset($order->delivery_man_review->rating))
                                <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                            @else
                                <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @isset($order->delivery_man->id)
        <div class="modal fade" id="chatting_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-faded-info">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{translate('Send_Message_to_Deliveryman')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('messages_store')}}" method="post" id="chat-form">
                            @csrf
                            @if($order->delivery_man->id != 0)
                                <input value="{{$order->delivery_man->id}}" name="delivery_man_id" hidden>
                            @endif

                            <textarea name="message" class="form-control" required placeholder="{{ translate('Write_here') }}..."></textarea>
                            <br>
                            <div class="justify-content-end gap-2 d-flex flex-wrap">
                                <a href="{{route('chat', ['type' => 'delivery-man'])}}" class="btn btn-soft-primary bg--secondary border">
                                    {{translate('go_to_chatbox')}}
                                </a>

                                @if($order->delivery_man->id != 0)
                                    <button class="btn btn--primary text-white">{{translate('send')}}</button>
                                @else
                                    <button class="btn btn--primary text-white" disabled>{{translate('send')}}</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endisset
@endsection


@push('script')
    <script>
        function review_message() {
            toastr.info('{{translate('you_can_review_after_the_product_is_delivered!')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        function refund_message() {
            toastr.info('{{translate('you_can_refund_request_after_the_product_is_delivered!')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
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

                    toastr.success('{{translate('send_successfully')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#chat-form').trigger('reset');
                }
            });

        });
        $('.rating-label-wrap input[type=range]').on('change', function() {
            let ratingVal = $(this).val();
            let ratingContent = $('.rating_content_delivery_man');
            switch (ratingVal) {
                case "1":
                    // Execute code for rating value 1
                    ratingContent.text('{{translate('poor')}} !').css('left', '5px');
                    $('.text-area-class').attr("placeholder", "");
                    break;
                case "2":
                    // Execute code for rating value 2
                    ratingContent.text('{{translate('average')}} !').css('left', '36px');
                    $('.text-area-class').attr("placeholder", "");
                    break;
                case "3":
                    // Execute code for rating value 3
                    ratingContent.text('{{translate('good')}} !').css('left', '85px');
                    $('.text-area-class').attr("placeholder", '{{translate('the_delivery_service_is_good')}}');
                    break;
                case "4":
                    // Execute code for rating value 4
                    ratingContent.text('{{translate('very_Good')}} !').css('left', '112px');
                    $('.text-area-class').attr("placeholder", '{{translate('this_delivery_service_is_very_good_I_am_highly_impressed')}}');
                    break;
                case "5":
                    // Execute code for rating value 5
                    ratingContent.text('Excellent !').css('left', '155px');
                    $('.text-area-class').attr("placeholder", '{{translate('best_delivery_service,_highly_recommended')}}');
                    break;
                default:
                    break;
            }
        });
    </script>
@endpush

