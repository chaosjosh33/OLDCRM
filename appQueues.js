function QueuesCtrl($scope,$http,$interval){
	var queueData = this;
	queueData.queues =[];
	$http.get("api/queues").success(function(response) {
		queueData.queues = response.queues;
	});
	var promise;
	// starts the interval
	$scope.start = function() {
		// stops any running interval to avoid two intervals running at the same time
		$scope.stop();
		// store the interval promise
		promise =   $interval(
			function(){
				$http.get("api/queues").success(function(response) {
					queueData.queues = response.queues;
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
	$scope.connect = function (callSid,conference){
		params = {"callSid": callSid, "conference": conference};
		$http.post("api/queues/connect?callSid="+callSid+"&conference="+conference, params);
	}
}


angular
	.module('insurecrm')
	.controller('QueuesCtrl', QueuesCtrl)
function queuesApi() {
	return {
		restrict: 'A',
		templateUrl: 'api/queues/queues.php',
		scope: {
		}
	};
};
angular
	.module('insurecrm')
	.directive('queuesApi', queuesApi)
	.filter("toArray", function(){
	return function(obj) {
		var result = [];
		angular.forEach(obj, function(val, key) {
			result.push(val);
		});
		return result;
	};
});