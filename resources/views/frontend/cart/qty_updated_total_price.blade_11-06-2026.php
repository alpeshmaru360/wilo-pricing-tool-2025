<?php $totalPrice = 0.00; ?>

@if($controlPanelCartData->isNotEmpty())
@foreach($controlPanelCartData as $key=> $val)



<?php $totalPrice += round($val->price * $val->qty); ?>
@endforeach
@endif
@if($atmosCartData->isNotEmpty())
@foreach($atmosCartData as $key=> $val)



<?php $totalPrice += $val->price * $val->qty; ?>
@endforeach
@endif

@if($scpCartData->isNotEmpty())
@foreach($scpCartData as $key=> $val)



<?php $totalPrice += $val->price * $val->qty; ?>
@endforeach
@endif


@if($boosterCartData->isNotEmpty())
@foreach($boosterCartData as $key=> $val)


<?php $totalPrice += $val->price * $val->qty; ?>
@endforeach
@endif

@if($fireFightingCartData->isNotEmpty())
		@foreach($fireFightingCartData as $key=> $val)
		<?php $totalPrice += $val->price * $val->qty; ?>
		@endforeach
		@endif

{{App\Helpers\CurrencyHelper::withCurrency($totalPrice) }}

