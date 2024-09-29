let priceDisplay = document.querySelector('#priceDisplay');
let info = document.querySelector('#info');
let dailyRent = parseInt(info.getAttribute('data-daily-rent'));
let vehicleId = info.getAttribute('data-vehicle-id');
let startDate = document.querySelector('#reservation_startDate');
let endDate = document.querySelector('#reservation_endDate');

function updateTotalPrice() {
    let days = Math.ceil((new Date(endDate.value) - new Date(startDate.value)) / (1000 * 60 * 60 * 24));
    priceDisplay.textContent = dailyRent * days;
}

function getAvailabiliy() {
    let start = Date.parse(startDate.value) / 1000;
    let end = Date.parse(endDate.value) / 1000;
    let url = `/api/vehicle/${vehicleId}/availability%3Fstart=${start}%26end=${end}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if ('true' === data) {
                document.querySelector('#availability').textContent = 'Disponible';
            } else {
                document.querySelector('#availability').textContent = 'Non disponible';
            }
        });
}

updateTotalPrice();
getAvailabiliy();

startDate.addEventListener('change', updateTotalPrice);
startDate.addEventListener('change', getAvailabiliy);
endDate.addEventListener('change', updateTotalPrice);
endDate.addEventListener('change', getAvailabiliy);