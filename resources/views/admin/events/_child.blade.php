@php
    $paramName = [
        'speakers' => 'speaker',
        'schedules' => 'schedule',
        'key-benefits' => 'key_benefit',
        'venues' => 'venue',
        'hotels' => 'hotel',
        'galleries' => 'gallery',
        'sponsors' => 'sponsor',
        'faqs' => 'faq',
        'amenities' => 'amenity',
        'prices' => 'price',
    ][$module];

    $routeBase = 'admin.' . $module;
@endphp

<div class="crud-panel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">{{ $title }}</h5>
        <a href="{{ route($routeBase . '.create', ['event_id' => $event->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm {{ $title }}</a>
    </div>

    @if(count($items) > 0)
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    @foreach($columns as $key => $label)
                        <th>{{ $label }}</th>
                    @endforeach
                    <th style="width: 120px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        @foreach($columns as $key => $label)
                            <td>
                                @if(in_array($key, ['photo', 'logo']))
                                    @if($item->$key)
                                        <img src="{{ $item->$key->thumbnail }}" alt="" style="height: 40px;">
                                    @else
                                        -
                                    @endif
                                @elseif($key === 'icon' && method_exists($item, 'getFirstMediaUrl') && $item->getFirstMediaUrl('icon'))
                                    <img src="{{ $item->getFirstMediaUrl('icon', 'thumb') }}" alt="" style="height: 40px;">
                                @elseif($key === 'speaker_name')
                                    {{ $item->speaker->name ?? '-' }}
                                @elseif($key === 'photos_count')
                                    {{ $item->photos ? count($item->photos) : 0 }}
                                @elseif($key === 'amenities')
                                    {{ $item->amenities ? $item->amenities->pluck('name')->join(', ') : '-' }}
                                @elseif($key === 'price')
                                    {{ number_format($item->price, 0, ',', '.') }}
                                @elseif($key === 'answer' || $key === 'description' || $key === 'full_description' || $key === 'desc')
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->$key ?? ''), 80) }}
                                @else
                                    {{ $item->$key ?? '-' }}
                                @endif
                            </td>
                        @endforeach
                        <td>
                            <a href="{{ route($routeBase . '.edit', [$paramName => $item->id, 'event_id' => $event->id]) }}" class="btn btn-xs btn-info" title="Sửa"><i class="fa fa-edit"></i> Sửa</a>
                            <form method="POST" action="{{ route($routeBase . '.destroy', [$paramName => $item->id]) }}" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <button type="submit" class="btn btn-xs btn-danger" title="Xóa"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Chưa có {{ $title }} nào.</p>
    @endif
</div>