function getData(backlogurl) {
    // Get the json
    var jsonData = $.ajax({
            url: backlogurl,
            dataType: "json",
            async: false
    }).responseText;

    return jsonData;
}

// Homepage stuff
var homePage = angular.module('homePage', ['ui.bootstrap', 'filters']).config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{[').endSymbol(']}');
});


homePage.factory('Stats', function() {
    var Stats = {};
    Stats.teamstats = angular.fromJson(getData('/data/general-teamstats'));
    Stats.deptstats = angular.fromJson(getData('/data/general-deptstats'));
    return Stats;
});

var HomepageCtrl = function($scope, Stats) {    
    $scope.stats = Stats;
    console.log($scope.stats);
}