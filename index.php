<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(-1);
require '../app.php';
$twilioObj = new twilioPlugin;
$client = new Services_Twilio($twilioObj->accountSid, $twilioObj->authToken);
$mongo = new MongoClient();
$app = new \Slim\Slim();
$app->map('/', function () use ($app, $settings, $client, $twilioObj, $mongo)  {
	header("Content-Type: application/json");
	$datacursor = $mongo->agentPortal->conferences->find();
	$datasort = $datacursor->sort(array('date_created'=>-1));
	foreach($datasort as $conf){
		$dataprint['conference'][$conf['sid']]['friendly_name'] = $conf['friendly_name'];
		$dataprint['conference'][$conf['sid']]['sid']           = $conf['sid'];
		$dataprint['conference'][$conf['sid']]['participants']  = $conf['participants'];
		$dataprint['conference'][$conf['sid']]['status']        = $conf['status'];
		$dataprint['conference'][$conf['sid']]['duration']      = $conf['duration'];
		$dataprint['conference'][$conf['sid']]['date']  	    = $conf['date'];
		$dataprint['conference'][$conf['sid']]['date_created']  = $conf['date_created'];
	}
	$conferences = $client->account->conferences->getIterator(0, 50, array('Status'=>"in-progress",
																		  ));
	foreach ($conferences as $conference) {
		$conferenceRoom = $client->account->conferences->get($conference->sid);
		$date1 = new DateTime($conference->date_created);
		$date2 = new DateTime($conference->date_updated);
		$interval = $date1->diff($date2);
		$page = $conferenceRoom->participants->getPage(0, 50);
		$participants = $page->participants;
		$participantCount = count($participants);
		$result['conference'][$conference->sid]['friendly_name'] = $conference->friendly_name;
		$result['conference'][$conference->sid]['sid']           = $conference->sid;
		$result['conference'][$conference->sid]['participants']  = $participantCount;
		$result['conference'][$conference->sid]['status']        = $conference->status;
		$result['conference'][$conference->sid]['duration']      = $interval->format('%H:%I:%S%');
		$result['conference'][$conference->sid]['date_created']  = strtotime($conference->date_created);
		$result['conference'][$conference->sid]['date'] 		  = $conference->date_created;
	}
	if(!empty($result)){
		$merge = array_merge_recursive($result,$dataprint);
	}else{$merge=$dataprint;}
	echo json_encode($merge);
	//echo '<pre>'.json_encode($dataprint, JSON_PRETTY_PRINT);
	//echo json_encode($dataprint);
})->via('GET','POST');
$app->map('/dbadd', function () use ($app, $settings, $client, $twilioObj, $mongo)  {
	if(isset($_REQUEST['ConferenceSid'])){
		$conference = $client->account->conferences->get($_REQUEST['ConferenceSid']);
		$date1 = new DateTime($conference->date_created);
		$date2 = new DateTime($conference->date_updated);
		$interval = $date1->diff($date2);
		$cursor = $mongo->agentPortal->conferences->findOne(array('sid'=>$conference->sid));
		if(array_key_exists('CallerCity', $_REQUEST)){
			if(count($cursor) == 0){
				$sdat2=array(
					'friendly_name'=>$conference->friendly_name,
					'sid' => $conference->sid,
					'participants' => $participantCount,
					'status' => 'completed',
					'duration' => $interval->format('%H:%I:%S%'),
					'date_created' => strtotime($conference->date_created),
					'date' => $conference->date_created
				);
				$merge = array_merge_recursive($_REQUEST,$sdat2);
				$mongo->agentPortal->conferences->save($merge);
			}
		}
	}else{
		$conferences = $client->account->conferences->getIterator(0, 50, array(
		));
		foreach ($conferences as $conference) {
			$conferenceRoom = $client->account->conferences->get($conference->sid);
			$date1 = new DateTime($conference->date_created);
			$date2 = new DateTime($conference->date_updated);
			$interval = $date1->diff($date2);
			$page = $conferenceRoom->participants->getPage(0, 50);
			$participants = $page->participants;
			$participantCount = count($participants);
			$cursor = $mongo->agentPortal->conferences->findOne(array('sid'=>$conference->sid));
			if($conference->status != 'in-progress'){
				if(count($cursor) == 0){
					$sdat=array(
						'friendly_name'=>$conference->friendly_name,
						'sid' => $conference->sid,
						'participants' => $participantCount,
						'status' => $conference->status,
						'duration' => $interval->format('%H:%I:%S%'),
						'date_created' => strtotime($conference->date_created),
						'date' => $conference->date_created
					);
					$mongo->agentPortal->conferences->save($sdat);
				}
			}
		}
	}
	header("Content-Type: application/json");
?>
<Response>
	<Hangup/>
</Response>
<?php
	//echo '<pre>'.json_encode($result, JSON_PRETTY_PRINT);
})->via('GET','POST');
$app->run();