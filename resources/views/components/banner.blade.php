<div class="banner banner--{{ $type }}">
    <div class="center center:wide">
        <p>
            @if ($icon)
                @svg($icon, 'icon--lg mr-2')
            @endif
            <span>{{ $slot ?? '' }}</span>
        </p>
    </div>
</div>
