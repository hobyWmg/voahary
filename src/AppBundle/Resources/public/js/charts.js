
function newArrayDates(format) {
    var whichDisplayDate = $('#chart_formatTime').val();
    var labelNumber      = 0;
    var arrayDates       = {};

    if ('days' === whichDisplayDate) {
        while ( labelNumber < 12 ) {
            arrayDates[labelNumber] = moment().subtract((11 - labelNumber), 'days').format(format);
            labelNumber++;
        }
    } else {
        while ( labelNumber < 12 ) {
                arrayDates[labelNumber] = moment().startOf('year').add(labelNumber, 'months').format(format);
            labelNumber++;
        }
    }

    return arrayDates;
}

function generateChart(data, place) {
    var avg_diesel = 'avg_diesel_' + place;
    var avg_95     = 'avg_95_' + place;
    var avg_98     = 'avg_98_' + place;
    var element    = 'chart-' + place;
    var whichDisplayDate = $('#chart_formatTime').val();
    var arrayDates;
    if ('days' === whichDisplayDate) {
        arrayDates = newArrayDates('DD MMM YYYY');
    } else {
        arrayDates = newArrayDates('MMM YYYY');
    }
    var chart_context = document.getElementById(element).getContext("2d");
    var dataset_diesel    = {
        label: 'Diesel',
        borderColor: "#5DA9E9",
        borderWidth: 2,
        fill: false,
        data: [
            data[0][0][avg_diesel],
            data[1][0][avg_diesel],
            data[2][0][avg_diesel],
            data[3][0][avg_diesel],
            data[4][0][avg_diesel],
            data[5][0][avg_diesel],
            data[6][0][avg_diesel],
            data[7][0][avg_diesel],
            data[8][0][avg_diesel],
            data[9][0][avg_diesel],
            data[10][0][avg_diesel],
            data[11][0][avg_diesel],
        ]
    };
    var dataset_95    = {
        label: 'Unleaded 95',
        borderColor: "#26A65B",
        borderWidth: 2,
        fill: false,
        data: [
            data[0][0][avg_95],
            data[1][0][avg_95],
            data[2][0][avg_95],
            data[3][0][avg_95],
            data[4][0][avg_95],
            data[5][0][avg_95],
            data[6][0][avg_95],
            data[7][0][avg_95],
            data[8][0][avg_95],
            data[9][0][avg_95],
            data[10][0][avg_95],
            data[11][0][avg_95],
        ]
    };
    var dataset_98    = {
        label: 'Unleaded 98',
        borderColor: "#F4D03F",
        borderWidth: 2,
        fill: false,
        data: [
            data[0][0][avg_98],
            data[1][0][avg_98],
            data[2][0][avg_98],
            data[3][0][avg_98],
            data[4][0][avg_98],
            data[5][0][avg_98],
            data[6][0][avg_98],
            data[7][0][avg_98],
            data[8][0][avg_98],
            data[9][0][avg_98],
            data[10][0][avg_98],
            data[11][0][avg_98],
        ]
    };
    var chart_config    = {
        type: 'line',
        data: {
            labels: [
                arrayDates[0],
                arrayDates[1],
                arrayDates[2],
                arrayDates[3],
                arrayDates[4],
                arrayDates[5],
                arrayDates[6],
                arrayDates[7],
                arrayDates[8],
                arrayDates[9],
                arrayDates[10],
                arrayDates[11],
            ],
            datasets: [
                dataset_diesel,
                dataset_95,
                dataset_98,
            ]
        },
        options:  {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: "Per " + whichDisplayDate,
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display:true,
                        labelString: '€/Liter',
                    },
                    ticks: {
                        suggestedMin: 1,
                        suggestedMax: 1.8,
                        stepSize: 0.05
                    }
                }],
            }
        }
    };

    new Chart(chart_context, chart_config);
}

function generateCharts() {
    var arrayDates         = newArrayDates('DD/MM/YYYY');
    var whichFormatDisplay = $('#chart_formatTime').val();

    var url = Routing.generate('retrieveAveragePricesByFuelTypeDate');
    $.ajax({
        url: url,
        method: "post",
        data: {
            "arrayDates": arrayDates,
            "whichFormatDisplay": whichFormatDisplay,
        }
    }).done(function (data) {
        generateChart(data.idf, 'idf');
        generateChart(data.paris, 'paris');
    });
}

$(document).ready(function(){
    generateCharts();

    $('#button_formatTime_days').click(function () {
        $('#chart_formatTime').val('days');
        generateCharts();
    });

    $('#button_formatTime_year').click(function () {
        $('#chart_formatTime').val('year');
        generateCharts();
    });
});