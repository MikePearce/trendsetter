// Truncate
angular.module('filters', []).
    filter('truncate', function () {
        return function (text, length, end) {
            if (isNaN(length))
                length = 10;
 
            if (end === undefined)
                end = "...";
 
            if (text.length <= length || text.length - end.length <= length) {
                return text;
            }
            else {
                return String(text).substring(0, length-end.length) + end;
            }
 
        };
    });
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
    Stats.teamstats = angular.fromJson(getData('/data/teamstats'));
    Stats.deptstats = angular.fromJson(getData('/data/deptstats'));
    return Stats;
});

var HomepageCtrl = function($scope, Stats) {    
    $scope.deptstats = Stats.deptstats;
    $scope.teamstats = Stats.teamstats;
}