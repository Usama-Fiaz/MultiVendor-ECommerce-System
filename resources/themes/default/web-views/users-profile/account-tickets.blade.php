@extends('layouts.front-end.app')

@section('title',translate('My_Support_Tickets'))
@section('content')

    <div class="modal fade rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" id="open-ticket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg  " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-md-12"><h5
                                class="modal-title font-nameA ">{{translate('submit_new_ticket')}}</h5></div>
                        <div class="col-md-12 text-black mt-3">
                            <span>{{translate('you_will_get_response')}}.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="mt-3" method="post" action="{{route('ticket-submit')}}" id="open-ticket" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="firstName">{{translate('subject')}}</label>
                                <input type="text" class="form-control" id="ticket-subject" name="ticket_subject"
                                       required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label class="" for="inlineFormCustomSelect">{{translate('type')}}</label>
                                    <select class="custom-select " id="ticket-type" name="ticket_type" required>
                                        <option
                                            value="Website problem">{{translate('website_problem')}}</option>
                                        <option value="Partner request">{{translate('partner_request')}}</option>
                                        <option value="Complaint">{{translate('complaint')}}</option>
                                        <option
                                            value="Info inquiry">{{translate('info_inquiry')}} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label class="" for="inlineFormCustomSelect">{{translate('priority')}}</label>
                                    <select class="form-control custom-select" id="ticket-priority"
                                            name="ticket_priority" required>
                                        <option value>{{translate('choose_priority')}}</option>
                                        <option value="Urgent">{{translate('urgent')}}</option>
                                        <option value="High">{{translate('high')}}</option>
                                        <option value="Medium">{{translate('medium')}}</option>
                                        <option value="Low">{{translate('low')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="detaaddressils">{{translate('describe_your_issue')}}</label>
                                <textarea class="form-control" rows="6" id="ticket-description"
                                          name="ticket_description"></textarea>
                            </div>
                        </div>

                        <label class="" for="inlineFormCustomSelect">{{translate('Attachment')}}</label>
                        <div class="d-flex flex-wrap upload_images_area">

                            <div class="d-flex flex-wrap filearray"></div>
                            <div class="selected-files-container"></div>

                            <label class="py-0 d-flex align-items-center m-0 cursor-pointer">
                                        <span class="position-relative">
                                            <img class="border rounded border-primary-light h-70px"
                                                 src="{{asset('public/assets/front-end/img/image-place-holder.png')}}" alt="">
                                        </span>
                                <input type="file" id="attachmentfiles" class="attachmentfiles h-100 position-absolute w-100 " hidden multiple accept="image/*">
                            </label>

                        </div>

                        <div class="modal-footer p-0 border-0">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{translate('close')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('submit_a_ticket')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Content-->
    <div class="container py-4 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
            @include('web-views.partials._profile-aside')

            <!-- Content  -->
            <section class="col-lg-9 __customer-profile d-none d-md-block">
                <!-- Tickets list-->
                <div class="mb-3 d-lg-none text-right">
                    <button class="profile-aside-btn btn btn--primary px-2 rounded px-2 py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7 9.81219C7 9.41419 6.842 9.03269 6.5605 8.75169C6.2795 8.47019 5.898 8.31219 5.5 8.31219C4.507 8.31219 2.993 8.31219 2 8.31219C1.602 8.31219 1.2205 8.47019 0.939499 8.75169C0.657999 9.03269 0.5 9.41419 0.5 9.81219V13.3122C0.5 13.7102 0.657999 14.0917 0.939499 14.3727C1.2205 14.6542 1.602 14.8122 2 14.8122H5.5C5.898 14.8122 6.2795 14.6542 6.5605 14.3727C6.842 14.0917 7 13.7102 7 13.3122V9.81219ZM14.5 9.81219C14.5 9.41419 14.342 9.03269 14.0605 8.75169C13.7795 8.47019 13.398 8.31219 13 8.31219C12.007 8.31219 10.493 8.31219 9.5 8.31219C9.102 8.31219 8.7205 8.47019 8.4395 8.75169C8.158 9.03269 8 9.41419 8 9.81219V13.3122C8 13.7102 8.158 14.0917 8.4395 14.3727C8.7205 14.6542 9.102 14.8122 9.5 14.8122H13C13.398 14.8122 13.7795 14.6542 14.0605 14.3727C14.342 14.0917 14.5 13.7102 14.5 13.3122V9.81219ZM12.3105 7.20869L14.3965 5.12269C14.982 4.53719 14.982 3.58719 14.3965 3.00169L12.3105 0.915687C11.725 0.330188 10.775 0.330188 10.1895 0.915687L8.1035 3.00169C7.518 3.58719 7.518 4.53719 8.1035 5.12269L10.1895 7.20869C10.775 7.79419 11.725 7.79419 12.3105 7.20869ZM7 2.31219C7 1.91419 6.842 1.53269 6.5605 1.25169C6.2795 0.970186 5.898 0.812187 5.5 0.812187C4.507 0.812187 2.993 0.812187 2 0.812187C1.602 0.812187 1.2205 0.970186 0.939499 1.25169C0.657999 1.53269 0.5 1.91419 0.5 2.31219V5.81219C0.5 6.21019 0.657999 6.59169 0.939499 6.87269C1.2205 7.15419 1.602 7.31219 2 7.31219H5.5C5.898 7.31219 6.2795 7.15419 6.5605 6.87269C6.842 6.59169 7 6.21019 7 5.81219V2.31219Z" fill="white"/>
                            </svg>
                    </button>
                </div>
                <div class="card __card h-100">
                    <div class="p-md-3">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                            <h5 class="mb-0">{{translate('support_ticket')}}</h5>
                            <button type="submit" class="btn btn--primary float-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" data-toggle="modal" data-target="#open-ticket">
                                {{translate('add_new_ticket')}}
                            </button>
                        </div>
                        @if ($supportTickets->count() >0)
                            <div class="table-responsive">
                                <table class="table mb-0 __table-2 fs-13">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0">
                                                <span class="d-block">{{translate('topic')}}</span>
                                            </th>
                                            <th class="border-0">
                                                <span class="d-block">{{translate('submition_date')}}</span>
                                            </th>
                                            <th class="border-0">
                                                <span class="d-block">{{translate('type')}}</span>
                                            </th>
                                            <th class="border-0">
                                                <span class="d-block">
                                                    {{translate('status')}}
                                                </span>
                                            </th>
                                            <th class="border-0 text-center">
                                                <span class="d-block">{{translate('action')}} </span>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($supportTickets as $ticket)
                                            <tr>
                                                <td>
                                                    <span class="marl">{{$ticket['subject']}}</span>
                                                </td>
                                                <td>
                                                    <span>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$ticket['created_at'])->format('Y-m-d h:i A')}}</span>
                                                </td>
                                                <td><span>{{translate($ticket['type'])}}</span></td>
                                                <td>
                                                    <span class="badge __badge rounded-full {{$ticket['status']=='open' ? 'badge-warning': 'badge-danger'}}">
                                                        {{translate($ticket['status'])}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn--container flex-nowrap justify-content-center">
                                                        <a class="__action-btn btn-shadow rounded-full text-primary"
                                                        href="{{route('support-ticket.index',$ticket['id'])}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <button class="__action-btn btn-shadow rounded-full text-danger marl delete-ticket-by-modal" data-link="{{ route('support-ticket.delete',['id'=>$ticket->id])}}">
                                                            <i class="czi-trash"></i>
                                                        </button>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    @if ($supportTickets->count() <=0)
                        <div class="text-center pt-3 text-capitalize">
                            <img src="{{asset('public/assets/front-end/img/icons/nodata.svg')}}" alt="" width="70">
                            <h5 class="fs-14 mt-4">{{translate('no_ticket_found')}}!</h5>
                        </div>
                    @endif
                </div>
            </section>
        </div>


        <div class="d-flex align-items-center justify-content-between gap-2 mb-3 d-md-none">
            <h5 class="mb-0">{{translate('support_ticket')}}</h5>

            <button class="profile-aside-btn btn btn--primary px-2 rounded px-2 py-1 d-lg-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7 9.81219C7 9.41419 6.842 9.03269 6.5605 8.75169C6.2795 8.47019 5.898 8.31219 5.5 8.31219C4.507 8.31219 2.993 8.31219 2 8.31219C1.602 8.31219 1.2205 8.47019 0.939499 8.75169C0.657999 9.03269 0.5 9.41419 0.5 9.81219V13.3122C0.5 13.7102 0.657999 14.0917 0.939499 14.3727C1.2205 14.6542 1.602 14.8122 2 14.8122H5.5C5.898 14.8122 6.2795 14.6542 6.5605 14.3727C6.842 14.0917 7 13.7102 7 13.3122V9.81219ZM14.5 9.81219C14.5 9.41419 14.342 9.03269 14.0605 8.75169C13.7795 8.47019 13.398 8.31219 13 8.31219C12.007 8.31219 10.493 8.31219 9.5 8.31219C9.102 8.31219 8.7205 8.47019 8.4395 8.75169C8.158 9.03269 8 9.41419 8 9.81219V13.3122C8 13.7102 8.158 14.0917 8.4395 14.3727C8.7205 14.6542 9.102 14.8122 9.5 14.8122H13C13.398 14.8122 13.7795 14.6542 14.0605 14.3727C14.342 14.0917 14.5 13.7102 14.5 13.3122V9.81219ZM12.3105 7.20869L14.3965 5.12269C14.982 4.53719 14.982 3.58719 14.3965 3.00169L12.3105 0.915687C11.725 0.330188 10.775 0.330188 10.1895 0.915687L8.1035 3.00169C7.518 3.58719 7.518 4.53719 8.1035 5.12269L10.1895 7.20869C10.775 7.79419 11.725 7.79419 12.3105 7.20869ZM7 2.31219C7 1.91419 6.842 1.53269 6.5605 1.25169C6.2795 0.970186 5.898 0.812187 5.5 0.812187C4.507 0.812187 2.993 0.812187 2 0.812187C1.602 0.812187 1.2205 0.970186 0.939499 1.25169C0.657999 1.53269 0.5 1.91419 0.5 2.31219V5.81219C0.5 6.21019 0.657999 6.59169 0.939499 6.87269C1.2205 7.15419 1.602 7.31219 2 7.31219H5.5C5.898 7.31219 6.2795 7.15419 6.5605 6.87269C6.842 6.59169 7 6.21019 7 5.81219V2.31219Z" fill="white"/>
                    </svg>
            </button>
        </div>

        <div class="d-flex flex-column gap-3 d-md-none">
            @foreach($supportTickets as $ticket)
            <div class="bg-white border rounded p-3 shadow-sm" onclick="location.href='{{route('support-ticket.index',$ticket['id'])}}'">
                <div class="d-flex justify-content-between gap-2 align-items-end">
                    <div class="d-flex flex-column gap-1 fs-12">
                        <div class="d-flex gap-2">
                            <div class="text-muted">{{translate('topic')}} :</div>
                            <div>{{$ticket['subject']}}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="text-muted">{{translate('submition_date')}} :</div>
                            <div>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$ticket['created_at'])->format('Y-m-d h:i A')}}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="text-muted">{{translate('type')}} :</div>
                            <div>{{translate($ticket['type'])}}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="text-muted">{{translate('status')}} :</div>
                            <div>
                                <span class="badge __badge rounded-full {{$ticket['status']=='open' ? 'badge-warning': 'badge-danger'}}">
                                    {{translate($ticket['status'])}}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a class="__action-btn btn-shadow rounded-full text-danger stopPropagation" href="javascript:"
                            onclick="Swal.fire({
                                title: '{{translate('do_you_want_to_delete_this')}}?',
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonColor: '{{$web_config['primary_color']}}',
                                cancelButtonColor: '{{$web_config['secondary_color']}}',
                                confirmButtonText: `{{translate('yes')}}`,
                                cancelButtonText: `{{translate('cancel')}}`,
                                denyButtonText: `{{translate('Do_not_delete')}}`,
                                }).then((result) => {
                                if (result.value) {
                                Swal.fire(`{{translate('deleted!')}}`, '', 'success')
                                location.href='{{ route('support-ticket.delete',['id'=>$ticket->id])}}';
                                } else{
                                Swal.fire(`{{translate('cancelled')}}`, '', 'info')
                                }
                                })" id="delete" class=" marl">
                            <i class="czi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
            @if ($supportTickets->count() <=0)
                <div class="text-center pt-3 text-capitalize">
                    <img src="{{asset('public/assets/front-end/img/icons/address.svg')}}" alt="" width="70">
                    <h5 class="fs-14 mt-2">{{translate('no_address_found')}}!</h5>
                </div>
            @endif
        </div>

    </div>

    <div class="bottom-sticky_offset"></div>
    <div class="bottom-sticky_ele bg-white d-md-none p-3">
        <button type="submit" class="btn btn--primary w-100" data-toggle="modal" data-target="#open-ticket">
            {{translate('add_new_ticket')}}
        </button>
    </div>

    <div class="modal fade" id="delete-ticket-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-5">
                    <div class="text-center">
                        <img src="{{asset('/public/assets/front-end/img/icons/ticket-delete.png')}}" alt="">
                        <h6 class="font-semibold mt-3 mb-1">{{translate('Delete_this_ticket')}}?</h6>
                        <p class="mb-4">
                            <small>{{translate('This_ticket_will_be_removed_from_this_list')}}</small>
                        </p>
                    </div>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="javascript:" class="btn btn-primary __rounded-10" id="delete-ticket-link">
                            {{translate('Remove')}}
                        </a>
                        <button class="btn btn-soft-secondary bg--secondary __rounded-10" data-dismiss="modal">
                            {{translate('Cancel')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    $(document).ready(function() {

        $('.delete-ticket-by-modal').on('click', function (){
            let link = $(this).data('link');
            $('#delete-ticket-link').attr('href', link);
            $('#delete-ticket-modal').modal('show');
        });

        const $stickyElement = $('.bottom-sticky_ele');
        const $offsetElement = $('.bottom-sticky_offset');

        if($stickyElement.length !== 0){
            $(window).on('scroll', function() {
                const elementOffset = $offsetElement.offset().top;
                const scrollTop = $(window).scrollTop();

                if (scrollTop >= elementOffset) {
                    $stickyElement.addClass('stick');
                } else {
                    $stickyElement.removeClass('stick');
                }
            });
        }
    });

    let reviewselectedFiles = [];
    $(document).on('ready', () => {
        $(".attachmentfiles").on('change', function () {
            for (let i = 0; i < this.files.length; ++i) {
                reviewselectedFiles.push(this.files[i]);
            }
            let pre_container = $(this).closest('.upload_images_area');
            // Display the selected files
            displaySelectedFiles(pre_container);
        });

        function displaySelectedFiles(pre_container = null) {
            /*start*/
            let container;
            if(pre_container == null) {
                container = document.getElementsByClassName("selected-files-container");
            }else {
                container = pre_container.find('.selected-files-container');
            }
            container.innerHTML = ""; // Clear previous content
            reviewselectedFiles.forEach((file, index) => {
                const input = document.createElement("input");
                input.type = "file";
                input.name = `image[${index}]`;
                input.classList.add(`image_index${index}`);
                input.hidden = true;
                container.append(input);
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
            pre_container.find(".filearray").empty(); // Clear previous user input
            for (let i = 0; i < reviewselectedFiles.length; ++i) {
                let filereader = new FileReader();
                let uploadDiv = jQuery.parseHTML("<div class='upload_img_box mb-2'><span class='img-clear'><i class='tio-clear'></i></span><img src='' alt=''></div>");

                filereader.onload = function () {
                    // Set the src attribute of the img tag within the created div
                    let imageData = this.result;
                    $(uploadDiv).find('img').attr('src', imageData);
                };

                filereader.readAsDataURL(reviewselectedFiles[i]);
                pre_container.find(".filearray").append(uploadDiv);
                // Attach a click event handler to the "tio-clear" icon to remove the associated div and file from the array
                $(uploadDiv).find('.img-clear').on('click', function () {
                    $(this).closest('.upload_img_box').remove();

                    reviewselectedFiles.splice(i, 1);
                    $('.image_index'+i).remove();
                });
            }
        }
    });
</script>
@endpush
