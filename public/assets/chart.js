import Chart from 'chart.js/auto';

const ctx = document.getElementById('chart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar', // change the chart type to bar
    data: {
        labels: ['Clients', 'Artistes'],
        datasets: [
            {
                label: 'Number of Clients and Artistes',
                data: [{ num_clients }, { num_artistes } ], // use the variables that were passed from the controller
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1,
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Number of Clients and Artistes',
            },
        },
        scales: {
            y: {
                beginAtZero: true,
            },
        },
    },
});
