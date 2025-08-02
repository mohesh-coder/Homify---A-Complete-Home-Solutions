let globalMap;
let globalMarkers = [];
let globalInfoWindow;

function initMap() {
    globalMap = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 0, lng: 0 },
        zoom: 2
    });
}

function filterProperties() {
    const location = document.getElementById('location').value;
    const maxPrice = document.getElementById('max_price').value;
    const pageType = window.location.pathname.includes('rent.html') ? 'rent' : 'sale';

    fetch(`php/fetch_properties.php?page_type=${pageType}&location=${encodeURIComponent(location)}&max_price=${maxPrice}`)
        .then(response => response.json())
        .then(properties => {
            displayProperties(properties, pageType);
        })
        .catch(error => console.error('Error:', error));
}

function displayProperties(properties, pageType) {
    const tableBody = document.getElementById('propertiesTableBody');
    tableBody.innerHTML = '';

    properties.forEach(property => {
        const row = tableBody.insertRow();
        
        row.insertCell(0).textContent = property.property_type;
        row.insertCell(1).textContent = property.location;
        row.insertCell(2).textContent = `₹${parseFloat(property.price).toLocaleString()}${pageType === 'rent' ? '/month' : ''}`;
        row.insertCell(3).textContent = property.description;
        
        const actionsCell = row.insertCell(4);
        const buttonGroup = document.createElement('div');
        buttonGroup.className = 'button-group';

        const viewOnMapButton = document.createElement('button');
        viewOnMapButton.textContent = 'View on Map';
        viewOnMapButton.onclick = () => showPropertyMap(property.latitude, property.longitude, property.location, property.description);

        const getQuoteButton = document.createElement('button');
        getQuoteButton.textContent = 'Get Quote';
        getQuoteButton.className = 'quote-btn';
        getQuoteButton.onclick = () => getQuote(property.id);

        buttonGroup.appendChild(viewOnMapButton);
        buttonGroup.appendChild(getQuoteButton);
        actionsCell.appendChild(buttonGroup);
    });
}

function showPropertyMap(latitude, longitude, location, description) {
    const lat = parseFloat(latitude);
    const lng = parseFloat(longitude);

    if (isNaN(lat) || isNaN(lng)) {
        console.error('Invalid latitude or longitude');
        return;
    }

    const mapDiv = document.getElementById('map');
    mapDiv.style.display = 'block';

    globalMap.setCenter({lat: lat, lng: lng});
    globalMap.setZoom(15);

    globalMarkers.forEach(marker => marker.setMap(null));
    globalMarkers = [];

    const marker = new google.maps.Marker({
        position: {lat: lat, lng: lng},
        map: globalMap,
        title: location
    });
    globalMarkers.push(marker);

    if (globalInfoWindow) {
        globalInfoWindow.close();
    }
    globalInfoWindow = new google.maps.InfoWindow({
        content: `
            <div>
                <h3 style="color: #000000; margin-top: 0;">${location}</h3>
                <p style="color: #000000;margin-bottom: 0;">${description}</p>
            </div>
        `,
        maxWidth: 300
    });
    globalInfoWindow.open(globalMap, marker);

    mapDiv.scrollIntoView({behavior: 'smooth'});
}

document.addEventListener('DOMContentLoaded', () => {
    initMap();
    filterProperties();
});