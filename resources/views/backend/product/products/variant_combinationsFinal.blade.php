<div id="sku_combination">
    @if (isset($combinations) && count($combinations) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ translate('Variant') }}</th>
                    <th>{{ translate('Variant Price') }}</th>
                    <th>{{ translate('Wholesale Price') }}</th>
                    <th>{{ translate('SKU') }}</th>
                    <th>{{ translate('Quantity') }}</th>
                    <th>{{ translate('Image') }}</th>
                    <th>{{ translate('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($combinations as $key => $combination)
                    <tr class="variant">
                        <td>
                            {{--
                                ✅ FIX: Convert attribute_value (stdClass) to a display string.
                                Example: {"Color":"Black","Age":"1/2 Age"} → "Color: Black, Age: 1/2 Age"
                            --}}
                            @php
                                $attrValue = $combination['attribute_value'];
                                $attrs = is_object($attrValue) ? (array) $attrValue : $attrValue;
                                $display = '';
                                if (is_array($attrs) && !empty($attrs)) {
                                    $parts = [];
                                    foreach ($attrs as $k => $v) {
                                        $parts[] = $k . ': ' . $v;
                                    }
                                    $display = implode(', ', $parts);
                                }
                            @endphp
                            {{ $display }}
                        </td>
                        <td>
                            <input type="number" name="variant_attributes[{{ $key }}][price]"
                                value="{{ $combination['price'] }}" step="0.01" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" readonly title="{{ translate('Price not changeable') }}"
                                name="variant_attributes[{{ $key }}][wholesale_price]"
                                value="{{ $combination['wholesale_price'] }}" step="0.01" class="form-control"
                                required>
                        </td>
                        <td>
                            <input type="text" readonly title="{{ translate('SKU not changeable') }}"
                                name="variant_attributes[{{ $key }}][sku]" value="{{ $combination['sku'] }}"
                                class="form-control" required>
                        </td>
                        <td>
                            <input type="number" name="variant_attributes[{{ $key }}][quantity]"
                                value="120" min="0" class="form-control" required>
                        </td>
                        <td>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}
                                    </div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="variant_attributes[{{ $key }}][image]"
                                    class="selected-files" value="{{ $combination['image'] ?? '' }}">
                            </div>
                            <div class="file-preview box sm">
                                @if (isset($combination['image']) && $combination['image'])
                                    <img src="{{ uploaded_asset($combination['image']) }}" class="size-60px">
                                @endif
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_variant(this)">
                                <i class="las la-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            {{ translate('No variants added. Please select attributes and save product to generate variants.') }}
        </div>
    @endif
</div>
