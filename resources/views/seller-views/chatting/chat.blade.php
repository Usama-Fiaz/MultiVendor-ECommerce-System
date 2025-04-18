@extends('layouts.back-end.app-seller')

@section('title',translate('chatting_Page'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/support-ticket.png')}}" alt="">
                {{translate('chatting_List')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Page Content-->
        <div class="row">
            @if(isset($chattings_user))
                <div class="col-xl-3 col-lg-4 chatSel">
                    <div class="card card-body px-0 h-100">
                        <div class="media align-items-center px-3 gap-3 mb-4">
                            <div class="avatar avatar-sm avatar-circle">
                                <img class="avatar-img" onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'" src="{{asset('storage/app/public/seller/')}}/{{auth('seller')->user()->image}}" alt="Image Description">
                                <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                            </div>
                            <div class="media-body">
                                <h5 class="profile-name mb-1">{{ $shop->name }}</h5>
                                <span class="fz-12">{{translate('seller')}}</span>
                            </div>
                        </div>

                        <div class="inbox_people">
                            <form class="search-form px-3" id="chat-search-form">
                                <div class="search-input-group">
                                    <i class="tio-search search-icon" aria-hidden="true"></i>
                                    <input
                                        class=""
                                        id="myInput" type="text"
                                        @if(Request::is('seller/messages/chat/customer'))
                                            placeholder="{{translate('search_customers')}}..."
                                        @elseif(Request::is('seller/messages/chat/delivery-man'))
                                            placeholder="{{translate('search_delivery_men')}}..."
                                        @endif
                                        aria-label="Search customers...">
                                </div>
                            </form>

                            <div class="inbox_chat d-flex flex-column mt-1">
                                @foreach($chattings_user as $key => $chatting)
                                    <div class="list_filter">
                                        <div class="chat_list p-3 d-flex gap-2 user_{{$chatting->user_id? $chatting->user_id : $chatting->delivery_man_id}} seller-list @if ($key == 0) active @endif"
                                             id="{{$chatting->user_id? $chatting->user_id : $chatting->delivery_man_id}}" data-name="{{$chatting->f_name}} {{$chatting->l_name}}" data-phone="{{ $chatting->phone }}"

                                             @if (Request::is('seller/messages/chat/customer'))
                                                data-image="{{ asset('storage/app/public/profile/'.$chatting->image) }}"
                                             @else
                                                data-image="{{ asset('storage/app/public/delivery-man/'.$chatting->image) }}"
                                             @endif

                                             onclick="message_view('{{$chatting->user_id? $chatting->user_id : $chatting->delivery_man_id}}')">
                                            <div class="chat_people media gap-10" id="chat_people">
                                                <div class="chat_img avatar avatar-sm avatar-circle">
                                                    <img
                                                        @if (Request::is('seller/messages/chat/customer'))
                                                            src="{{ asset('storage/app/public/profile/'.$chatting->image) }}"
                                                        @else
                                                            src="{{ asset('storage/app/public/delivery-man/'.$chatting->image) }}"
                                                        @endif
                                                        id="{{$chatting->user_id? $chatting->user_id : $chatting->delivery_man_id}}" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" class="avatar-img avatar-circle">
                                                    <span class="avatar-satatus avatar-sm-status avatar-status-success"></span>
                                                </div>
                                                <div class="chat_ib media-body">
                                                    <h5 class="mb-1 seller @if($chatting->seen_by_seller)active-text @endif"
                                                        id="{{$chatting->user_id? $chatting->user_id : $chatting->delivery_man_id}}" data-name="{{$chatting->f_name}} {{$chatting->l_name}}" data-phone="{{ $chatting->phone }}">
                                                        {{$chatting->f_name}} {{$chatting->l_name}}
                                                        <br><span class="mt-2 font-weight-normal text-muted" id="{{$chatting->user_id? $chatting->user_id: $chatting->delivery_man_id}}" data-name="{{$chatting->f_name}} {{$chatting->l_name}}" data-phone="{{ $chatting->phone }}">{{ $chatting->phone }}</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            @if($chatting->seen_by_seller == false)
                                                <div class="message-status bg-danger" id="notif-alert-{{ $chatting->user_id? $chatting->user_id: $chatting->delivery_man_id }}"></div>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <section class="col-xl-9 col-lg-8 mt-4 mt-lg-0">
                    <div class="card card-body card-chat justify-content-between Chat">
                        <!-- Inbox Message Header -->
                        <div class="inbox_msg_header d-flex flex-wrap gap-3 justify-content-between align-items-center border px-3 py-2 rounded mb-4">
                            <!-- Profile -->
                            <div class="media align-items-center gap-3">
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" id="profile_image"
                                         @if (Request::is('seller/messages/chat/customer'))
                                         src="{{ asset('storage/app/public/profile/'.$chattings_user[0]->image) }}"
                                         @else
                                         src="{{ asset('storage/app/public/delivery-man/'.$chattings_user[0]->image) }}"
                                         @endif
                                         onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'" alt="Image Description">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                                <div class="media-body">
                                    <h5 class="profile-name mb-1" id="profile_name">{{ $chattings_user[0]->f_name.' '.$chattings_user[0]->l_name }}</h5>
                                    <span class="fz-12" id="profile_phone">{{ $chattings_user[0]->phone }}</span>
                                </div>
                            </div>
                            <!-- End Profile -->

                        </div>
                        <!-- End Inbox Message Header -->

                        <div class="messaging">
                            <div class="inbox_msg">
                                <!-- Message Body -->
                                <div class="mesgs">
                                    <div class="msg_history d-flex flex-column-reverse pr-2" id="show_msg">
                                        @foreach($chattings as $key => $message)
                                                @if ( $message->sent_by_customer? $message->sent_by_customer: $message->sent_by_delivery_man)
                                                    <div class="incoming_msg">
                                                        <div class="received_msg">
                                                            <div class="received_withd_msg">

                                                                @if($message->message)
                                                                <div class="d-flex justify-content-start">
                                                                    <p class="bg-chat rounded px-3 py-2 mb-1">
                                                                        {{$message->message}}
                                                                    </p>
                                                                </div>
                                                                @endif

                                                                @if (json_decode($message['attachment']) !=null)
                                                                    <div class="row g-2 flex-wrap pt-1">
                                                                        @foreach (json_decode($message['attachment']) as $index => $photo)
                                                                            <div class="col-sm-3 col-md-2 position-relative img_row{{$index}}">
                                                                                <a data-lightbox="mygallery" href="{{asset("storage/app/public/chatting/".$photo)}}"
                                                                                class="aspect-1 overflow-hidden d-block border rounded">
                                                                                    <img onerror="this.src=' {{asset('public/assets/back-end/img/image-place-holder.png')}}'"
                                                                                         src="{{asset('storage/app/public/chatting')}}/{{$photo}}" class="img-fit"
                                                                                         alt="VR Collection">
                                                                                </a>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                                <span class="time_date fz-12 pt-2 d-flex justify-content-start"> {{$message->created_at->format('h:i A')}}    |    {{$message->created_at->format('M d')}} </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="outgoing_msg">
                                                        <div class="sent_msg p-2">
                                                            @if($message->message)
                                                            <div class="d-flex justify-content-end">
                                                                <p class="bg-c1 text-white rounded px-3 py-2 mb-1">
                                                                    {{$message->message}}
                                                                </p>
                                                            </div>
                                                            @endif

                                                            @if (json_decode($message['attachment']) !=null)
                                                                <div class="row g-2 flex-wrap pt-1 justify-content-end">
                                                                    @foreach (json_decode($message['attachment']) as $index => $photo)
                                                                        <div class="col-sm-3 col-md-2 position-relative img_row{{$index}}">
                                                                            <a data-lightbox="mygallery" href="{{asset("storage/app/public/chatting/".$photo)}}"
                                                                            class="aspect-1 overflow-hidden d-block border rounded">
                                                                                <img onerror="this.src=' {{asset('public/assets/back-end/img/image-place-holder.png')}}'"
                                                                                     src="{{asset('storage/app/public/chatting/'.$photo)}}"class="img-fit"
                                                                                     alt="VR Collection">
                                                                            </a>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            <span class="time_date fz-12 pt-2 d-flex justify-content-end"> {{$message->created_at->format('h:i A')}}    |    {{$message->created_at->format('M d')}} </span>
                                                        </div>
                                                    </div>
                                            @endif
                                        @endForeach
                                    </div>
                                    <div class="type_msg">
                                        <div class="input_msg_write">
                                            <form class="mt-4" id="myForm" enctype="multipart/form-data">
                                                @csrf
                                                    <input type="hidden" id="hidden_value"
                                                           value="{{$last_chat->user_id? $last_chat->user_id : $last_chat->delivery_man_id}}" name="user_id">

                                                <div class="position-relative">
                                                    @if(theme_root_path() == "default")
                                                    <label class="py-0 px-3 d-flex align-items-center m-0 cursor-pointer position-absolute top-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                            <path d="M18.1029 1.83203H3.89453C2.75786 1.83203 1.83203 2.75786 1.83203 3.89453V18.1029C1.83203 19.2395 2.75786 20.1654 3.89453 20.1654H18.1029C19.2395 20.1654 20.1654 19.2395 20.1654 18.1029V3.89453C20.1654 2.75786 19.2395 1.83203 18.1029 1.83203ZM3.89453 3.20703H18.1029C18.4814 3.20703 18.7904 3.51595 18.7904 3.89453V12.7642L15.2539 9.2277C15.1255 9.09936 14.9514 9.02603 14.768 9.02603H14.7653C14.5819 9.02603 14.405 9.09936 14.2776 9.23136L10.3204 13.25L8.65845 11.5945C8.53011 11.4662 8.35595 11.3929 8.17261 11.3929C7.9957 11.3654 7.81053 11.4662 7.6822 11.6009L3.20703 16.1705V3.89453C3.20703 3.51595 3.51595 3.20703 3.89453 3.20703ZM3.21253 18.1304L8.17903 13.0575L13.9375 18.7904H3.89453C3.52603 18.7904 3.22811 18.4952 3.21253 18.1304ZM18.1029 18.7904H15.8845L11.2948 14.2189L14.7708 10.6898L18.7904 14.7084V18.1029C18.7904 18.4814 18.4814 18.7904 18.1029 18.7904Z" fill="#1455AC"/>
                                                            <path d="M8.12834 9.03012C8.909 9.03012 9.54184 8.39728 9.54184 7.61662C9.54184 6.83597 8.909 6.20312 8.12834 6.20312C7.34769 6.20312 6.71484 6.83597 6.71484 7.61662C6.71484 8.39728 7.34769 9.03012 8.12834 9.03012Z" fill="#1455AC"/>
                                                        </svg>
                                                        <input type="file" id="msgfilesValue" class="h-100 position-absolute w-100 " hidden multiple accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                    </label>
                                                    @endif

                                                    <textarea class="form-control {{ theme_root_path() == "default" ? 'pl-8':'' }}" id="msgInputValue" name="message"
                                                              type="text" placeholder="{{translate('send_a_message')}}"
                                                              aria-label="Search"></textarea>
                                                </div>


                                                <div class="mt-3 d-flex justify-content-between">
                                                    <div class="">
                                                        <div class="d-flex gap-3 flex-wrap filearray"></div>
                                                        <div id="selected-files-container"></div>
                                                    </div>

                                                    <div>
                                                        <button class="aSend btn btn--primary" type="submit" id="msgSendBtn">{{translate('send_Reply')}}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Message Body -->
                            </div>
                        </div>
                    </div>

                </section>

            @else
                <div class="offset-md-1 col-md-10 d-flex justify-content-center align-items-center">
                    <p>{{translate('no_conversation_found')}}</p>
                </div>
            @endif

        </div>


    </div>

@endsection

@push('script')
    <script>
        $(document).ready(function () {

            $("#myInput").on("keyup", function (e) {
                var value = $(this).val().toLowerCase();
                $(".list_filter").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            //Chat Search Form
            $('#chat-search-form').on('submit', function(e) {
                e.preventDefault();
            });

            $('#myForm').on('submit', function (event) {
                event.preventDefault();
                var user_id = $('#hidden_value').val();
                let formData = new FormData(document.getElementById('myForm'));

                let post_url;
                if ("{{ Request::is('seller/messages/chat/customer') }}" == true){
                    post_url = "{{ route('seller.messages.ajax-seller-message-store') }}" +"?user_id=" + user_id;
                }
                else if("{{ Request::is('seller/messages/chat/delivery-man') }}" == true) {
                    post_url = "{{ route('seller.messages.ajax-seller-message-store') }}" +"?delivery_man_id=" + user_id;
                }

                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
                });

                $.ajax({
                    type: "POST",
                    url: post_url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $("#msgSendBtn").attr('disabled', true);
                    },
                    success: function (response) {
                        let msg_history = $(".msg_history");
                        let dateTime = new Date(response.time);
                        let month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                        let time = dateTime.toLocaleTimeString().toLowerCase();
                        let date = month[dateTime.getMonth().toString()] + " " + dateTime.getDate().toString();

                        let imageContainer = ''
                        if(response.image.length !== 0){
                            response.image.forEach(function (imageUrl, index) {
                                let img_path = `{{ asset('storage/app/public/chatting') }}/${imageUrl}`;
                                imageContainer += `
                                    <div class="col-sm-3 col-md-2 position-relative img_row${index}">
                                        <a data-lightbox="mygallery" href="${img_path}" class="aspect-1 overflow-hidden d-block border rounded">
                                            <img onerror="this.src='{{ asset('public/assets/back-end/img/image-place-holder.png') }}'"
                                                src="${img_path}" alt="img" class="img-fit">
                                        </a>
                                    </div>`;
                            });
                        }

                        let message = response.message ? `<div class="d-flex justify-content-end">
                                            <p class="bg-c1 text-white rounded px-3 py-2 mb-1"">${response.message}</p>
                                        </div>` : '';

                        msg_history.prepend(`
                                  <div class="outgoing_msg" id="outgoing_msg">
                                    <div class='sent_msg'>
                                    ${message}
                                    <div class="row g-2 flex-wrap mt-3 justify-content-end">
                                        ${imageContainer}
                                    </div>
                                      <span class='time_date d-flex justify-content-end'> now </span>
                                    </div>
                                  </div>`
                        )
                        $(this).trigger('reset');

                        msg_history.stop().animate({scrollTop: msg_history[0].scrollHeight}, 1000);
                        $('.filearray').empty().html('');
                        $('#selected-files-container').empty().html('');
                        $('#msgInputValue').val('');
                        $('#msgfilesValue').val('');
                        selectedFiles = [];
                    },
                    complete: function () {
                        $("#msgSendBtn").removeAttr('disabled', true);
                    },
                    error: function (error) {
                        toastr.warning(error.responseText);
                    }
                });

            });
        });
    </script>

    <script>
        let selectedFiles = [];
        $(document).on('ready', () => {
            $("#msgfilesValue").on('change', function () {
                for (let i = 0; i < this.files.length; ++i) {
                    selectedFiles.push(this.files[i]);
                }
                // Display the selected files
                displaySelectedFiles();
            });

            function displaySelectedFiles() {
                /*start*/
                const container = document.getElementById("selected-files-container");
                container.innerHTML = ""; // Clear previous content
                selectedFiles.forEach((file, index) => {
                    const input = document.createElement("input");
                    input.type = "file";
                    input.name = `image[${index}]`;
                    input.classList.add(`image_index${index}`);
                    input.hidden = true;
                    container.appendChild(input);
                    /*BlobPropertyBag :
                    / This type represents a collection of object properties and does not have an
                    / explicit JavaScript representation.
                    */
                    const blob = new Blob([file], { type: file.type });
                    const file_obj = new File([file],file.name);
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file_obj);
                    input.files = dataTransfer.files;
                });
                /*end */
                $(".filearray").empty(); // Clear previous user input
                for (let i = 0; i < selectedFiles.length; ++i) {
                    let filereader = new FileReader();
                    let $uploadDiv = jQuery.parseHTML("<div class='upload_img_box'><span class='img-clear'><i class='tio-clear'></i></span><img src='' alt=''></div>");

                    filereader.onload = function () {
                        // Set the src attribute of the img tag within the created div
                        $($uploadDiv).find('img').attr('src', this.result);
                        let imageData = this.result;
                    };

                    filereader.readAsDataURL(selectedFiles[i]);
                    $(".filearray").append($uploadDiv);
                    // Attach a click event handler to the "tio-clear" icon to remove the associated div and file from the array
                    $($uploadDiv).find('.img-clear').on('click', function () {
                        $(this).closest('.upload_img_box').remove();

                        selectedFiles.splice(i, 1);
                        $('.image_index'+i).remove();
                    });
                }
            }
        });
    </script>

    <script>
        function message_view(user_id)
        {
            let user_gen_id = '.user_'+user_id;
            let customer_name = $(user_gen_id).data('name');
            let customer_phone = $(user_gen_id).data('phone');
            let customer_image = $(user_gen_id).data('image');

            $('#profile_name').text(customer_name)
            $('#profile_phone').text(customer_phone)
            $('#profile_image').attr("src", customer_image)

            //active when click on seller
            $('.chat_list.active').removeClass('active');
            $(`.user_${user_id}`).addClass("active");
            $('.seller').css('color', 'black');
            $(`.user_${user_id} h5`).css('color', '#377dff');
            // $('.inbox_chat').find('h5.active-text').removeClass(".active-text");

            let url;

            if ("{{ Request::is('seller/messages/chat/customer') }}" == true){
                url = "{{ route('seller.messages.ajax-message-by-user') }}" +"?user_id=" + user_id;
            }
            else if("{{ Request::is('seller/messages/chat/delivery-man') }}" == true) {
                url = "{{ route('seller.messages.ajax-message-by-user') }}" +"?delivery_man_id=" + user_id;
            }

            $.ajax({
                type: "get",
                url: url,

                success: function (data) {
                    $('.msg_history').html('');
                    $('.chat_ib').find('#' + user_id).removeClass('active-text');
                    //$(".msg_history").stop().animate({scrollTop: $(".msg_history")[0].scrollHeight}, 1000);

                    if (data.length != 0) {
                        data.map((element, index) => {
                            let dateTime = new Date(element.created_at);
                            let month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                            let time = dateTime.toLocaleTimeString().toLowerCase();
                            let date = month[dateTime.getMonth().toString()] + " " + dateTime.getDate().toString();

                            let attachment_files = element.attachment;
                            let imageContainer = ''
                            if(attachment_files.length !== 0){
                                JSON.parse(attachment_files).map((imageUrl, index) => {
                                    let img_path = `{{ asset('storage/app/public/chatting') }}/${imageUrl}`;
                                    imageContainer += `
                                    <div class="col-sm-3 col-md-2 position-relative img_row${index}">
                                        <a data-lightbox="mygallery" href="${img_path}" class="aspect-1 overflow-hidden d-block border rounded">
                                            <img onerror="this.src='{{ asset('public/assets/back-end/img/image-place-holder.png') }}'"
                                                src="${img_path}" alt="img" class="img-fit">
                                        </a>
                                    </div>`;
                                });
                            }

                            let seller_message = element.message ? `<div class='d-flex justify-content-end'>
                                          <p class="bg-c1 text-white rounded px-3 py-2">${element.message}</p>
                                        </div>` : '';

                            if (element.sent_by_seller) {
                                $(".msg_history").prepend(`
                                      <div class="outgoing_msg" id="outgoing_msg">
                                        <div class='sent_msg'>
                                        ${seller_message}
                                        <div class="row g-2 flex-wrap pt-1 justify-content-end">
                                                ${imageContainer}
                                        </div>

                                          <span class='time_date fz-12 pt-2 d-flex justify-content-end'> ${time}    |    ${date}</span>
                                        </div>
                                      </div>`
                                )

                            } else {
                                $(".msg_history").prepend(`
                                      <div class="incoming_msg" id="incoming_msg">
                                        <div class="received_msg">
                                          <div class="received_withd_msg">
                                          <div class='d-flex justify-content-start'>
                                            <p class="bg-chat rounded px-3 py-2 mb-1" id="receive_msg">${element.message}</p>
                                        </div>
                                            <div class="row g-2 flex-wrap pt-1 justify-content-start mb-1">
                                                ${imageContainer}
                                            </div>
                                          <span class="time_date fz-12"> ${time}    |    ${date}</span></div>
                                        </div>
                                      </div>`
                                )
                            }

                            $('#hidden_value').attr("value", user_id);
                            $('#notif-alert-'+user_id).hide();
                        })
                    } else {
                        $(".msg_history").html(`<p> {{translate('no_message_available')}} </p>`);
                        data = [];
                    }

                }
            });

            $('.type_msg').css('display', 'block');
        }
    </script>

@endpush

