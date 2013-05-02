google.load('visualization', '1', {packages: ['corechart']});
function drawVisualization() {

    var backlogurl = ($('body').data('backlog') ? '/backlogtotalstoriespermonth/'+ $('body').data('backlog') : '');

    // Get the json
    var jsonData_totalPerMonth = $.ajax({
            url: "/stories/data" + backlogurl,
            dataType:"json",
            async: false
    }).responseText;

    // Create our data table out of JSON data loaded from server.
    var data_totalPerMonth = new google.visualization.DataTable(jsonData_totalPerMonth);
        var options = {
          title: 'Company Performance',
          hAxis: {title: 'Year', titleTextStyle: {color: 'red'}}
        };

    var options_totalPerMonth = {
        title : 'Total stories created per month',
        vAxis: {title: "No of stories"},
        hAxis: {title: "Year/Month"},
        seriesType: "bars"
    };

    var chart_totalPerMonth = new google.visualization.ComboChart(document.getElementById('chart_div_totalPerMonth'));
    chart_totalPerMonth.draw(data_totalPerMonth, options_totalPerMonth);    
}
setTimeout(google.setOnLoadCallback(drawVisualization), 2000);
