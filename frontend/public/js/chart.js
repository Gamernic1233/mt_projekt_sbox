let myChart = null; // Uloženie inštancie grafu

// Definície pre mesiace a dni v týždni
const mesiace = ["jan", "feb", "mar", "apr", "máj", "jún", "júl", "aug", "sep", "okt", "nov", "dec"];
const weekDays = ["Ned", "Pon", "Uto", "Str", "Štv", "Pia", "Sob"];

// Funkcia na získanie prvého dňa týždňa (pondelok)
function getFirstDayOfWeek(date) {
    const dayOfWeek = date.getDay(); // Získa deň v týždni (0 = nedeľa, 1 = pondelok, ...)
    const diff = date.getDate() - dayOfWeek + (dayOfWeek == 0 ? -6 : 1); // Nastaví pondelok ako prvý deň týždňa
    const firstDay = new Date(date.setDate(diff));
    firstDay.setHours(0, 0, 0, 0); // Nastaví čas na polnoc
    return firstDay;
}

function VytvorGraf(data, hodnota) {
    if (myChart) {
        myChart.destroy(); // Zničenie predchádzajúceho grafu
    }

    let dataset7 = [], dataset14 = [], dataset21 = [];
    let dni = [];

    // Získame aktuálny dátum
    const today = new Date();

    // Získame dátum začiatku aktuálneho týždňa (pondelok)
    const firstDayOfWeek = getFirstDayOfWeek(today);

    // Generujeme dni od pondelka do nedele (7 dní)
    for (let i = 0; i < 7; i++) {
        const date = new Date(firstDayOfWeek);
        date.setDate(firstDayOfWeek.getDate() + i); // Nastavíme dátum na pondelok až nedeľu aktuálneho týždňa

        const dayLabel = `${("0" + date.getDate()).slice(-2)} ${mesiace[date.getMonth()]} (${weekDays[date.getDay()]})`; // Formát: "17 feb (pon)"
        dni.push(dayLabel); // Pridáme deň s dátumom a skratkou dňa
    }

    // Spracovanie dát, aby sa zahrnuli len dáta pre aktuálny týždeň
    data.forEach(item => {
        const date = new Date(item.datum_cas);
        const day = date.getDay(); // 1 = pondelok, 2 = utorok, ...
        const hours = date.getHours();
        const minutes = date.getMinutes(); // Získame minúty z času
        const label = `${("0" + date.getDate()).slice(-2)} ${mesiace[date.getMonth()]} (${weekDays[date.getDay()]})`; // Formát "17 feb (pon)"

        // Skontrolujeme, či dátum patrí do aktuálneho týždňa
        const itemDay = date.getDate();
        const itemWeekStart = getFirstDayOfWeek(date); // Začiatok týždňa pre tento dátum
        const itemWeekEnd = new Date(itemWeekStart);
        itemWeekEnd.setDate(itemWeekStart.getDate() + 6); // Nedeľa aktuálneho týždňa

        // Zápis do datasetu iba ak dátum patrí do aktuálneho týždňa
        if (date >= itemWeekStart && date <= itemWeekEnd) {
            // Priradenie hodnoty medzi 7:00 a 7:14
            if (hours === 7 && minutes >= 0 && minutes < 15 && dni.includes(label)) {
                dataset7[dni.indexOf(label)] = item[hodnota];
            }
            // Priradenie hodnoty medzi 14:00 a 14:14
            if (hours === 14 && minutes >= 0 && minutes < 15 && dni.includes(label)) {
                dataset14[dni.indexOf(label)] = item[hodnota];
            }
            // Priradenie hodnoty medzi 21:00 a 21:14
            if (hours === 21 && minutes >= 0 && minutes < 15 && dni.includes(label)) {
                dataset21[dni.indexOf(label)] = item[hodnota];
            }
        }
    });

    // Ak v daný deň nie sú dáta, nastavíme ich ako prázdne (null)
    dni.forEach(day => {
        if (!dataset7[dni.indexOf(day)]) dataset7[dni.indexOf(day)] = null;
        if (!dataset14[dni.indexOf(day)]) dataset14[dni.indexOf(day)] = null;
        if (!dataset21[dni.indexOf(day)]) dataset21[dni.indexOf(day)] = null;
    });

    // Určíme nadpisy podľa hodnoty
    let labels = {
        "vlhkost_pody": {
            "7": "Vlhkosť pôdy ráno (07:00)",
            "14": "Vlhkosť pôdy poobede (14:00)",
            "21": "Vlhkosť pôdy večer (21:00)"
        },
        "tlak_vzduchu": {
            "7": "Tlak vzduchu ráno (07:00)",
            "14": "Tlak vzduchu poobede (14:00)",
            "21": "Tlak vzduchu večer (21:00)"
        },
        "teplota_vzduchu": {
            "7": "Teplota vzduchu ráno (07:00)",
            "14": "Teplota vzduchu poobede (14:00)",
            "21": "Teplota vzduchu večer (21:00)"
        },
        "vlhkost_vzduchu": {
            "7": "Vlhkosť vzduchu ráno (07:00)",
            "14": "Vlhkosť vzduchu poobede (14:00)",
            "21": "Vlhkosť vzduchu večer (21:00)"
        }
    };

    const datasetLabels = labels[hodnota];

    // Vytvorenie grafu
    const allValues = [...dataset7, ...dataset14, ...dataset21];
    const minVal = 0;  // Zobrazenie minimálnej hodnoty na ose Y
    const maxVal = 100; // Zobrazenie maximálnej hodnoty na ose Y
    const stepSize = 5;

    const ctx = document.getElementById("myChart").getContext("2d");
    myChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: dni,
            datasets: [
                { label: datasetLabels["7"], data: dataset7, borderColor: "blue", fill: false },
                { label: datasetLabels["14"], data: dataset14, borderColor: "red", fill: false },
                { label: datasetLabels["21"], data: dataset21, borderColor: "green", fill: false }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: false,
                    min: minVal,
                    max: maxVal,
                    ticks: {
                        stepSize: stepSize
                    }
                }
            }
        }
    });
}


function NacitajGraf(hodnota) {
    const username = localStorage.getItem("username");
    const device_name = localStorage.getItem("device_name");

    fetch("http://127.0.0.1:8000/getUserDevice?username=" + encodeURIComponent(username) + "&device_name=" + encodeURIComponent(device_name), {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        }
    })
    .then(response => response.json())
    .then(data => {
        const extractedData = data.map(element => ({
            nazov_zariadenia: element.nazov_zariadenia,
            datum_cas: element.datum_cas,
            vlhkost_vzduchu: element.vlhkost_vzduchu,
            teplota_vzduchu: element.teplota_vzduchu,
            tlak_vzduchu: element.tlak_vzduchu,
            vlhkost_pody: element.vlhkost_pody
        }));
        console.log(extractedData)
        VytvorGraf(extractedData, hodnota); // Spracovať a zobraziť dáta v grafe
    })
    .catch(error => {
        console.error("Error fetching data:", error);
    });
}

document.getElementById("graf1Btn").addEventListener("click", function() {
    NacitajGraf("vlhkost_pody");
});

document.getElementById("graf2Btn").addEventListener("click", function() {
    NacitajGraf("tlak_vzduchu");
});

document.getElementById("graf3Btn").addEventListener("click", function() {
    NacitajGraf("teplota_vzduchu");
});

document.getElementById("graf4Btn").addEventListener("click", function() {
    NacitajGraf("vlhkost_vzduchu");
});
