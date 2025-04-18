<?php
    $current_customer ='';
    if(explode('-',session('current_user'))[0]=='wc')
    {
        $current_customer = 'Walking Customer';
        $user_id = 0;
        $current_customer_data = \App\User::where('id',0)->first();
    }else{
        $user_id = explode('-',session('current_user'))[1];
        $current_customer_data = \App\User::where('id',$user_id)->first();
        $current_customer = $current_customer_data->f_name.' '.$current_customer_data->l_name. ' (' .$current_customer_data->phone.')';
    }

    // View ALL Hold Orders
    $cart_names = [];
    $total_hold_orders = 0;
    if (session()->has('cart_name')){
        foreach (session('cart_name') as $item){
            if (session()->has($item) && count(session($item)) > 1){
                $total_hold_orders++;
                array_push($cart_names, $item);
            }
        }
    }
?>

<div class="form-group">
    <label class="text-capitalize title-color d-flex align-items-center flex-wrap gap-1">
        {{translate('current_customer')}} :
        <span class="mb-0">{{ $current_customer }}</span>
    </label>
</div>

<div class="pos-home-delivery mb-4">
    <div class="d-flex justify-content-between gap-2 mb-3">
        <div class="d-flex gap-2">
            <i class="tio-user-big"></i>
            <h4 class="card-title">{{ translate('customer_Information') }} </h4>
        </div>
    </div>

    <div class="row gy-2">
        <div class="col-sm-12">
            <div class="pair-list">
                <div>
                    <span class="key" style="--flex-basis: 60px">{{ translate('name') }}</span>
                    <span>:</span>
                    <span class="value">{{ $current_customer == 'Walking Customer' ? 'Walking Customer' : $current_customer_data->f_name.' '.$current_customer_data->l_name }}</span>
                </div>

                @if ($current_customer != 'Walking Customer')
                <div>
                    <span class="key" style="--flex-basis: 60px">{{ translate('contact') }}</span>
                    <span>:</span>
                    <a href="tel:{{ $current_customer_data->phone }}"
                        class="value text-dark">{{ $current_customer_data->phone }}</a>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>

<div class="d-flex gap-2 flex-wrap flex-sm-nowrap mb-3">
    <div class="dropdown w-100" id="dropdown-order-select">
        <button class="form-control text-start dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false" id="cart_id_primary">
            {{ session('current_user') }}
        </button>
        <div class="dropdown-menu px-2">
            @foreach ($cart_names as $cart_name)
                <button class="dropdown-item border rounded mb-1" onclick="cart_change('{{ $cart_name }}')">{{ $cart_name }}</button>
            @endforeach

            <button class="dropdown-item border rounded mt-2" onclick="view_all_hold_orders()">
                <div class="d-flex align-items-center gap-2">
                    <i class="tio-pause"></i>
                    {{translate('view_all_hold_orders')}}
                    <span class="badge badge-danger rounded-circle">{{ $total_hold_orders }}</span>
                </div>
            </button>
        </div>
    </div>
    <a class="btn btn-secondary rounded text-nowrap" onclick="clear_cart()">
        {{ translate('clear_Cart')}}
    </a>
    <a class="btn btn-info rounded text-nowrap" onclick="new_order()">
        {{ translate('new_Order')}}
    </a>
</div>

@include('seller-views.pos._cart',['cart_id'=>session('current_user')])
