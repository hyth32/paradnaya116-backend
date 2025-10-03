@if($service->isTrashed())
    <div class="alert alert-danger">
        <p>Услуга удалена</p>
    </div>
@elseif($service->isArchived())
    <div class="alert alert-warning">
        <p>Услуга в архиве</p>
    </div>
@endif