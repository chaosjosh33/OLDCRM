<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(-1);
require '../app.php';
$twilioObj = new twilioPlugin;
$client = new Services_Twilio($twilioObj->accountSid, $twilioObj->authToken);
$mongo = new MongoClient();
$app = new \Slim\Slim();
$app->map('/connect', function () use ($app, $settings, $client, $twilioObj, $mongo)  {
	$dbresult = $mongo->trentTest->user->findOne(array('email'=>$_REQUEST['conference']));
	$clientId = strtoupper(str_replace('-', '', preg_replace('/[^A-Za-z0-9\-]/', '', $dbresult['_id'])));
	$numberOrClient = 'client:' . $clientId;
	$update = $client->account->calls->get($_REQUEST['callSid']);
	$update->update(array('Url' => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$settings['base_uri'].'twilioApps/conference?room='.$_REQUEST['conference'], 'Method' => 'GET'));
	$client->account->calls->create($twilioObj->callerId, $numberOrClient,  (isset($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$settings['base_uri'].'twilioApps/conference?room='.$_REQUEST['conference'].'&end=yes', array(
		'Method' => 'GET'
	));
})->via('GET','POST');
$app->map('/', function () use ($app, $settings, $client, $twilioObj, $mongo)  {
	foreach ($client->account->queues as $queue) {
		$result['queues'][$queue->sid]['queueSid']    = $queue->sid;
		$result['queues'][$queue->sid]['queueName']   = $queue->friendly_name;
		$result['queues'][$queue->sid]['currentSize'] = $queue->current_size;
		$result['queues'][$queue->sid]['maxSize']     = $queue->max_size;
		$result['queues'][$queue->sid]['waitTime']    = $queue->average_wait_time;
		foreach ($client->account->queues->get($queue->sid)->members as $member){
			$call= $client->account->calls->get($member->call_sid);
			$result['queues'][$queue->sid]['members'][$member->call_sid]['callSid']         = $member->call_sid;
			$result['queues'][$queue->sid]['members'][$member->call_sid]['position']        = $member->position;
			$result['queues'][$queue->sid]['members'][$member->call_sid]['number']          = $call->from;
			$result['queues'][$queue->sid]['members'][$member->call_sid]['forwardedFrom']   = $call->forwarded_from;
		}
	}
	echo json_encode($result);
})->via('GET','POST');
$app->run();