@props(['for'])
@error($for)
    @if (strlen($slot->toHtml()) != 0)
        <p {{ $attributes->merge(['class' => 'text-base text-red-600 opacity-50']) }}>
            {{ $slot }}
        </p>
    @else
        <p {{ $attributes->merge(['class' => 'text-base text-red-600 opacity-50 w-full overflow-hidden overflow-ellipsis whitespace-nowrap']) }}>
            {{ $message }}
        </p>
    @endif
@enderror
