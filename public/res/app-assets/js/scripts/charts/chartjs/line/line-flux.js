/*=========================================================================================
    File Name: line.js
    Description: Chartjs simple line chart
    ----------------------------------------------------------------------------------------
    Item Name: Modern Admin - Clean Bootstrap 4 Dashboard HTML Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// Line chart
// ------------------------------
function loadGraph(div,labels,matrix,month,year){
    var ctx = div;
    // Chart Options
    var chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            position: 'bottom',
        },
        hover: {
            mode: 'label'
        },
        scales: {
            xAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                    labelString: getMonthName(parseInt(month) - 1) + " "+year
                }
            }],
            yAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Nombre'
                },
                min: 0,
                ticks: {
                // forces step size to be 50 units
                stepSize: 50
        }
            }]
        },
        title: {
            display: true,
            text: 'Statistique migratoire du mois de '+getMonthName(parseInt(month) - 1) + " "+year
        }

    };

    // Chart Data
    var chartData = {
        labels: labels,
        datasets: [{
            label: "Entrée",
            data: matrix[0],
            fill: false,
            borderDash: [5, 5],
            borderColor: "#9C27B0",
            pointBorderColor: "#9C27B0",
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 4,
         },
           {
            label: "Sorties",
            data: matrix[1],
            fill: false,
            borderDash: [5, 5],
            borderColor: "#00A5A8",
            pointBorderColor: "#00A5A8",
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 4,
        }
        //, {
        //     label: "My Third dataset - No bezier",
        //     data: matrix[2],
        //     lineTension: 0,
        //     fill: false,
        //     borderColor: "#FF7D4D",
        //     pointBorderColor: "#FF7D4D",
        //     pointBackgroundColor: "#FFF",
        //     pointBorderWidth: 2,
        //     pointHoverBorderWidth: 2,
        //     pointRadius: 4,
        // }
    ]
    };

    var config = {
        type: 'line',
        options : chartOptions,
        data : chartData
    };

    console.log(matrix);
    // Create the chart
    var lineChart = new Chart(ctx, config);
}

function loadGraphYear(div,matrix,year){
    var ctx = div;
    // Chart Options
    var chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            position: 'bottom',
        },
        hover: {
            mode: 'label'
        },
        scales: {
            xAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                    labelString: "Année "+year
                }
            }],
            yAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Nombre'
                },
                min: 0,
                ticks: {
                // forces step size to be 50 units
                stepSize: 50
        }
            }]
        },
        title: {
            display: true,
            text: 'Statistique migratoire de l\'année '+year
        }

    };

    // Chart Data
    var chartData = {
        labels: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin","Juillet", "Août", "Septembre", "Octobre", "Novembre", "Decembre"],
        datasets: [{
            label: "Entrées",
            data: matrix[0],
            fill: false,
            borderDash: [5, 5],
            borderColor: "#9C27B0",
            pointBorderColor: "#9C27B0",
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 4,
         },
           {
            label: "Sorties",
            data: matrix[1],
            fill: false,
            borderDash: [5, 5],
            borderColor: "#00A5A8",
            pointBorderColor: "#00A5A8",
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 4,
        }
        //, {
        //     label: "My Third dataset - No bezier",
        //     data: matrix[2],
        //     lineTension: 0,
        //     fill: false,
        //     borderColor: "#FF7D4D",
        //     pointBorderColor: "#FF7D4D",
        //     pointBackgroundColor: "#FFF",
        //     pointBorderWidth: 2,
        //     pointHoverBorderWidth: 2,
        //     pointRadius: 4,
        // }
    ]
    };

    var config = {
        type: 'line',
        options : chartOptions,
        data : chartData
    };

    console.log(matrix);
    // Create the chart
    var lineChart = new Chart(ctx, config);
}

function getMonthName(monthNumber) {
    const months = [
        "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
        "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Decembre"
    ];

    return months[monthNumber];
}

