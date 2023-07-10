@props(['data'])
@if (count($data) === 1)
    <p>{{ $data[0] }}</p>
@else
    <ul role="list">
        @foreach ($data as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
@endif
