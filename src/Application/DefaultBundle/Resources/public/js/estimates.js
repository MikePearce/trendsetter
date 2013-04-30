google.load('visualization', '1', {packages: ['corechart']});
function drawVisualization() {

    // Get the json
    var jsonData = $.ajax({
            url: "/estimates/data",
            dataType:"json",
            async: false
        }).responseText;


    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);


    var options = {
        title : 'Monthly spread of story points: all teams',
        vAxis: {title: "No of instances"},
        hAxis: {title: "Year/Month"},
        seriesType: "bars",
        //series: {5: {type: "line"}}
    };

    var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}
setTimeout(google.setOnLoadCallback(drawVisualization), 2000);
