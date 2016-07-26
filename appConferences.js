function ConferencesCtrl($scope,$http,$interval, $rootScope,$stateParams){
	var conferenceData = this;
	$scope.conferenceSid = $stateParams.conferenceSid;
	conferenceData.conference = [ ];
	$scope.conferenceItem = [ ];
	$http.get("api/conferences").success(function(response) {
		conferenceData.conference = response.conference;
	});
	if($scope.conferenceSid != undefined){
		$http.get("api/conferences/search/"+$scope.conferenceSid).success(function(response) {
			conferenceData.conferenceItem = response.conference;
			console.log(conferenceData.conferenceItem);
		});
	}
	var promise;
	// starts the interval
	$scope.start = function() {
		// stops any running interval to avoid two intervals running at the same time
		$scope.stop();
		// store the interval promise
		promise =   $interval(
			function(){
				$http.get("api/conferences").success(function(response) {
					conferenceData.conference = response.conference;
					console.log("live conferences!");
				});
			}.bind(this)
			,1000 * 4);
	};
	// stops the interval
	$scope.stop = function() {
		$interval.cancel(promise);
	};
	// starting the interval by default
	$scope.start();
	$scope.$on('$destroy', function() {
		$scope.stop();
	});
	$scope.moderate=  function (conference){
		params = {"moderate": conference};
		Twilio.Device.connect(params);
	}
}


angular
	.module('insurecrm')
	.controller('ConferencesCtrl',ConferencesCtrl)
function conferencesApi() {
	return {
		restrict: 'A',
		templateUrl: 'api/conferences/conferences.php',
		scope: {
		}
	};
};
function conferenceApi() {
	return {
		restrict: 'A',
		templateUrl: 'api/conferences/conference.php',
		scope: {
		}
	};
};
angular
	.module('insurecrm')
	.directive('conferencesApi', conferencesApi)
	.directive('conferenceApi', conferenceApi)
	.filter("toArray", function(){
	return function(obj) {
		var result = [];
		angular.forEach(obj, function(val, key) {
			result.push(val);
		});
		return result;
	};
});