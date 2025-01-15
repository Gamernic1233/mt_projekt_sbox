const canvas = document.getElementById('myChart').getContext('2d');
const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

function vlhkostPody() {
    const data = {
        labels: labels,
        datasets: [
            {
                label: '7 AM',
                data: [10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65], // Example data
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: false,
                tension: 0.4,
            },
            {
                label: '2 PM',
                data: [20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75], // Example data
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: false,
                tension: 0.4,
            },
            {
                label: '9 PM',
                data: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60], // Example data
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: false,
                tension: 0.4,
            }
        ]
    };
    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Čas'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Mesiace'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Namerané hodnoty'
                    },
                    beginAtZero: true
                }
            }
        }
    };

    // Render the chart
    new Chart(canvas, config);
}

// vlhkostPody
document.getElementById("graf1Btn").addEventListener("click", vlhkostPody)
// tlakVzduchu
document.getElementById("graf1Btn").addEventListener("click", vlhkostPody)
// teplotaVzduchu
document.getElementById("graf1Btn").addEventListener("click", vlhkostPody)
// vlhkostVzduchu
document.getElementById("graf1Btn").addEventListener("click", vlhkostPody)