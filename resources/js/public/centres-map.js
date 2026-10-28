import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

/**
 * @param {HTMLElement} root
 */
function parseMarkers(root) {
    const raw = root.getAttribute('data-markers');

    if (! raw) {
        return [];
    }

    try {
        const parsed = JSON.parse(raw);

        return Array.isArray(parsed) ? parsed : [];
    } catch {
        return [];
    }
}

/**
 * @param {HTMLElement} root
 */
export function mountCentresMap(root) {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches && root.dataset.forceMap !== 'true') {
        // Still show the map; reduced motion only avoids animated pan.
    }

    const markers = parseMarkers(root).filter(
        (marker) => Number.isFinite(marker.lat) && Number.isFinite(marker.lng),
    );

    if (markers.length === 0) {
        root.hidden = true;

        return;
    }

    root.hidden = false;
    root.querySelectorAll('[data-centres-map-fallback]').forEach((node) => node.remove());

    const mapNode = document.createElement('div');
    mapNode.className = 'h-full min-h-[18rem] w-full';
    root.appendChild(mapNode);

    const map = L.map(mapNode, {
        scrollWheelZoom: false,
        attributionControl: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 18,
    }).addTo(map);

    const bounds = L.latLngBounds([]);

    markers.forEach((marker) => {
        const pin = L.marker([marker.lat, marker.lng]).addTo(map);
        const label = [marker.name, marker.city].filter(Boolean).join(' · ');
        const content = marker.url
            ? `<a href="${marker.url}">${label}</a>`
            : label;

        pin.bindPopup(content);
        bounds.extend([marker.lat, marker.lng]);
    });

    if (markers.length === 1) {
        map.setView([markers[0].lat, markers[0].lng], 12);
    } else {
        map.fitBounds(bounds.pad(0.2));
    }

    requestAnimationFrame(() => map.invalidateSize());
}

export function bootCentresMaps(root = document) {
    root.querySelectorAll('[data-centres-map]').forEach((node) => {
        if (node instanceof HTMLElement) {
            mountCentresMap(node);
        }
    });
}
