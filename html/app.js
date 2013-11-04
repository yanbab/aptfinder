/* app.js */

var app = angular.module('app', []);


/* Controllers */

function defaultCtrl($scope, $http) {
   
    $scope.data = []; 
    $scope.modal = function () {
    	$scope.showModal = true;
    } 

	$scope.filterApps = function (app) {
		if(!$scope.filterCategory || app.category == $scope.filterCategory)
		return true;
	}
    
    $scope.get = function(name) {
	    $http({method: 'GET', url: '../data/' + name + '.json'}).
			success(function(data, status, headers, config) {
	    		$scope.data[name] = data;
	  		}).
	  		error(function(data, status, headers, config) {
	    		$scope.error = "Can't fetch "  + name;
	  	});

    }

    $scope.get('categories');
    $scope.get('packages');
}


