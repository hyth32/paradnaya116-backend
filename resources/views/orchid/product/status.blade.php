@if($product->is_deleted)
    <div class="alert alert-danger">
        <p>Товар удалён</p>
    </div>
@elseif($product->is_archived)
    <div class="alert alert-warning">
        <p>Товар в архиве</p>
    </div>
@endif
