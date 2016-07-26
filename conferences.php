<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Table with data from $scope - <strong>(The angular way)</strong></h5>
					<div ibox-tools></div>
				</div>
				<div class="ibox-content">
					<table ng-controller="ConferencesCtrl as confs" class="table table-striped table-bordered table-hover dataTables-example">
						<thead>
							<tr>
								<th>Conference Name</th>
								<th>Date Created</th>
								<th>Participants</th>
								<th>Client Number</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="conference in confs.conference | toArray | orderBy:'-status'">
								<td>{{ conference.friendly_name }}</td>
								<td>{{ conference.date }}</td>
								<td>{{ conference.participants }}</td>
								<td>{{ conference.From }}</td>
								<td>
									<a class="btn btn-info" ng-if="conference.status == 'completed'" href="#api/conference/{{conference.sid}}"><i class="fa fa-search"></i></a>
									<a href="{{conference.RecordingUrl}}" target="_blank"><button class="btn btn-success" ng-if="conference.RecordingUrl"><i class="fa fa-play"></i></button></a>
									<button class="btn btn-primary" ng-if="conference.status == 'in-progress'" ng-click="moderate(conference.friendly_name)"><i class="fa fa-headphones"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>