<div class="modal fade" id="chatting_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-faded-info">
                <h5 class="modal-title" id="exampleModalLongTitle">{{translate('Send_Message_to_seller')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('messages_store')}}" method="post" id="chat-form">
                    @csrf
                    @if(isset($seller->shop->id) && $seller->shop->id != 0)
                        <input value="{{$seller->shop->id}}" name="shop_id" hidden>
                        <input value="{{$seller->id}}}" name="seller_id" hidden>
                    @endif

                    <textarea name="message" class="form-control" required placeholder="{{ translate('Write_here') }}..."></textarea>
                    <br>
                    <div class="justify-content-end gap-2 d-flex flex-wrap">
                        <a href="{{route('chat', ['type' => 'seller'])}}" class="btn btn-soft-primary bg--secondary border">
                            {{translate('go_to_chatbox')}}
                        </a>
                        @if(isset($seller->shop->id) && $seller->shop->id  != 0)
                            <button
                                class="btn btn--primary text-white">{{translate('send')}}</button>
                        @else
                            <button class="btn btn--primary text-white"
                                    disabled>{{translate('send')}}</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
