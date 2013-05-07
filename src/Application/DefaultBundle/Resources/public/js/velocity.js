google.load('visualization', '1', {packages: ['corechart']});
function drawVisualization() {

    var backlogurl = ($('body').data('backlog') ? '/departmentvelocity/'+ $('body').data('backlog') : '');

    // Get the json
    var jsonData = $.ajax({
            url: "/velocity/data" + backlogurl,
            dataType:"json",
            async: false
    }).responseText;

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);

        // var data = google.visualization.arrayToDataTable([
        //   ['Year/Month', 'Velocity'],
        //   ['2012/09',  23],
        //   ['2012/10',  20],
        //   ['2012/11',  26],
        //   ['2012/12',  24]
        // ]);

    var options = {
      title: 'Company Performance',
      hAxis: {title: 'Year', titleTextStyle: {color: 'red'}}
    };

    var options = {
        title : 'Department Velocity',
        vAxis: {title: "Velocity", baseline: 0, gridlines: { count: 10 }, maxValue: 35},
        hAxis: {title: "Year/Month"}
    };

    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    chart.draw(data, options);  

}
setTimeout(google.setOnLoadCallback(drawVisualization), 2000);
