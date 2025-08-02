let globalMap;
let globalInfoWindow;

function initMap() {
    globalMap = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 0, lng: 0 },
        zoom: 2
    });
    filterProperties();
}

function filterProperties() {
    const location = document.getElementById('location').value;
    const maxPrice = document.getElementById('max_price').value;
    const pageType = window.location.pathname.includes('buy.html') ? 'sale' : 'rent';

    const url = `php/fetch_properties.php?location=${encodeURIComponent(location)}&max_price=${encodeURIComponent(maxPrice)}&page_type=${encodeURIComponent(pageType)}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const propertiesContainer = document.querySelector('.properties');
            propertiesContainer.innerHTML = '';

            if (data.length === 0) {
                propertiesContainer.innerHTML = '<p>No properties found.</p>';
                return;
            }

            const bounds = new google.maps.LatLngBounds();

            data.forEach(property => {
                const lat = parseFloat(property.latitude);
                const lng = parseFloat(property.longitude);
                
                if (isNaN(lat) || isNaN(lng)) {
                    console.error('Invalid latitude or longitude:', property);
                    return;
                }

                const marker = new google.maps.Marker({
                    position: { lat, lng },
                    map: globalMap,
                    title: property.location
                });

                bounds.extend(marker.getPosition());

                const infoWindowContent = `
                    <div>
                        <h3>${property.property_type}</h3>
                        <p>Location: ${property.location}</p>
                        <p>Price: ₹${property.price}</p>
                        <p>Email: ${property.email_id}</p>
                        <p>Description: ${property.property_description}</p>
                    </div>
                `;

                marker.addListener('click', () => {
                    if (globalInfoWindow) {
                        globalInfoWindow.close();
                    }
                    globalInfoWindow = new google.maps.InfoWindow({
                        content: infoWindowContent
                    });
                    globalInfoWindow.open(globalMap, marker);
                });

                // Add property to the list
                const propertyElement = document.createElement('div');
                propertyElement.className = 'property';
                propertyElement.innerHTML = `
                    <h3>${property.property_type}</h3>
                    <p>Location: ${property.location}</p>
                    <p>Price: ₹${property.price}</p>
                    <button onclick="showPropertyMap(${lat}, ${lng}, '${property.location}')">View on Map</button>
                `;
                propertiesContainer.appendChild(propertyElement);
            });

            globalMap.fitBounds(bounds);
        })
        .catch(error => {
            console.error('Error fetching or parsing data:', error);
            const propertiesContainer = document.querySelector('.properties');
            propertiesContainer.innerHTML = '<p>Error fetching properties. Please try again later.</p>';
        });
}

function showPropertyMap(lat, lng, location) {
    const propertyLocation = new google.maps.LatLng(lat, lng);
    globalMap.setCenter(propertyLocation);
    globalMap.setZoom(15);

    if (globalInfoWindow) {
        globalInfoWindow.close();
    }

    globalInfoWindow = new google.maps.InfoWindow({
        content: `<h3>${location}</h3>`
    });

    const marker = new google.maps.Marker({
        position: propertyLocation,
        map: globalMap,
        title: location
    });

    globalInfoWindow.open(globalMap, marker);
}

document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.querySelector('button');
    if (filterButton) {
        filterButton.addEventListener('click', filterProperties);
    }
});