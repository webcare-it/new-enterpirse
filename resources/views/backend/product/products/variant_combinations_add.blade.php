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
                </tr>
            </thead>
            <tbody>
                @foreach ($combinations as $key => $combination)
                    <tr class="variant">
                        <td style="width: 20%">
                            {{ $combination['attribute_value_text'] }}
                            <input type="hidden" name="variant_attributes[{{ $key }}][attributes]"
                                value="{{ $combination['attributes_json'] ?? '{}' }}">
                        </td>
                        <td style="width: 10%">
                            <input type="number" name="variant_attributes[{{ $key }}][price]"
                                value="{{ $combination['price'] }}" step="0.01" class="form-control" required>
                        </td>
                        <td style="width: 10%">
                            <input type="number" name="variant_attributes[{{ $key }}][wholesale_price]"
                                readonly value="{{ $combination['wholesale_price'] }}" step="0.01"
                                class="form-control" required>
                        </td>
                        <td style="width: 10%">
                            <input type="text" name="variant_attributes[{{ $key }}][sku]"
                                value="{{ $combination['sku'] }}" class="form-control" readonly>
                        </td>
                        <td style="width: 10%">
                            <input type="number" name="variant_attributes[{{ $key }}][quantity]"
                                value="120" min="0" class="form-control" required>
                        </td>
                        <td style="width: 30%">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}
                                    </div>
                                </div>

                                <div class="form-control file-amount">
                                    {{ translate('Choose File') }}
                                </div>

                                <input type="hidden" name="variant_attributes[{{ $key }}][image]"
                                    class="selected-files" value="">
                            </div>

                            <div class="file-preview box sm"></div>
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
