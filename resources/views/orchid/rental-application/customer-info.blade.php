<div>
    <strong class="d-block mb-2">{{ $rentalApplication->customer_name }}</strong>

    <div class="d-flex align-items-center mb-2">
        <x-orchid-icon path="bs.telephone" class="me-3"/>
        <a href="tel:{{ $rentalApplication->customer_phone }}">
            {{ $rentalApplication->customer_phone ?? 'Не указан' }}
        </a>
    </div>

    <div class="d-flex align-items-center">
        <x-orchid-icon path="bs.envelope" class="me-3"/>
        <a href="mailto:{{ $rentalApplication->customer_email }}">
            {{ $rentalApplication->customer_email ?? 'Не указан' }}
        </a>
    </div>
</div>
