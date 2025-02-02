let currentChart = null;

function getStartOfWeek(date) {
    const startOfWeek = new Date(date);
    const currentDay = date.getDay(); // 0 (Sunday) to 6 (Saturday)
    const offset = (currentDay - 1 + 7) % 7; // Adjust so Monday (1) is the start
    startOfWeek.setDate(date.getDate() - offset);
    startOfWeek.setHours(0, 0, 0, 0); // Resetovať čas
    return startOfWeek;
}

// Slovník skrátených mesiacov
const monthNames = ['jan', 'feb', 'mar', 'apr', 'máj', 'jún', 'júl', 'aug', 'sep', 'okt', 'nov', 'dec'];

function fetchDataAndCreateGraph(parameter) {
    const username = localStorage.getItem("username");
    const device_name = localStorage.getItem("device_name");

    fetch(`http://127.0.0.1:8000/getuserdevice?username=${encodeURIComponent(username)}&device_name=${encodeURIComponent(device_name)}`, {
        method: "GET",
        headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => {
        const extractedData = data.map(element => ({
            nazov_zariadenia: element.nazov_zariadenia,
            datum_cas: element.datum_cas,
            [parameter]: element[parameter] // Dynamicky priradí parameter (napr. "vlhkost_vzduchu")
        }));
        
        console.log(extractedData);
        createGraph(extractedData, parameter); // Vykresli graf podľa parametra
    })
    .catch(error => console.error("Error fetching data:", error));
}

function createGraph(extractedData, parameter) {
    // Získať dnešný dátum a začiatok týždňa
    const today = new Date();
    const startOfWeek = getStartOfWeek(today);

    // Pripraviť popisky pre graf
    const labels = Array.from({ length: 7 }, (_, i) => {
        const day = new Date(startOfWeek);
        day.setDate(day.getDate() + i);
        const weekday = day.toLocaleDateString('sk-SK', { weekday: 'short' });
        const dayOfMonth = String(day.getDate()).padStart(2, '0');
        const month = monthNames[day.getMonth()];
        return `${dayOfMonth} ${weekday} ${month}`;
    });

    // Pripraviť dátové body pre tri časové intervaly
    const dataPointsMorning = Array(7).fill(null);
    const dataPointsAfternoon = Array(7).fill(null);
    const dataPointsEvening = Array(7).fill(null);

    // Definovať časové intervaly
    const intervals = [
        { start: 7 * 60, end: 7 * 60 + 30, dataPoints: dataPointsMorning },
        { start: 14 * 60, end: 14 * 60 + 30, dataPoints: dataPointsAfternoon },
        { start: 21 * 60, end: 21 * 60 + 30, dataPoints: dataPointsEvening }
    ];

    // Spracovať dáta a priradiť ich do aktuálneho týždňa
    extractedData.forEach(element => {
        const dataDate = new Date(element.datum_cas);
        const timeInMinutes = dataDate.getHours() * 60 + dataDate.getMinutes();
        const dayIndex = Math.floor((dataDate - startOfWeek) / (24 * 60 * 60 * 1000));

        intervals.forEach(interval => {
            if (
                dataDate >= startOfWeek &&
                dataDate < new Date(startOfWeek.getTime() + 7 * 24 * 60 * 60 * 1000) &&
                dataDate.getFullYear() === today.getFullYear() &&
                timeInMinutes >= interval.start && timeInMinutes <= interval.end
            ) {
                interval.dataPoints[dayIndex] = parseFloat(element[parameter]);
            }
        });
    });

    // Určiť minimálnu a maximálnu hodnotu pre os Y
    const allValues = [...dataPointsMorning, ...dataPointsAfternoon, ...dataPointsEvening];
    const minValue = Math.min(...allValues.filter(v => v !== null));
    const maxValue = Math.max(...allValues.filter(v => v !== null));

    // Vykresliť graf
    const ctx = document.getElementById('myChart').getContext('2d');
    if (currentChart) currentChart.destroy();

    currentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: `Vlhkost ráno (07:00)`, data: dataPointsMorning, borderColor: 'rgba(75, 192, 192, 1)', backgroundColor: 'rgba(75, 192, 192, 0.2)', fill: false, tension: 0.4 },
                { label: `Vlhkost popoludní (14:00)`, data: dataPointsAfternoon, borderColor: 'rgba(255, 159, 64, 1)', backgroundColor: 'rgba(255, 159, 64, 0.2)', fill: false, tension: 0.4 },
                { label: `Vlhkost večer (21:00)`, data: dataPointsEvening, borderColor: 'rgba(153, 102, 255, 1)', backgroundColor: 'rgba(153, 102, 255, 0.2)', fill: false, tension: 0.4 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' } },
            scales: {
                x: { title: { display: true, text: 'Dni v týždni' } },
                y: {
                    title: { display: true, text: 'Vlhkost (%)' },
                    beginAtZero: true,
                    min: minValue - 15,
                    max: maxValue + 15,
                    ticks: { stepSize: 5 }
                }
            }
        }
    });
}


// Pre každé tlačidlo priradíme event listener bez okamžitého vykonania funkcie
document.getElementById("graf1Btn").addEventListener("click", function() {
    fetchDataAndCreateGraph('vlhkost_pody');
});

// Pre tlak vzduchu
document.getElementById("graf2Btn").addEventListener("click", function() {
    fetchDataAndCreateGraph('tlak_vzduchu');
});

// Pre teplotu vzduchu
document.getElementById("graf3Btn").addEventListener("click", function() {
    fetchDataAndCreateGraph('teplota_vzduchu');
});

// Pre vlhkosť vzduchu
document.getElementById("graf4Btn").addEventListener("click", function() {
    fetchDataAndCreateGraph('vlhkost_vzduchu');
});

fetchDataAndCreateGraph('vlhkost_pody');