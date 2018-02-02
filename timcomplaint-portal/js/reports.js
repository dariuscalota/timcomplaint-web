var filtersObj = {
    date_from: "",
    date_to: "",
    category: ""
};

var ChartData = [0,0,0,0];

$(document).ready(function () {
    $('.input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true
    });

    getFreshData();
    refreshGraphic();
});

var refreshGraphic = function() {
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        responsive: false,
        type: 'bar',
        data: {
            labels: ["OPEN", "INPROGRESS", "SOLVED", "CLOSED"],
            datasets: [{
                label: '# of Complaints',
                data: ChartData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

var getFreshData = function() {
    $.ajax({
        url: 'http://localhost/www/timcomplaint-api/reports/simple.php',
        contentType: "application/json",
        type: 'GET',
        data: filtersObj,
        success: function(response) {
            ChartData = [];
            for (var key in response){
                ChartData.push(response[key]);
            }
            refreshGraphic();
        }
     });
};

$('#category').on('change', function() {
    filtersObj.category = $('#category').val();
    getFreshData();
});

$('#date_from').on('changeDate', function() {
    var d,n;
    var dateValue = $('#date_from').datepicker('getFormattedDate');
    if(dateValue && dateValue.length>0){
        d = new Date(dateValue);
        n = d.toISOString();
        n = n.split("T")[0];
    } else {
        n="";
    }
    filtersObj.date_from = n;
    getFreshData();
});

$('#date_to').on('changeDate', function() {
    var d,n;
    var dateValue = $('#date_to').datepicker('getFormattedDate');
    if(dateValue && dateValue.length>0){
        d = new Date(dateValue);
        n = d.toISOString();
        n = n.split("T")[0];
    } else {
        n="";
    }
    
    filtersObj.date_to = n;
    getFreshData();
});

