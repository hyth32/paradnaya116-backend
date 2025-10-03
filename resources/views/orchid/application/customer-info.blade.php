<div class="customer-info">
    <div class="row">
        <div class="col-md-6">
            <strong>Имя:</strong> {{ $application->customer_name }}
        </div>
        <div class="col-md-6">
            <strong>Телефон:</strong> {{ $application->customer_phone }}
        </div>
    </div>
    @if($application->customer_email)
    <div class="row mt-2">
        <div class="col-md-12">
            <strong>Email:</strong> {{ $application->customer_email }}
        </div>
    </div>
    @endif
</div>