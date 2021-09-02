<div class="auth-card flow">
    <div class="auth-card__logo">
        {{ $logo }}
    </div>

    <div class="auth-card__title">
        <h1 class="align-center">{{ $title }}</h1>
    </div>

    <div class="auth-card__form flow">
        {{ $slot }}
    </div>
</div>
