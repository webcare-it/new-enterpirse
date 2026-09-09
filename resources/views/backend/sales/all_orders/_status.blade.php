<option value="pending" {{ $order->delivery_status == 'pending' ? 'selected' : '' }}>
    {{ translate('Pending') }}</option>
<option value="confirmed" {{ $order->delivery_status == 'confirmed' ? 'selected' : '' }}>
    {{ translate('Confirmed') }}</option>

<option value="picked_up" {{ $order->delivery_status == 'picked_up' ? 'selected' : '' }}>
    {{ translate('Picked Up') }}</option>

<option value="on_the_way" {{ $order->delivery_status == 'on_the_way' ? 'selected' : '' }}>
    {{ translate('On The Way') }}</option>

<option value="delivered" {{ $order->delivery_status == 'delivered' ? 'selected' : '' }}>
    {{ translate('Delivered') }}</option>

<option value="transfer" {{ $order->delivery_status == 'transfer' ? 'selected' : '' }}>
    {{ translate('Transfer') }}</option>

<option value="cancelled" {{ $order->delivery_status == 'cancelled' ? 'selected' : '' }}>
    {{ translate('Cancelled') }}</option>
