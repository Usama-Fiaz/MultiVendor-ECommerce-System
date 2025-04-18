@extends('theme-views.layouts.app')

@section('title', translate('Support_Ticket').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">
                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card ov-hidden border-0">
                        <div class="bg-light rounded-10 border-grey d-flex gap-3 flex-wrap align-items-start justify-content-between p-3 m-3">
                            <div class="media flex-wrap gap-3">
                                <div class="avatar avatar-lg rounded-circle">
                                    <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                         src="{{asset('storage/app/public/profile')}}/{{\App\CPU\customer_info()->image}}" loading="lazy" class="img-fit rounded-circle dark-support" alt="">
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <h6 class="">{{ \App\CPU\customer_info()->f_name }}&nbsp{{ \App\CPU\customer_info()->l_name }}</h6>
                                            <span
                                                @if($ticket->priority == 'Urgent')
                                                    class="badge rounded-pill bg-danger"
                                                @elseif($ticket->priority == 'High')
                                                    class="badge rounded-pill bg-warning"
                                                @elseif($ticket->priority == 'Medium')
                                                    class="badge rounded-pill bg-info"
                                                @else
                                                    class="badge rounded-pill bg-success"
                                                        @endif
                                                    >{{ translate($ticket->priority) }}</span>
                                        </div>
                                        <div class="fs-12 text-muted">{{ \App\CPU\customer_info()->email }}</div>
                                        <div class="d-flex flex-wrap align-items-center column-gap-4">
                                            <div class="d-flex align-items-center gap-2 gap-md-3">
                                                <div class="fw-bold">{{ translate('status') }}:</div>
                                                <span class="{{$ticket->status ==  'open' ? ' text-info ' : 'text-danger'}} fw-semibold">{{ translate($ticket->status) }}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 gap-md-3">
                                                <div class="fw-bold">{{ translate('priority') }}:</div>
                                                <span
                                                    @if($ticket->priority == 'Urgent')
                                                        class="text-danger fw-bold"
                                                    @elseif($ticket->priority == 'High')
                                                        class="text-warning fw-bold "
                                                    @elseif($ticket->priority == 'Medium')
                                                        class="text-info fw-bold"
                                                    @else
                                                        class="text-success fw-bold"
                                                        @endif
                                                    >
                                                    {{ translate($ticket->priority) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($ticket->status != 'close')
                            <a href="{{route('support-ticket.close',[$ticket['id']])}}" class="btn btn-outline-danger rounded">{{ translate('Close_this_ticket') }}</a>
                            @endif
                        </div>

                        <div class="messaging">
                            <div class="inbox_msg custom-scrollbar p-3 msg_history __h-30rem">
                                <div class="outgoing_msg">
                                    <p class="message_text">{{ $ticket['description']}}</p>
                                    <span class="time_date d-flex justify-content-end"> {{ date('h:i:A | M d',strtotime($ticket['created_at'])) }}<i class="bi px-1"></i></span>
                                </div>
                                @foreach($ticket->conversations as $conversation)
                                    @if($conversation['admin_message'] == null)

                                        <div class="outgoing_msg">
                                            @if($conversation['customer_message'])
                                                <p class="message_text">{{ $conversation['customer_message']}}</p>
                                            @endif

                                            @if ($conversation['attachment'] !=null && count(json_decode($conversation['attachment'])) > 0)
                                                <div class="d-flex flex-wrap g-2 gap-2 justify-content-end">
                                                    @foreach (json_decode($conversation['attachment']) as $key => $photo)
                                                        @if(file_exists(base_path("storage/app/public/support-ticket/".$photo)))
                                                            <div class="col-sm-6 col-md-3">
                                                                <img onerror="this.src=' {{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                                     src="{{asset('storage/app/public/support-ticket')}}/{{$photo}}" height="100" class="rounded"
                                                                     alt="img">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            <span class="time_date d-flex justify-content-end"> {{ date('h:i:A | M d',strtotime($conversation['created_at'])) }}<i class="bi px-1"></i></span>
                                        </div>
                                    @endif
                                    @if($conversation['customer_message'] == null)
                                        <div class="received_msg">
                                            @if($conversation['admin_message'])
                                                <p class="message_text">{{$conversation['admin_message']}}</p>
                                            @endif

                                            @if ($conversation['attachment'] !=null && count(json_decode($conversation['attachment'])) > 0)
                                                <div class="d-flex flex-wrap g-2 gap-2 justify-content-start">
                                                    @foreach (json_decode($conversation['attachment']) as $key => $photo)
                                                        @if(file_exists(base_path("storage/app/public/support-ticket/".$photo)))
                                                            <div class="col-sm-6 col-md-3">
                                                                <img onerror="this.src=' {{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                                     src="{{asset('storage/app/public/support-ticket')}}/{{$photo}}" height="100" class="rounded"
                                                                     alt="img">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            <span class="time_date"> {{ date('h:i:A | M d',strtotime($conversation['created_at'])) }} <i class="bi px-1"></i></span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="type_msg">
                                <form action="{{route('support-ticket.comment',[$ticket['id']])}}" method="post">
                                    @csrf
                                    <div class="input_msg_write border-top py-2 px-2 px-sm-3 d-flex align-items-start justify-content-between gap-2 gap-sm-3 lh-base">

                                        <textarea class="w-100 custom-height" style="--h: 5rem" id="msgInputValueTicket" name="comment" rows="6" placeholder="{{translate('start_typing')}}..."></textarea>
                                        <button class="btn btn-primary px-2 py-1 lh-1 rounded" type="submit">
                                            <i class="bi bi-send-fill fs-16"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $(".msg_history").stop().animate({scrollTop: $(".msg_history")[0].scrollHeight}, 1000);
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $(".chat_list").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endpush
