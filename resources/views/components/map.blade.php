@props([
    'id' => 'map',
    'height' => '400px',
    // markers: array of ['lat' => ..., 'lng' => ..., 'label' => ...]
    'markers' => [],
])

<div id="{{ $id }}" style="height: {{ $height }};" class="w-full rounded border border-gray-300"></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapId = @json($id);
        const el = document.getElementById(mapId);
        if (!el) return;

        const map = L.map(mapId);

        @if(!empty($markers))
            const points = @json($markers);
            const latlngs = points
                .filter(p => p.lat !== null && p.lng !== null)
                .map(p => [p.lat, p.lng]);

            if (latlngs.length) {
                map.fitBounds(latlngs, { padding: [40, 40] });

                points.forEach(p => {
                    if (p.lat !== null && p.lng !== null) {
                        L.marker([p.lat, p.lng]).addTo(map).bindPopup(p.label || '');
                    }
                });
            } else {
                map.setView([20, 0], 2);
            }
        @else
            map.setView([20, 0], 2);
        @endif

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(map);
    });
</script>
@endpush