@if (count($cart) > 0)
    @foreach ($cart as $key => $item)
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    @if (isset($item['thumbnail']))
                        <img src="{{ asset($item['thumbnail']) }}" class="size-50px mr-2" alt="">
                    @endif
                    <div>
                        <div class="font-weight-bold">{{ $item['name'] }}</div>
                        @if (isset($item['variant_name']))
                            <small class="text-muted">{{ $item['variant_name'] }}</small>
                        @endif
                    </div>
                </div>
            </td>
            <td>{{ number_format($item['price']) }} ৳</td>
            <td>
                <input type="number" class="form-control form-control-sm cart-qty" data-key="{{ $key }}"
                    value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] ?? 999 }}"
                    style="width: 60px;">
            </td>
            <td>{{ number_format($item['price'] * $item['quantity']) }} ৳</td>
            <td>
                <button class="btn btn-sm btn-danger remove-from-cart" data-key="{{ $key }}">
                    <i class="las la-trash"></i>
                </button>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center py-4">
            <i class="las la-shopping-cart fs-40 text-muted"></i>
            <p class="text-muted mb-0">Cart is empty</p>
        </td>
    </tr>
@endif
