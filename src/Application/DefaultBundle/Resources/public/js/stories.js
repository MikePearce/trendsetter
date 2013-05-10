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


    backlogurl += ( $('body').data('backlog')
        ? '/' + $('body').data('backlog')
        : ''
    );

    var chartData = getData(backlogurl);

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(chartData);

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



// Angular shizzie
var storyFilter = angular.module('storyFilter', ['ui.bootstrap', 'filters']).config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{[').endSymbol(']}');
});


storyFilter.factory('Backlog', function() {
    var Backlog = {};
    Backlog.stories = angular.fromJson(getData('/stories/'+ $('body').data('backlog')));
    return Backlog;
});

var BacklogCtrl = function($scope, Backlog) {
    
    $scope.currentPage = 1;
    $scope.backlog = Backlog;
    $scope.pageSize = 10;
    $scope.noOfPages = Math.ceil($scope.backlog.stories.length/$scope.pageSize);
    $scope.maxSize = 10;

    // Which Page?
    $scope.selectPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };

    // Modal
    $scope.open = function (storyid) {
        $scope.shouldBeOpen = true;
        $scope.story = angular.fromJson(getData('/story/' + $('body').data('backlog') + '/' + storyid));
    };

    $scope.close = function () {
        $scope.shouldBeOpen = false;
    };

    

    $scope.opts = {
        backdropFade: true,
        dialogFade:true
    };
    
};

//We already have a limitTo filter built-in to angular,
//let's make a startFrom filter
storyFilter.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});

