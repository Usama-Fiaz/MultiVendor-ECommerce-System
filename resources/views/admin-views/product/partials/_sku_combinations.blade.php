@if(count($combinations[0]) > 0)
	<table class="table physical_product_show table-borderless">
		<thead class="thead-light thead-50 text-capitalize">
			<tr>
				<th class="text-center">
					<label for="" class="control-label">{{translate('SL')}}</label>
				</th>
				<th class="text-center">
					<label for="" class="control-label">{{translate('attribute_Variation')}}</label>
				</th>
				<th class="text-center">
					<label for="" class="control-label">{{translate('variation_Wise_Price')}} ({{\App\CPU\BackEndHelper::currency_symbol()}})</label>
				</th>
				<th class="text-center">
					<label for="" class="control-label">{{translate('SKU')}}</label>
				</th>
				<th class="text-center">
					<label for="" class="control-label">{{translate('Variation_Wise_Stock')}}</label>
				</th>
			</tr>
		</thead>
		<tbody>
@endif

@php
    $serial = 1;
@endphp

@foreach ($combinations as $key => $combination)
	@php
		$sku = '';
		foreach (explode(' ', $product_name) as $key => $value) {
			$sku .= substr($value, 0, 1);
		}

		$str = '';
		foreach ($combination as $key => $item){
			if($key > 0 ){
				$str .= '-'.str_replace(' ', '', $item);
				$sku .='-'.str_replace(' ', '', $item);
			}
			else{
				if($colors_active == 1){
					$color_name = \App\Model\Color::where('code', $item)->first()->name;
					$str .= $color_name;
					$sku .='-'.$color_name;
				}
				else{
					$str .= str_replace(' ', '', $item);
					$sku .='-'.str_replace(' ', '', $item);
				}
			}
		}
	@endphp

	@if(strlen($str) > 0)
			<tr>
                <td class="text-center">
                    {{ $serial++ }}
                </td>
				<td>
					<label for="" class="control-label">{{ $str }}</label>
				</td>
				<td>
					<input type="number" name="price_{{ $str }}" value="{{ $unit_price }}" min="0" step="0.01" class="form-control variation-price-input" required placeholder="{{ translate('ex') }}: 535">
				</td>
				<td>
					<input type="text" name="sku_{{ $str }}" value="{{ $sku }}" class="form-control" required placeholder="{{ translate('ex') }}: MCU47V593M">
				</td>
				<td>
					<input type="number" onkeyup="update_qty()" name="qty_{{ $str }}" value="1" min="1" max="1000000" step="1" class="form-control" required placeholder="{{ translate('ex') }}: 5">
				</td>
			</tr>
	@endif
@endforeach
	</tbody>
</table>

<script>
	update_qty();
	function update_qty()
	{
		var total_qty = 0;
		var qty_elements = $('input[name^="qty_"]');
		for(var i=0; i<qty_elements.length; i++)
		{
			total_qty += parseInt(qty_elements.eq(i).val());
		}
		if(qty_elements.length > 0)
		{

			$('input[name="current_stock"]').attr("readonly", true);
			$('input[name="current_stock"]').val(total_qty);
		}
		else{
			$('input[name="current_stock"]').attr("readonly", false);
		}
	}
	$('input[name^="qty_"]').on('keyup', function () {
		var total_qty = 0;
		var qty_elements = $('input[name^="qty_"]');
		for(var i=0; i<qty_elements.length; i++)
		{
			total_qty += parseInt(qty_elements.eq(i).val());
		}
		$('input[name="current_stock"]').val(total_qty);
	});

</script>
