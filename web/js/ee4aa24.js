google.load('visualization', '1', {packages: ['corechart']});
function drawVisualization() {

    var backlogurl = ($('body').data('backlog') ? '/data/departmentvelocity/'+ $('body').data('backlog') : '/data/departmentvelocity');

    // Get the json
    var jsonData = getData(backlogurl);

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);

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
