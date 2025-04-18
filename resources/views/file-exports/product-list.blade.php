<html>
    <table>
        <thead>
            <tr>
                <th style="font-size: 18px">{{translate($data['type'].'_product_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('filter_Criteria') }} -</th>
                <th></th>
                <th>
                    {{translate('category')}} - {{$data['category'] != 'all' ? $data['category']['defaultName'] : $data['category']  }}
                    <br>
                    {{translate('sub_Category')}} - {{$data['sub_category'] != 'all' ? $data['sub_category']['defaultName'] : $data['sub_category']  }}
                    <br>
                    {{translate('sub_Sub_Category')}} - {{$data['sub_sub_category'] != 'all' ? $data['sub_sub_category']['defaultName'] : $data['sub_sub_category']  }}
                    <br>
                    {{translate('brand')}} - {{$data['brand'] != 'all' ? $data['brand']['defaultName'] : $data['brand']  }}

                    @if($data['type']=='seller')
                        <br>
                        {{translate('store')}} - {{$data['seller']?->shop->name ?? translate('all')}}
                        <br>
                        {{translate('status')}} - {{translate($data['status']==0 ? 'pending' : ($data['status'] == 1 ? 'approved' : 'denied') )}}
                    @endif
                    <br>
                    {{translate('search_Bar_Content')}} - {{!empty($data['search']) ?  ucwords($data['search']) : 'N/A'}}

                </th>
            </tr>
            <tr>
                <td> {{translate('SL')}}</td>
                <td> {{translate('product_Image')}}	</td>
                <td> {{translate('image_URL')}}	</td>
                <td> {{translate('product_Name')}}	</td>
                <td> {{translate('product_SKU')}}</td>
                <td> {{translate('description')}}</td>

                <td>
                    @if($data['type']=='seller')
                        {{translate('store_Name')}}
                    @endif
                </td>
                <td> {{translate('category_Name')}}</td>
                <td> {{translate('sub_Category_Name')}}</td>
                <td> {{translate('sub_Sub_Category_Name')}}</td>
                <td> {{translate('brand')}}</td>
                <td> {{translate('product_Type')}}</td>
                <td> {{translate('price')}}</td>
                <td> {{translate('tax')}}</td>
                <td> {{translate('discount')}}</td>
                <td> {{translate('discount_Type')}}</td>
                <td> {{translate('rating')}}</td>
                <td> {{translate('product_Tags')}}</td>
                <td> {{translate('status')}}</td>
            </tr>
            <!-- loop  you data -->
            @foreach ($data['products'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td style="height: 200px"></td>
                    <td>{{asset('storage/app/public/product/thumbnail/'.$item->thumbnail)}}</td>
                    <td> {{$item->name}}</td>
                    <td>{{$item->code}}</td>
                    <td>{!! $item->details !!}</td>
                    <td>
                        @if($data['type']=='seller')
                        {{ucwords($item?->seller?->shop->name ?? translate('not_found'))}}
                        @endif
                    </td>
                    <td>{{ $item?->category->name ?? 'N/A'}}</td>
                    <td>{{ $item?->sub_category->name ?? 'N/A'}}</td>
                    <td>{{ $item?->sub_sub_category->name ?? 'N/A'}}</td>
                    <td>{{ $item?->brand->name ?? 'N/A'}}</td>
                    <td>{{ $item?->product_type}}</td>
                    <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item['unit_price']))}}</td>
                    <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item['tax']))}}</td>
                    <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item['discount']))}}</td>
                    <td>{{$item->discount_type}}</td>
                    <td>{{$item?->rating && count($item->rating) > 0 ?  number_format($item->rating[0]->average,2) : 'N/A'}}</td>
                    <td>
                        @if($item->tags)
                            @foreach ($item->tags as $tag)
                                {{$tag->tag}},
                            @endforeach
                        @endif
                    </td>
                    <td> {{translate($item->status == 1 ? 'active' : 'inactive')}}</td>
                </tr>
            @endforeach
            <!-- end -->
        </thead>
    </table>
</html>
