<div>
    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-icon btn-soft-primary btn-circle">
        <i class="las la-edit"></i>
    </a>


    <a href="{{ route('products.show', $product->id) }}" class="btn btn-icon btn-soft-info btn-circle">
        <i class="las la-eye"></i>
    </a>

    <button type="button" class="btn btn-soft-danger btn-icon btn-circle confirm-delete"
        data-href="{{ route('products.destroy', ['id' => $product->id]) }}" title="{{ translate('Delete') }}">
        <i class="las la-trash"></i>
    </button>
</div>
