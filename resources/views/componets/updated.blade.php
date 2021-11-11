<p class="text-muted">
    {{ $slot ?? 'Added ' }} {{ $date->diffForHumans() }}
    @if (isset($name))
        by {{ $name }}
    @endif
</p>