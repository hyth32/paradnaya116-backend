@if($rentalApplication->isNew())
    <div class="alert alert-info">
        <p>Заявка создана {{ $rentalApplication->created_at->format('d.m.Y H:i') }}</p>
    </div>
@elseif($rentalApplication->isActive())
    <div class="alert alert-warning">
        <p>Заявка принята в работу {{ $rentalApplication->approved_at->format('d.m.Y H:i') }}</p>
    </div>
@elseif($rentalApplication->isCanceled())
    <div class="alert alert-info">
        <p>Заявка отменена {{ $rentalApplication->canceled_at->format('d.m.Y H:i') }}</p>
    </div>
@elseif($rentalApplication->isCompleted())
    <div class="alert alert-success">
        <p>Заявка завершена {{ $rentalApplication->completed_at->format('d.m.Y H:i') }}</p>
    </div>
@endif
