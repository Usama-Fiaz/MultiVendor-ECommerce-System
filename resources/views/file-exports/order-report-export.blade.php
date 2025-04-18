<html>
    <table>
        <thead>
            <tr>
                <th>{{translate('order_Report_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('filter_Criteria') }} -</th>
                <th></th>
                <th>

                    {{translate('search_Bar_Content')}} - {{ $data['search'] ?? 'N/A'}}
                    <br>
                    {{translate('store')}} - {{ucwords($data['seller'] != 'all' && $data['seller'] !='inhouse' ? $data['seller']?->shop->name : translate($data['seller'] ?? 'all' ))}}
                    <br>
                    {{translate('date_type')}} - {{translate($data['date_type'])}}
                    <br>
                    @if($data['from'] && $data['to'])
                        {{translate('from')}} - {{date('d M, Y',strtotime($data['from']))}}
                    <br>
                        {{translate('to')}} - {{date('d M, Y',strtotime($data['to']))}}
                    <br>
                    @endif
                </th>
            </tr>
            <tr>
                <td> {{translate('SL')}}</td>
                <td> {{translate('order_ID')}}	</td>
                <td> {{translate('total_Amount')}}	</td>
                <td> {{translate('product_Discount')}}</td>
                <td> {{translate('coupon_Discount')}}</td>
                <td> {{translate('shipping_Charge')}}</td>
                <td> {{translate('VAT/TAX')}}</td>
                <td> {{translate('commission')}}</td>
                <td> {{translate('status')}}</td>
            </tr>
            <!-- loop  you data -->
            @foreach ($data['orders'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td> {{$item['id']}}	</td>
                    <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item->order_amount)) }}</td>
                    <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item->details_sum_discount)) }}</td>
                    <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item->discount_amount)) }}</td>
                    <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item->shipping_cost - ($item->extra_discount_type == 'free_shipping_over_order_amount' ? $item->extra_discount : 0))) }}</td>
                    <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item->details_sum_tax)) }}</td>
                    <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item->admin_commission)) }}</td>
                    <td>{{translate($item['order_status'])}}</td>
            @endforeach
            <!-- end -->
        </thead>
    </table>
</html>
