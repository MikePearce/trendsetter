google.load('visualization', '1', {packages: ['corechart']});
function drawVisualization() {
    
    var backlogurl = ($('body').data('backlog') 
        ? '/data/backlogestimatespread/'+ $('body').data('backlog') 
        : '/data/backlogestimatespread'
    );

    // Get the json
    var jsonData = getData(backlogurl);

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);

    var options = {
        vAxis: {title: "No of instances"},
        hAxis: {title: "Year/Month"},
        bar: { groupWidth: "90%" },
        seriesType: "bars",
    };

    var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
    chart.draw(data, options);

}
setTimeout(google.setOnLoadCallback(drawVisualization), 2000);
