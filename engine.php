<?php
namespace thingEngine;
/*
*
* thingExtension CLASS
*/
class thingExtension
{
    var $id;
    var $referenceId;
    var $dateCreated;
    var $dateModified;
    var $createdBy;
    var $lastModifiedBy;
    var $extensionInfo;
    var $changeNotifications;
    var $changesToObject = array();
    var $settings = array();
    function __construct($settings = false){
        $this->settings = $settings;
        $this->setReferenceId($this->getRandomId());
        //Set Information about the extension
        $this->extensionInfo['name'] = "Extension Maker";
        $this->extensionInfo['description'] = "Use This To Create Extensions";
        $this->extensionInfo['author'] = "ThingEngine";
        $this->extensionInfo['owner'] = "thingengine";
        $this->extensionInfo['version'] = "1.0";
        $this->extensionInfo['dateCreated'] = "01/01/2015";
        $this->extensionInfo['price'] = "0.00";
    }
    function verifyOwnership(){
        // Check to see if owner has paid for this plugin
    }
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->setVar($property,$value);
        }
    }
    public function setReferenceId($refId){
        if ((!empty($this->referenceId)) || (((trim($this->referenceId)) <> (trim($this->refId))))) {
            $this->changesToObject['referenceId']['OLD'] = $this->referenceId;
            $this->referenceId = $refId;
            $this->changesToObject['referenceId'] = $refId;
            $this->changesToObject['referenceId']['NEW'] = $var;
        }
    }
    // Generic Set Var
    public function setVar($key, $var){
        if ((trim(strtoupper($this->$key))) <> (trim(strtoupper($var))) ){
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $var = filter_var($var, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $this->$key = trim($var);
            $this->changesToObject[$key]['NEW'] = $var;
        }
    }
    // Generic Set Var
    public function setVarToHtml($key, $var){
        if ((trim(strtoupper($this->$key))) <> (trim(strtoupper($var))) ){
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $this->$key = trim($var);
            $this->changesToObject[$key]['NEW'] = $var;
        }
    }
    // Generic Set Var To Upper
    public function setVarToUpper($key, $var){
        if ((trim(strtoupper($this->$key))) <> (trim(strtoupper($var))) ){
            $var = filter_var($var, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $var = strtoupper(trim($var));
            $this->$key = $var;
            $this->changesToObject[$key]['NEW'] = $var;
        }
    }
    // Generic Set Var To Lower
    public function setVarToLower($key, $var){
        if ((trim(strtoupper($this->$key))) <> (trim(strtoupper($var))) ){
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $var = filter_var($var, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $var = strtoupper(trim($var));
            $this->$key = $var;
            $this->changesToObject[$key]['NEW'] = $var;
        }
    }
    //
    public function setVarToPassword($key, $var){
        $options = [
            'cost' => 10,
            'salt' => $this->settings['password_salt']
        ];
        $var =  password_hash($var, PASSWORD_BCRYPT, $options);
        if ((trim(strtoupper($this->$key))) <> (trim(strtoupper($var))) ){
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $this->$key = $var;
            $this->changesToObject[$key]['NEW'] = $this->$key;
        }
    }
    //
    public function setVarToEmail($key, $var){
        if ((trim(strtoupper($this->$key))) <> (trim(strtoupper($var))) ){
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $var = strtolower(filter_var($var, FILTER_SANITIZE_EMAIL));
            // Validate e-mail
            if (!filter_var($var, FILTER_VALIDATE_EMAIL) === false) {
                $this->$key = $var;
            } else {
                $this->$key = "";
            }
            $this->changesToObject[$key]['NEW'] = $this->$key;
        }
    }
    //
    public function setVarToDate($key, $var){
        if (date("Y-m-d", strtotime($this->$key)) <> date("Y-m-d", strtotime($var)) ){
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $var =  date("Y-m-d", strtotime($var));
            $this->$key = $var;
            $this->changesToObject[$key]['NEW'] = $this->$key;
        }
    }
    //
    public function setVarToDateTime($key, $var){
        if (date("Y-m-d H:i:s", strtotime($this->$key)) <> date("Y-m-d H:i:s", strtotime($var)) ){
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $var =  date("Y-m-d H:i:s", strtotime($var));
            $this->$key = $var;
            $this->changesToObject[$key]['NEW'] = $this->$key;
        }
    }
    //
    public function setVarToPhone($key, $var){
        if (preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '$1$2$3', $this->$key) <> preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '$1$2$3', $var) ){
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $var =  preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '$1$2$3', $var);
            $this->$key = $var;
            $this->changesToObject[$key]['NEW'] = $this->$key;
        }
    }
    //
    public function setVarToNumeric($key, $var){
        if ((trim(strtoupper($this->$key))) <> (trim(strtoupper($var))) ){
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $var = trim($var);
            $dotPos = strrpos($var, '.');
            $commaPos = strrpos($var, ',');
            $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : 
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
            if (!$sep) {
                $var = floatval(preg_replace("/[^0-9]/", "", $var));
            } else {
                $var = floatval(
                    preg_replace("/[^0-9]/", "", substr($var, 0, $sep)) . '.' .
                    preg_replace("/[^0-9]/", "", substr($var, $sep+1, strlen($var)))
                );
            }
            $this->{$key} = $var;
            $this->changesToObject[$key]['NEW'] = $this->$key;
        }
    }
    //
    public function setVarToEncrypted($key, $var){
        
        $thingEngine = new engine();
        $varEncrypt = $thingEngine->setEncrypt(trim($var));
        if ( ( (trim(strtoupper($this->$key))) <> (trim(strtoupper($var))) ) && (($this->$key) <> ($varEncrypt)) && (($thingEngine->getDecrypt($this->$key)) <> (trim($var)) ) ){
            $this->changesToObject[$key]['OLD'] = $this->$key;
            $this->{$key} = $varEncrypt;
            $this->changesToObject[$key]['NEW'] = $varEncrypt;
        }
       
    }
    public function displayPhoneNumber($phoneNumber){
        if(strlen($phoneNumber) == 11){
            return "(".substr($phoneNumber, 1, 3).") ".substr($phoneNumber, 4, 3)."-".substr($phoneNumber,7);   
        }
        if(strlen($phoneNumber) == 10){
            return "(".substr($phoneNumber, 0, 3).") ".substr($phoneNumber, 3, 3)."-".substr($phoneNumber,6);   
        }
    }
    public function displayCurrency($var){
        return money_format('%.2n', $var);
    }
     public function displaySocialSecurityNumber($var){
         if(!empty($var)){
             return substr($var, 0, 3)."-".substr($var, 4, 2)."-".substr($var,6);  
         } else {
             return false;
         }
    }
    public function displayDate($var,$format = "m/d/Y"){
        return date($format, strtotime($var));
    }
    public function displayTime($var,$format = "h:i a"){
        return date($format, strtotime($var));
    }
    public function displayDateTime($var,$format = "m/d/Y h:i a"){
        return date($format, strtotime($var));
    }
    public function displayTimeStamp($var,$format = "m/d/Y H:i:s"){
        return date($format, strtotime($var));
    }
    public function displayEncrypted($var, $lastChars = 0){
         $thingEngine = new engine();
         return $thingEngine->getDecrypt($var);
    }



    //
    public function setVarToState($key, $var, $format = "ABBREV"){
        if ((trim(strtoupper($this->$key))) <> (trim(strtoupper($var))) ){
            $var = trim($var);
            $states = array (
                'AL'=>'Alabama',
                'AK'=>'Alaska',
                'AZ'=>'Arizona',
                'AR'=>'Arkansas',
                'CA'=>'California',
                'CO'=>'Colorado',
                'CT'=>'Connecticut',
                'DE'=>'Delaware',
                'DC'=>'District Of Columbia',
                'FL'=>'Florida',
                'GA'=>'Georgia',
                'HI'=>'Hawaii',
                'ID'=>'Idaho',
                'IL'=>'Illinois',
                'IN'=>'Indiana',
                'IA'=>'Iowa',
                'KS'=>'Kansas',
                'KY'=>'Kentucky',
                'LA'=>'Louisiana',
                'ME'=>'Maine',
                'MD'=>'Maryland',
                'MA'=>'Massachusetts',
                'MI'=>'Michigan',
                'MN'=>'Minnesota',
                'MS'=>'Mississippi',
                'MO'=>'Missouri',
                'MT'=>'Montana',
                'NE'=>'Nebraska',
                'NV'=>'Nevada',
                'NH'=>'New Hampshire',
                'NJ'=>'New Jersey',
                'NM'=>'New Mexico',
                'NY'=>'New York',
                'NC'=>'North Carolina',
                'ND'=>'North Dakota',
                'OH'=>'Ohio',
                'OK'=>'Oklahoma',
                'OR'=>'Oregon',
                'PA'=>'Pennsylvania',
                'RI'=>'Rhode Island',
                'SC'=>'South Carolina',
                'SD'=>'South Dakota',
                'TN'=>'Tennessee',
                'TX'=>'Texas',
                'UT'=>'Utah',
                'VT'=>'Vermont',
                'VA'=>'Virginia',
                'WA'=>'Washington',
                'WV'=>'West Virginia',
                'WI'=>'Wisconsin',
                'WY'=>'Wyoming',
            );
            foreach( $states as $abbr => $name ) {
                if ( preg_match( "/\b($name)\b/", ucwords( strtolower( $var ) ), $match ) )  {
                    if( strtoupper($format) != "NAME" ){ 
                        $this->$key = $abbr;
                    } else {
                        $this->$key = strtoupper($name);
                    }
                }
                elseif( preg_match("/\b($abbr)\b/", strtoupper( $var ), $match) ) {                    
                    if( strtoupper($format) != "NAME" ){ 
                        $this->$key = $abbr;
                    } else {
                        $this->$key = strtoupper($name);
                    }
                } 
            }
            $this->changesToObject[$key] = $this->$key;
        } else {
             $this->$key = strtoupper($var);
             $this->changesToObject[$key] = $this->$key;
        }
    }
    //
    public function objToArray(){
        return json_decode(json_encode($this), true);
    }
    //
    function getRandomId($parts=1, $part_size=6){
        $id = date("YmdHis").$this->getRandomString($part_size);
        if($parts > 1){
            for ($i = 2; $i <= $parts; $i++) {
                $id .= "-".$this->getRandomString($part_size);
            }  
        }
        return $id;
    }
    function getRandomString($name_length = 6) {
        $alpha_numeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        return substr(str_shuffle(str_repeat($alpha_numeric,12)), 0, $name_length);
    }
    //
    public function changeNotification($type,$id,$field,$newValue,$oldValue = false){
        $userId = 0;
        if(!empty($_SESSION['api']['user']['id'])){
            $userId = $_SESSION['api']['user']['id'];   
        }
        $this->changeNotifications[] = array("parentThing"=>$type, "parentId"=>$id, "field"=>$field, "newValue"=>$newValue, "oldValue"=>$oldValue, "userId"=>$userId);
    }

    //
    public function saveChangeNotifications(){
        if(!empty($this->changeNotifications)){
            $RBFreeze = FALSE;
            $referenceId = $this->getRandomString();
            foreach($this->changeNotifications as $key=>$var){
                $changeObj = \RedBeanPHP\Facade::load("changeNotifications",0);
                $changeObj->referenceId = $referenceId;
                $changeObj->dateCreated = date("Y-m-d H:i:s");
                $changeObj->parentThing = $var['parentThing'];
                $changeObj->parentId =$var['parentId'];
                $changeObj->field = $var['field'];
                $changeObj->newValue = $var['newValue'];
                $changeObj->oldValue = $var['oldValue'];
                $changeObj->userId = $var['userId'];
                \RedBeanPHP\Facade::store( $changeObj );
            }
            $RBFreeze = TRUE;
        }
        return true;
    }
    //
    public function debugNotifications(){
        debug($this->changeNotifications,"Notifications");
    }
    //
    public function saveData($tableName){
        $RBFreeze = TRUE;
        try{
            $confirm_save = false;
            if((empty($this->id)) || ($this->id < 1)){
                $this->id = 0;
            }


            $objCount = \RedBeanPHP\Facade::count( $tableName );

            if($objCount > 0){
                $obj = \RedBeanPHP\Facade::load( $tableName,  $this->id );
                $objCount = \RedBeanPHP\Facade::count( $tableName );
            }
            if($objCount < 1){
                $RBFreeze = FALSE;
                $obj = \RedBeanPHP\Facade::dispense( $tableName );
                $objFields = array();
            }


            $parentIds = array();
            // If changes to object, loop through changes and save. 
            if(!empty($this->changesToObject)){
                // Update Values
                if(empty($obj->dateCreated)){
                    $obj->dateCreated =  date("Y-m-d H:i:s");
                    $obj->changeNotification($tableName, $obj->id, "DATECREATED",  $obj->dateCreated );
                } 
                $obj->dateModified =  date("Y-m-d H:i:s");
                $obj->changeNotification($tableName, $obj->id, "DATEMODIFIED", $obj->dateModified);
                foreach($this->changesToObject as $key=>$val){
                    if(empty($objFields[$key])){
                        $RBFreeze = FALSE;
                    }
                    if(empty($val['NEW'])){
                        $val['NEW'] = "";
                    }
                    $obj->$key = $val['NEW'];
                    $this->changeNotification($tableName, $obj->id, $key, $val['NEW'], $val['OLD']);
                    unset($this->changesToObject[$key]);
                    //}
                }
                
                // Set to save
                $confirm_save = true;
                unset($this->settings);
                \RedBeanPHP\Facade::freeze( $RBFreeze );
                $obj->id = \RedBeanPHP\Facade::store( $obj );
                $this->id = $obj->id;
            }
            // If saving set notificaions
            if($confirm_save === true){
                $this->saveChangeNotifications();
            }
            // Set Parent Ids that will cascade to all children
            $parentIds[$tableName."Id"] = $obj->id;
            // Loop through children and save
            foreach($this as $k1=>$v1){
                if(!empty($v1)){
                    if(is_array($v1)){
                        foreach($v1 as $key=>$var){
                            if(is_object($var)){
                                $obj1 = $this->saveDataSubItem($parentIds,$k1, $var);
                                if (!empty($this->{$k1}[$key]->changesToObject)) {
                                    $this->{$k1}[$key]->changesToObject = array();
                                }

                                if( (!empty($this->{$k1}[$key])) && (!empty($obj1->id))){
                                    $this->{$k1}[$key]->id = $obj1->id;
                                }
                            }
                        }
                    }
                }
            }

            return true;
        }
        catch (\Exception $e) {
            debug($e, "EXCEPTION!");
            exit();
            return false;
        }
    }
    public function saveDataSubItem($parentIds, $k1, $var){
        $RBFreeze = TRUE;
        if((empty($var->id)) || ($var->id < 1)){
            $id = 0;
        } else {
            $id =  $var->id;
        }
        $objCount = \RedBeanPHP\Facade::count( $k1 );

        if($objCount > 0){
            $obj1 = \RedBeanPHP\Facade::load( $k1, $id );
            $objCount = \RedBeanPHP\Facade::count( $k1 );
        }
        if($objCount < 1){
            $RBFreeze = FALSE;
            $obj1 = \RedBeanPHP\Facade::dispense( $k1 );
            $objFields = array();
        }
        $confirm_save = false;;
        if(!empty($var->changesToObject)){
            if(empty($obj1->dateCreated)){
                $obj1->dateCreated =  date("Y-m-d H:i:s");
                $var->changeNotification($k1, $obj1->id, "DATECREATED", $obj1->dateCreated);
            } 
            $obj1->dateModified =  date("Y-m-d H:i:s");
            $var->changeNotification($k1, $obj1->id, "DATEMODIFIED",  $obj1->dateModified);
            if((!empty($parentIds)) && (is_array($parentIds))){
                foreach($parentIds as $pKey=>$pVar){
                    if(empty($obj1->{$pKey})){
                        $obj1->{$pKey} =  $pVar;
                        $var->changeNotification($k1, $obj1->id, strtoupper($pKey), $pVar);
                    } 
                }
            }
            foreach($var->changesToObject as $key2=>$var2){
                if(empty($objFields[$key2])){
                    $RBFreeze = FALSE; 
                }
                $val['OLD'] = $obj1->$key2;
                $obj1->$key2 = $var2['NEW'];
                $this->changeNotification($k1, $obj1->id, $key2, $var2['NEW'], $val['OLD']);
            }
            $confirm_save = true;
            unset($obj1->settings);
            \RedBeanPHP\Facade::freeze( $RBFreeze );
            $obj1->id = \RedBeanPHP\Facade::store( $obj1 );
        }
        if($confirm_save === true){
            $this->saveChangeNotifications();
        } 
        if((!empty($var)) && (is_object($var))){
            foreach($var as $key2=>$var2){
                if(($key2 <> "extensionInfo") && ($key2 <> "changesToObject")  && ($key2 <> "changeNotifications")){
                    if(is_array($var2)){
                        $parentIds[$k1."Id"] = $obj1->id;
                        foreach($var2 as $key3=>$var3){
                            $obj2 = $this->saveDataSubItem($parentIds,$key2,$var3);
                        }
                    }
                }
            }
        }
        return $obj1;
    }


    function getFieldList() {
        $field_list = array();
        foreach($this as $key => $value) {
            if(is_array($this->{$key})){
            } else {
                $field_list[$key] = $value;
            }

        }
        return $field_list;
    }

}
/*
*
* ThingEngine CLASS
*/
class Engine 
{
    public $slim;
    public $settings;
    protected $dbConn;
    public $things = array();
    public $messages = array();
    function __construct($settings = false){
        if(!empty($settings)){
            $this->settings = $settings;
        }
        $this->initSlim();
        $this->dbConnection();
    }
    public function debugSettings(){
        if(!empty($this->settings['slim'])){
            unset($this->settings['slim']);
            echo "<p>Slim Set</p>";
        } else {
            echo "<p>Slim Not Set</p>";
        }
        if(!empty($this->settings['settings']['encrypted_key'])){
            $this->settings['settings']['encrypted_key'] = "removed for privacy";
        }
        if(!empty($this->settings['settings']['encryptionIV'])){
            $this->settings['settings']['encryptionIV'] = "removed for privacy";
        }
        if(!empty($this->settings['settings']['password_salt'])){
            $this->settings['settings']['password_salt'] = "removed for privacy";
        }
        if(!empty($this->settings['settings']['dbserver'])){
            $this->settings['settings']['dbserver'] = "removed for privacy";
        }
        if(!empty($this->settings['settings']['dbuser'])){
            $this->settings['settings']['dbuser'] = "removed for privacy";
        }
        if(!empty($this->settings['settings']['dbpass'])){
            $this->settings['settings']['dbpass'] = "removed for privacy";
        }
        debug($this->settings);
    }
    public function debugMessages(){
        debug($this->messages, "Messages");
    }
    public function getVars($varname,$ext){
        $varvalues = $this->$varname;
        if(!empty($varvalues[$ext])){
            return $varvalues[$ext];
        }
    }
    //Initiate Slim into the Thing Engine
    function initSlim(){
        if(!class_exists('Slim')){
            $this->slim = new \Slim\Slim(array(
                'cookies.lifetime' => '2 days',
                'cookies.encrypt' => true,
                'cookies.secret_key' => $this->settings['encrypted_key'],
                'cookies.cipher' => MCRYPT_RIJNDAEL_256,
                'cookies.cipher_mode' => MCRYPT_MODE_CBC,

            ));
        } else {
            $this->slim = Slim::getInstance();
        }
    }
    function dbConnection(){
        if((!empty($settings['dbserver'])) && (!empty($settings['dbuser'])) && (!empty($settings['dbpass']))){
            // Create connection
            $this->dbConn = new mysqli($settings['dbserver'], $settings['dbuser'], $settings['dbpass']);
            // Check connection
            if ($this->dbConn->connect_error)
            {
                die("Connection failed: ". $this->dbConn->connect_error);
            }
        }
    }
    function cssToView(){
        if(!empty($this->settings['css'])){
            foreach($this->settings['css'] as $key=>$var){
                echo '<link href="'.$var.'" rel="stylesheet">';
            }
        }
    }
    function jsToView(){
        if(!empty($this->settings['js'])){
            foreach($this->settings['js'] as $key=>$var){
                echo '<script src="'.$var.'"></script>';
            }
        }
    }
    function getIpAddress(){
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
        {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        }
        else if(getenv('HTTP_X_FORWARDED_FOR'))
        {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        }
        else if(getenv('HTTP_X_FORWARDED'))
        {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        }
        else if(getenv('HTTP_FORWARDED_FOR'))
        {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        }
        else if(getenv('HTTP_FORWARDED'))
        {
            $ipaddress = getenv('HTTP_FORWARDED');
        }
        else if(getenv('REMOTE_ADDR')){
            $ipaddress = getenv('REMOTE_ADDR');
        }
        else{
            $ipaddress = 'UNKNOWN';
        }   
        return $ipaddress;
    }
    public function setEncrypt($pure_string = false, $encryption_key = false)
    {
        if (empty (trim($pure_string)))
        {
            return FALSE;
        }
        if ($encryption_key === FALSE)
        {
            $encryption_key = $this->settings['encrypted_key'];
        }
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $encryption_key);
        $secret_iv = $this->settings['encryptionIV'];
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($pure_string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }
    public function getDecrypt($encrypted_string = false, $encryption_key = false)
    {
        if (empty (trim($encrypted_string)))
        {
            return FALSE;
        }
        if (strlen($encrypted_string) < 12)
        {
            //return $encrypted_string;
        }
        if ($encryption_key === FALSE)
        {
            $encryption_key = $this->settings['encrypted_key'];
        }
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $encryption_key);
        $secret_iv = $this->settings['encryptionIV'];
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        try{
            $output = openssl_decrypt(base64_decode($encrypted_string), $encrypt_method, $key, 0, $iv);
        }
        catch (\Exception $e) {
            $output = $encrypted_string;
        }
        return $output;
    }
    function getRandomId($parts=1, $part_size=6){
        $id = date("YmdHis").$this->getRandomString($part_size);
        if($parts > 1){
            for ($i = 2; $i <= $parts; $i++) {
                $id .= "-".$this->getRandomString($part_size);
            }  
        }
        return $id;
    }
    function getRandomString($name_length = 6) {
        $alpha_numeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        return substr(str_shuffle(str_repeat($alpha_numeric,12)), 0, $name_length);
    }
    function dateConvert($date_to_convert, $date_format = "Y-m-d 00:00:00"){
        $formattedDate = date($date_format, strtotime($date_to_convert));
        return $formattedDate;
    }
    //   function setVar($varname,$value, $index= 0, $index2 = 0){
    //        parent::setExtensionVar($varname, $value);
    //    }
}



class thingEngineApi{

    public $apiKey;
    public $thingEngineURI;

    function __construct($settings = false)
    {
        $this->apiKey = $settings['apiKey'];
        $this->thingEngineURI = $settings['thingEngineURI'];
    }

    function apiPost($data = false, $function = false)
    {
        $data['apiKey'] = $this->apiKey;
        $data_string = json_encode($data);
        if(!empty($_COOKIE['PHPSESSID'])){
            $strCookie = 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/';
        } else {
            $strCookie = "";
        }
        session_write_close();
        $ch = curl_init($this->thingEngineURI.$function);    
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIE, $strCookie ); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
                   );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);
        return ($result);
    }
    function RandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring = $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    }

}
