<?php require __DIR__ . '/../app.php'; ?>
	<div ng-controller="QueuesCtrl as list" class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Table with data from $scope - <strong>(The angular way)</strong></h5>
					<div ibox-tools></div>
				</div>
				<div class="ibox-content" ng-repeat="queue in list.queues | toArray">
				<table  class="table table-hover dataTables-example">
					<th width="33%"><h3>{{queue.queueName}}</h3></th>
					<th width="33%"><h3>Current Size: {{queue.currentSize}}</h3></th>
					<th width="33%"><h3>Average Wait Time: {{queue.waitTime}}</h3></th>
				</table>
					<table class="table table-striped table-bordered table-hover dataTables-example">
						<thead>
							<tr>
								<th>Caller</th>
								<th>Caller Position</th>
								<th>Number Called</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="caller in queue.members | toArray">
								<td>{{caller.number}}</td>
								<td>{{caller.position}}</td>
								<td>{{caller.forwardedFrom}}</td>
								<td><button class="btn btn-success" ng-click="connect(caller.callSid, '<?php echo $_SESSION['api']['user']['email']; ?>');model.status='Ready';model.statusColor='info';model.break=false;model.ready=true;model.online=false;model.offline=false;model.standby=false"><i class="fa fa-phone"></i></button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>