<?php

namespace App\Http\Controllers\api\v4;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class CartController extends Controller
{

    public function cart(Request $request)
    {
        $user = Helpers::get_customer($request);
        $cart_query = Cart::with('product');
        if($user == 'offline'){
            $cart = $cart_query->where(['customer_id' => $request->guest_id, 'is_guest'=>1])->get()->toArray();
        }else{
            $cart = $cart_query->where(['customer_id' => $user->id, 'is_guest'=>'0'])->get()->toArray();
        }

        $items = [];
        if($cart) {
            foreach($cart as $key => $value){
                if(!isset($value['product'])){
                    $cart_data = Cart::find($value['id']);
                    $cart_data->delete();

                    unset($cart[$key]);
                }
            }

            foreach($cart as $data) {
                $data['choices'] = json_decode($data['choices']);
                $data['variations'] = json_decode($data['variations']);

                $data['minimum_order_amount_info'] = OrderManager::minimum_order_amount_verify($request, $data['cart_group_id']);
                $cart_group = Cart::where(['product_type'=>'physical'])->where('cart_group_id', $data['cart_group_id'])->get()->groupBy('cart_group_id');
                if(isset($cart_group[$data['cart_group_id']])){
                    $data['free_delivery_order_amount'] = OrderManager::free_delivery_order_amount($data['cart_group_id']);
                }else{
                    $data['free_delivery_order_amount'] = [
                        'status'=> 0,
                        'amount'=> 0,
                        'percentage'=> 0,
                        'shipping_cost_saved' => 0,
                    ];
                }

                $colors = json_decode($data['product']['colors']);
                $formatted_colors = [];
                if(!empty($colors)){
                    $query_data = Color::whereIn('code', $colors)->pluck('name', 'code')->toArray();
                    foreach ($query_data as $key => $color) {
                        $formatted_colors[] = array(
                            'name' => $color,
                            'code' => $key,
                        );
                    }
                }
                $variants = is_array($data['product']['variation']) ? $data['product']['variation'] : json_decode($data['product']['variation']);
                $data['product']['colors_formatted'] = $formatted_colors;
                $data['product']['images'] = is_array($data['product']['images']) ? $data['product']['images'] : json_decode($data['product']['images']);
                $data['product']['color_image'] = is_array($data['product']['color_image']) ? $data['product']['color_image'] : json_decode($data['product']['color_image']);
                $data['product']['choice_options'] = is_array($data['product']['choice_options']) ? $data['product']['choice_options'] : json_decode($data['product']['choice_options']);

                $data['variation'] = $variants;
                $data['colors_formatted'] =  $data['product']['colors_formatted'];
                $data['images'] =  $data['product']['images'];
                $data['color_image'] =  $data['product']['color_image'];
                $data['choice_options'] =  $data['product']['choice_options'];
                $data['current_stock'] =  $data['product']['current_stock'];
                $data['tax'] =  $data['product']['tax'];
                $data['tax_type'] =  $data['product']['tax_type'];
                $data['tax_model'] =  $data['product']['tax_model'];
                $data['discount_type'] =  'flat';
                $data['unit_price'] =  $data['product']['unit_price'];

                if (isset($data['product']['variation']) && !empty($variants)) {
                    foreach ($variants as $var) {
                        if ($data['variant'] == $var->type) {
                            $selected_data = [
                                'id' => $data['product_id'],
                                'color' => $data['color'],
                                'quantity' => $data['quantity'],
                                'variant' => $data['variant'],
                            ];

                            $data['product']['selected_data'] = !empty($data['choices']) ? array_merge($selected_data, get_object_vars($data['choices'])) : $selected_data;
                        }
                    }
                    $data['product']['variation'] = $variants;
                }else{
                    $data['product']['selected_data'] = [
                        'id' => $data['product_id'],
                        'quantity' => $data['quantity'],
                    ];
                    $data['product']['variation'] = [];
                }

                unset($data['product']['colors']);
                $items[] = $data;
            }
        }

        return response()->json($items, 200);
    }

    public function add_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'quantity' => 'required',
        ], [
            'id.required' => translate('product_ID_is_required')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $cart = CartManager::add_to_cart($request);
        return response()->json($cart, 200);
    }

    public function update_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'quantity' => 'required',
        ], [
            'key.required' => translate('cart_key_or_ID_is_required')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $response = CartManager::update_cart_qty($request);
        return response()->json($response);
    }

    public function remove_from_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required'
        ], [
            'key.required' => translate('cart_key_or_ID_is_required')
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $user = Helpers::get_customer($request);
        Cart::where([
            'id' => $request->key,
            'customer_id' => ($user == 'offline' ? $request->guest_id : $user->id),
            'is_guest' => ($user == 'offline' ? 1 : '0'),
        ])->delete();
        return response()->json(translate('successfully_removed'));
    }

    public function remove_all_from_cart(Request $request)
    {
        $user = Helpers::get_customer($request);
        Cart::where([
            'customer_id'=> ($user == 'offline' ? $request->guest_id : $user->id),
            'is_guest' => ($user == 'offline' ? 1 : '0'),
        ])->delete();
        return response()->json(translate('successfully_removed'));
    }
}
