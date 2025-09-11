@if($product->trashed())
    <div class="alert alert-danger">
        <p>Товар удалён</p>
    </div>
@elseif($product->isArchived())
    <div class="alert alert-warning">
        <p>Товар в архиве</p>
    </div>
@endif
