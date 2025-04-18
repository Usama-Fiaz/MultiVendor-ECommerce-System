<html>
    <table>
        <thead>
            <tr>
                <th style="font-size: 18px">{{translate('order_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('filter_criteria') }} -</th>
                <th></th>
                <th>
                    @if($data['status'] != 'all')
                        {{translate('order_Status')}} - {{translate($data['status'] != 'failed' ? $data['status'] : 'failed_to_deliver')}}
                        <br>
                    @endif
                        {{translate('search_Bar_Content')}} - {{$data['search'] ?? 'N/A'}}
                    <br>
                        {{translate('order_Type')}} - {{translate($data['order_type']=='admin' ? 'inhouse' : $data['order_type'] )}}
                    <br>
                        {{translate('store')}} - {{ucwords($data['seller']?->shop?->name ?? translate('all'))}}
                    <br>
                        {{translate('customer_Name')}} - {{ucwords(isset($data['customer']->f_name) ? $data['customer']->f_name.' '.$data['customer']->l_name : translate('all_customers') )}}
                    <br>
                        {{translate('date_type')}} - {{translate(!empty($data['date_type']) ? $data['date_type'] : 'all')}}
                    <br>
                    @if ($data['date_type'] == 'custom_date')
                            {{translate('from')}} - {{$data['from'] ?? date('d M, Y',strtotime($data['from']))}}
                        <br>
                            {{translate('to')}} - {{$data['to'] ?? date('d M, Y',strtotime($data['to']))}}
                        <br>
                    @endif
                </th>
            </tr>
            <tr>
                @if($data['status'] == 'all')
                    <th>{{translate('order_Status')}}</th>
                    <th></th>
                    <th>
                        @foreach ($data['status_array'] as $key=>$value)
                            {{translate($key != 'failed' ? $key : 'failed_to_deliver')}} - {{$value}}
                        @endforeach
                    </th>
                @endif
            </tr>
            <tr>
                <td> {{translate('SL')}}	</td>
                <td> {{translate('Order_ID')}}	</td>
                <td> {{translate('Order_Date')}}	</td>
                <td> {{translate('Customer_Name')}}	</td>
                <td> {{translate('Store_Name')}}	</td>
                <td> {{translate('Total_Items')}}	</td>
                <td> {{translate('Item_Price')}}	</td>
                <td> {{translate('Item_Discount')}}	</td>
                <td> {{translate('Coupon_Discount')}}	</td>
                <td> {{translate('extra_Discount')}}	</td>
                <td> {{translate('Discounted_Amount')}}	</td>
                <td> {{translate('Vat/Tax')}}	</td>
                <td> {{translate('shipping')}}	</td>
                <td> {{translate('Total_Amount')}}	</td>
                <td> {{translate('Payment_Status')}}</td>
                @if($data['status'] == 'all')
                <td> {{translate('Order_Status')}}</td>
                @endif
            </tr>
            <!-- loop  you data -->
            @foreach ($data['orders'] as $key=>$order)
                <tr>
                    <td> {{++$key}}	</td>
                    <td> {{$order->id}}	</td>
                    <td> {{date('d M, Y h:i A',strtotime($order->created_at))}}</td>
                    <td> {{ucwords($order->is_guest == 0 ? (($order?->customer?->f_name ?? translate('not_found')) .' '. $order?->customer?->l_name) : translate('guest_customer'))}}	</td>
                    <td> {{ucwords($order?->seller_is == 'seller' ? ($order?->seller?->shop->name ?? translate('not_found')) : translate('inhouse'))}}	</td>
                    <td> {{$order->total_qty}} </td>
                    <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->total_price))}} </td>
                    <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->total_discount))}} </td>
                    <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->discount_amount))}}</td>
                    @php
                        if ($order->extra_discount_type == 'percent') {
                            $extra_discount = $order->total_price*$order->extra_discount /100;
                        }else {
                            $extra_discount = $order->extra_discount;
                        }
                    @endphp
                    <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($extra_discount))}}</td>
                    <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->total_price-$order->total_discount- $order->discount_amount -($order->is_shipping_free == 0 ? $extra_discount : 0)))}}  </td>
                    <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->total_tax))}}	</td>
                    <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->is_shipping_free == 0 ? $order->shipping_cost : 0))}}	</td>
                    <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount + ($order->is_shipping_free == 1 ? $extra_discount : 0)))}}</td>
                    <td> {{translate($order->payment_status)}}</td>
                    @if($data['status'] == 'all')
                        <td> {{translate($order->order_status)}}</td>
                    @endif
                </tr>
            @endforeach
            <!-- end -->
        </thead>
    </table>
</html>
