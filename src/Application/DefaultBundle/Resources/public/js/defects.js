google.load('visualization', '1', {packages: ['corechart']});
function drawVisualization() {
    // Get the json
    var jsonData = getData('/data/defects');

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);

    var options = {
        vAxis: {title: "No of defects"},
        hAxis: {title: "Year/Month"},
        bar: { groupWidth: "90%" },
        seriesType: "bars",
    };

    var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
    chart.draw(data, options);
    
    // By Priority
    var jsonData_p = getData('/data/defectspriority');
    var data_p = new google.visualization.DataTable(jsonData_p);
    var chart_p = new google.visualization.LineChart(document.getElementById('chart_div_p'));
    chart_p.draw(data_p);
    
    // By Alive
    var jsonData_a = getData('/data/defects');
    var data_a = new google.visualization.DataTable(jsonData_a);
    var chart_a = new google.visualization.ComboChart(document.getElementById('chart_div_a'));
    chart_a.draw(data_a, options);

}
setTimeout(google.setOnLoadCallback(drawVisualization), 2000);
