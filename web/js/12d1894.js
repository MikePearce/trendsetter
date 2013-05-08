google.load('visualization', '1', {packages: ['corechart']});
function drawVisualization() {

    if ($('body').data('datatype') == 'acceptancerate') {
        var title = 'Acceptance Rate';
        var backlogurl = '/acceptancerate';
    }
    else {
        var title = 'No. of Stories';
        var backlogurl = '/totalstoriespermonth';
    }

    backlogurl += ($('body').data('backlog') 
        ? '/'+$('body').data('backlog') 
        : ''
    );

    // Get the json
    var jsonData = $.ajax({
            url: "/stories/data" + backlogurl,
            dataType:"json",
            async: false
    }).responseText;

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);

    var options = {
        vAxis: {title: title, baseline: 0, gridlines: { count: 10 }, maxValue: 35},
        hAxis: {title: "Year/Month"}
    };

    if ($('body').data('charttype') == 'line') {
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    }
    else {
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));    
    }
    
    chart.draw(data, options);    
}
setTimeout(google.setOnLoadCallback(drawVisualization), 2000);
