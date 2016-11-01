<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile extends CI_Controller {
        
    public function __construct() {
        parent::__construct();
        $this->load->model('mobilemodel');
    }

    public function index()
    {

    }
	
    public function login()
    {       
        $userName = $this->input->get_post("username");
        $userPassword = $this->input->get_post("password");
        
        if(!$userName || !$userPassword)
        {
            echo json_encode(array('result' => 'fail' , 'reason' => 'emptydata'));
        }

        $result = $this->mobilemodel->get_user_all($userName,$userPassword);
        if(!$result){
                echo json_encode(array('result' => 'fail','reason'=>'invaliddata'));
                return;
        }
        $userID = $result -> id;
        $suitableParties = $this->mobilemodel->get_SuitableParties($userID);
        if(!$suitableParties){
                echo json_encode(array('result' => 'emptyparty', 'me' => $result));
                return;
        }
        echo json_encode(array('result' => 'success', 'party' => $suitableParties, 'me' => $result));
    }
	
    public function facebooklogin()
    {   
        $facebookID = $this->input->get_post("facebookid");
        $firstname = $this->input->get_post("firstname");
        $lastname = $this->input->get_post("lastname");
        $email = $this->input->get_post("email");
        $deviceToken = $this->input->get_post("devicetoken");
        $deviceType = $this->input->get_post("devicetype");

        $result = $this->mobilemodel->get_userwithfacebook($facebookID);
        $me = $result;

        if(!$result){
            $changedEmail =  str_replace("...", "@", $email);
                    $result1 = $this->mobilemodel->insert_facebook_user($facebookID, $firstname, $lastname, $changedEmail);
                    if(!$result1){
                            echo json_encode(array('result' => 'fail','reason'=>'invaliddata'));
                            return;
                    }
                    $result2 = $this->mobilemodel->get_useridWithFacebookID($facebookID);
                    if(!$result2){
                            echo json_encode(array('result' => 'fail','reason'=>'invaliddata'));
                            return;
                    } else {
                        $me = $result2;
                    }

        }
        $userID = $me->id;
        $img = $_FILES["photo"];
        $config = $this->set_photoUpload($userID);
        $fileName = $userID.'_photo.png';
        if(isset($img)){
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('photo'))
            {
                $result = $this->mobilemodel->update_user_photo($userID, $fileName);   
            }
        }

        $suitableParties = $this->mobilemodel->get_SuitableParties($userID);
        if(!$suitableParties){
            echo json_encode(array('result' => 'emptyparty', 'me' => $me));
            return;
        }
        echo json_encode(array('result' => 'success', 'party' => $suitableParties, 'me' => $me));

    }
        
    public function register()
    {
        $firstname = $this->input->get_post("firstname");
        $lastname = $this->input->get_post("lastname");
        $username = $this->input->get_post('username');
        $email = $this->input->get_post('email');
        $password = $this->input->get_post('password');
        $deviceToken = $this->input->get_post('devicetoken');
        $deviceType = $this->input->get_post('devicetype');

        $resultForUsername = $this->mobilemodel->check_username($username);
        if($resultForUsername){
                echo json_encode(array('result' => 'fail', 'reason' => 'sameusernameexist')); 
                return;
        }	

        $changedEmail =  str_replace("...", "@", $email); 
        $result = $this->mobilemodel->insert_user($firstname, $lastname, $changedEmail, $username, $password);
        if(!$result){
            echo json_encode(array('result' => 'fail','reason' => 'servererror'));
            return;
        }

        $userInfo = $this->mobilemodel->get_user_all($username, $password);
        $userID = $userInfo->id;

//        $this->load->library('upload');
//        $files = $_FILES;
//        $count = count($_FILES['profileimage']['name']);
//        for($i=0; $i<$count; $i++)
//        {
//            $_FILES['profileimage']['name']= $files['profileimage']['name'][$i];
//            $_FILES['profileimage']['type']= $files['profileimage']['type'][$i];
//            $_FILES['profileimage']['tmp_name']= $files['profileimage']['tmp_name'][$i];
//            $_FILES['profileimage']['error']= $files['profileimage']['error'][$i];
//            $_FILES['profileimage']['size']= $files['profileimage']['size'][$i];    
//            $this->upload->initialize($this->set_upload_options());
//            if($this->upload->do_upload() == False)
//            {
//                //error coming here
//                $this->load->view('profile_view');
//            }
//            else
//            {
//              // Insert Code here
//            }
//
//        }

        $files = $_FILES;
        $img = $_FILES["photo"];
        $coverPhotoImg = $_FILES["coverphoto"];
        $this->load->library('upload');
        
        //upload photo
       
        
        $fileName = $userID.'_photo.png';
        if(isset($img))
        {
            $_FILES['photo']['name']= $files['photo']['name'];
            $_FILES['photo']['type']= $files['photo']['type'];
            $_FILES['photo']['tmp_name']= $files['photo']['tmp_name'];
            $_FILES['photo']['error']= $files['photo']['error'];
            $_FILES['photo']['size']= $files['photo']['size'];  
            
            $this->upload->initialize($this->set_photoUpload($userID));
            if ($this->upload->do_upload('photo'))
            {
                $this->mobilemodel->update_user_photo($userID, $fileName);
            } else {
                echo json_encode(array('result' => 'fail', 'reason' => 'photouploadfail'));
                return;
            }
        }

        // upload cover photo
        $fileName = $userID.'_cover.png';
        
        if(isset($coverPhotoImg))
        {
            $_FILES['coverphoto']['name']= $files['coverphoto']['name'];
            $_FILES['coverphoto']['type']= $files['coverphoto']['type'];
            $_FILES['coverphoto']['tmp_name']= $files['coverphoto']['tmp_name'];
            $_FILES['coverphoto']['error']= $files['coverphoto']['error'];
            $_FILES['coverphoto']['size']= $files['coverphoto']['size'];  
            
            $this->upload->initialize($this->set_coverPhotoUpload($userID));
            if($this->upload->do_upload('coverphoto'))
            {
                $result = $this->mobilemodel->update_user_coverimage($userID, $fileName);
            }
        }
        $this->register_devicetoken($userID, $deviceToken, $deviceType);
        $this->login($username, $password);             	
    }
        
    private function set_photoUpload($userID){
        $fileName = $userID."_photo.png";
        $config['upload_path']          = './images/photo';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 5000;
        $config['max_width']            = 3000;
        $config['max_height']           = 3080;
        $config['file_name']            = $fileName;
        $config['overwrite']            = TRUE;

        return $config;
    }
        
    private function set_coverPhotoUpload($userID){
        $fileName = $userID."_cover.png";
        $config['upload_path']          = './images/cover';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 5000;
        $config['max_width']            = 3000;
        $config['max_height']           = 3080;
        $config['file_name']            = $fileName;
        $config['overwrite']            = TRUE;

        return $config;
}

    private function set_partyPhotoUpload($partyID){
        $fileName = $partyID."_party.png";
        $config['upload_path']          = './images/party';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 5000;
        $config['max_width']            = 3000;
        $config['max_height']           = 3080;
        $config['file_name']            = $fileName;
        $config['overwrite']            = TRUE;

        return $config;
    }
        
    public function change_profile()
    {
        $userID = $this->input->get_post("userid");
        $password = $this->input->get_post("password");
        $firstname = $this->input->get_post("firstname");
        $lastname = $this->input->get_post("lastname");
        $email = $this->input->get_post("email");
        $changedEmail =  str_replace("...", "@", $email);
        
        if(!$userID || !$password || !$firstname || !$lastname || !$email)
        {
            echo json_encode(array('result' => 'fail'));
            return;
        }
        
        $result = $this->mobilemodel->update_user($userID, $password, $firstname, $lastname, $changedEmail);
        if($result){
                echo json_encode(array( 'result' => 'success'));
        }else{
                echo json_encode(array( 'result' => 'fail'));
        }

        $photoImg = $_FILES['photo'];
        $coverPhotoImg = $_FILES['coverphoto'];

        if(isset($photoImg))
        {
            $config = $this->set_photoUpload($userID);
            $fileName = $userID.'_photo.png';
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('photo'))
            {
                $this->mobilemodel->update_user_photo($userID, $fileName);
            } else {

            }
        }            

        // upload cover photo
        $fileName = $userID.'_cover.png';
        if(isset($coverPhotoImg)){
            $this->set_coverPhotoUpload($userID);
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('coverphoto'))
            {
                $result = $this->mobilemodel->update_user_coverimage($userID, $fileName);
            }
        }
    }
	
    public function logout($userid){
        $this->load->model('mobilemodel');
        $result = $this->mobilemodel->update_user_online($userid);
        if($result){
                echo json_encode(array('result' => 'success'));
        }else{
                echo json_encode(array('result' => 'fail'));
        }
    }
	
//	public function register_devicetoken()
//	{		
//		$userID = $this->input->get_post('userid');
//		$deviceToken = $this->input->get_post('devicetoken');
//		$deviceType = $this->input->get_post('devicetype');
//		
//		$result = $this->mobilemodel->get_deviceToken_Foruserid($deviceToken);
//		$result1;
//		if($result){
//			$result1 = $this->mobilemodel->update_device_token($result->id, $userID);
//		}else{
//			$result1 = $this->mobilemodel->insert_device_token($userID, $deviceToken,$deviceType);
//		}
//		
//		if($result1){
//			echo json_encode(array( 'result' => 'success'));
//		}else{
//			echo json_encode(array( 'result' => 'fail'));
//		}
//	}
        
    private function register_devicetoken($userID, $deviceToken, $deviceType)
    {
        $result = $this->mobilemodel->get_deviceToken_Foruserid($deviceToken);
        $result1;
        if($result){
                $result1 = $this->mobilemodel->update_device_token($result->id, $userID);
        }else{
                $result1 = $this->mobilemodel->insert_device_token($userID, $deviceToken,$deviceType);
        }
        return $result1;
    }

    public function do_upload_coverimage($userID,$filename)
    {

    	$result = $this->mobilemodel->update_user_coverimage($userID, $filename);
		if(!$result){
			return;
		}
    		$config['upload_path']          = './images/cover';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 5000;
                $config['max_width']            = 3000;
                $config['max_height']           = 3080;
		$config['overwrite']            = TRUE;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload())
                {
                        $error = array('error' => $this->upload->display_errors());
						echo json_encode(array('result' => 'fail','error' => $error));
                }
                else
                {
                        $data = array('upload_data' => $this->upload->data());
						echo json_encode(array('result' => 'success'));

                }            
    }
	
    public function do_upload_photo($userID, $fileName)
    {
    	$result = $this->mobilemodel->update_user_photo($userID, $fileName);
		if(!$result){
			return;
		}
    		$config['upload_path']          = './images/photo';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 5000;
                $config['max_width']            = 5000;
                $config['max_height']           = 5000;
				$config['overwrite']            = TRUE;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload())
                {
                        $error = array('error' => $this->upload->display_errors());
						echo json_encode(array('result' => 'fail','error' => $error));
                }
                else
                {
                        $data = array('upload_data' => $this->upload->data());
						echo json_encode(array('result' => 'success'));
                }            
    }

    public function do_upload_partyimage($partyID, $filename){

    $result = $this->mobilemodel->update_party_picture($partyID, $filename);
            if(!$result){
                    return;
            }
                    $config['upload_path']          = './images/party';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 5000;
            $config['max_width']            = 5000;
            $config['max_height']           = 5000;
                            $config['overwrite']            = TRUE;

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload())
            {
                    $error = array('error' => $this->upload->display_errors());
                                            echo json_encode(array('result' => 'fail','error' => $error));
            }
            else
            {
                    $data = array('upload_data' => $this->upload->data());
                                            echo json_encode(array('result' => 'success'));
            }  
    }


    function getuserpartyinfo($userID){
            $this->load->model('mobilemodel');
            $result = $this->mobilemodel->get_user_partinfo($userID);
            if($result){
                    echo json_encode(array('result' => 'success','userdata' => $result));
            }else{
                    echo json_encode(array('result' => 'fail'));
            }
    }

    public function getuserfollowing($userID){
            $this->load->model('mobilemodel');
            $result = $this->mobilemodel->get_user_following($userID);
            if($result){
                    echo json_encode(array('result' => 'success','following' => $result));
            }else{
                    echo json_encode(array('result' => 'fail'));
            }
    }

    public function getuserfollower($userID){
            $this->load->model('mobilemodel');
            $result = $this->mobilemodel->get_user_follower($userID);
            if($result){
                    echo json_encode(array('result' => 'success','follower' => $result));
            }else{
                    echo json_encode(array('result' => 'fail'));
            }
    }

    public function getmyparty($userID){
            $this->load->model('mobilemodel');
            $resultStr;
            $resultForHost = $this->mobilemodel->get_hosted_myparty($userID);
            $resultForGuest = $this->mobilemodel->get_guested_myparty($userID);
            if($resultForHost){
                    if($resultForGuest){
                            echo json_encode(array('result' => 'success', 'hostparty' => $resultForHost, 'guestparty' => $resultForGuest));
                    }
                    else{
                            echo json_encode(array('result' => 'emptyguestparty', 'hostparty' => $resultForHost));
                    }
            }
            else{
                    if($resultForGuest){
                            echo json_encode(array('result' => 'emptyhostparty', 'guestparty' => $resultForGuest));
                    }
                    else{
                            echo json_encode(array('result' => 'emptyall'));
                    }
            }
    }

    public function getmypartiesforhost($userID){
            $this->load->model('mobilemodel');
            $result = $this->mobilemodel->get_user_partiesforhost($userID);
            if($result){
                    echo json_encode(array('result' => 'success','party' => $result));
            }else{
                    echo json_encode(array('result' => 'fail'));
            }
    }

    public function getmypartiesforguest($userID){
            $this->load->model('mobilemodel');
            $result = $this->mobilemodel->get_user_partiesforguest($userID);
            if($result){
                    echo json_encode(array('result' => 'success','party' => $result));
            }else{
                    echo json_encode(array('result' => 'fail'));
            }
    }

    // notification type : 0 - create a party 
    //                     1 - following you
    //                     2 - invited you
    public function sendAppleNotification($senderID, $photoURL, $partyID, $message, $notificationType, $deviceToken){
            /* We are using the sandbox version of the APNS for development. For production
             environments, change this to ssl://gateway.push.apple.com:2195 */
            $apnsServer = 'ssl://gateway.sandbox.push.apple.com:2195';
            /* Make sure this is set to the password that you set for your private key
             when you exported it to the .pem file using openssl on your OS X */
            $privateKeyPassword = 'rockzrockzrockz';
            /* Put your own message here if you want to */
            //$message = 'Welcome to iOS 7 Push Notifications';
            /* Pur your device token here */
            //$deviceToken = 'BA1A196A7DD63B5EE634BD83B338F366C19D47A64FE0759E9CFDF52E647475B2';
            /* Replace this with the name of the file that you have placed by your PHP
             script file, containing your private key and certificate that you generated
             earlier */
            $pushCertAndKeyPemFile = 'ck.pem';
            $stream = stream_context_create();
            stream_context_set_option($stream,
                                      'ssl',
                                      'passphrase',
                                      $privateKeyPassword);
            stream_context_set_option($stream,
                                      'ssl',
                                      'local_cert',
                                      dirname(__FILE__) .'/'.$pushCertAndKeyPemFile);
            $connectionTimeout = 10;
            $connectionType = STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT;
            $connection = stream_socket_client($apnsServer,
                                               $errorNumber,
                                               $errorString,
                                               $connectionTimeout,
                                               $connectionType,
                                               $stream);
            if (!$connection){
                //echo "Failed to connect to the APNS server. Error no = $errorNumber<br/>";
                exit;
            } else {
                //echo "Successfully connected to the APNS. Processing...</br>";
            }
            $messageBody['aps'] = array('alert' => $message,
                                        'sound' => 'default',
                                        'badge' => 1,
                                        'from'  => $senderID,
                                        'photo' => $photoURL,
                                        'pid'   => $partyID,
                                        'ntype' => $notificationType
                                        );
            $payload = json_encode($messageBody);
            $notification = chr(0) .
                            pack('n', 32) .
                            pack('H*', $deviceToken) .
                            pack('n', strlen($payload)) .
                            $payload;                              
            $wroteSuccessfully = fwrite($connection, $notification, strlen($notification));
            if (!$wroteSuccessfully){
                echo "Could not send the message<br/>";
            }
            else {
                echo "Successfully sent the message<br/>";
            }
            fclose($connection);
    }

    public function sendNotification($senderID, $partyID, $reciverID, $text, $notificationType){
            $result = $this->mobilemodel->get_userNameAndPhoto($senderID);
            if(!$result){
                    echo json_encode(array( 'result' => 'fail'));
                    return FALSE;
            } 
            $username = $result->firstname + $result->lastname + " ";
            $photoURL = $result->photo;
            $message = $username + $text;
            $result_deviceToken = $this->mobilemodel->get_deviceToken($reciverID);
            if(!$result_deviceToken){
                    echo json_encode(array( 'result' => 'fail', 'reason' => 'nodevicetoken'));
                    return FALSE;
            }
            if($result_deviceToken){
                    foreach ($result_deviceToken as $deviceTokenArr) { 
                            $deviceToken = $deviceTokenArr->devicetoken;
                            $deviceType  = $deviceTokenArr->devicetype;                
                            if($deviceType == 0) // device is ios device
                            {
                                    $this->sendAppleNotification($senderID, $photoURL, $partyID, $message, $notificationType, $deviceToken);
                            }
                            if($deviceType == 1) // device is android device
                            {

                            }
                    }
            }
    }

    public function create_party()
    {
            $userid = $this->input->get_post('userid');
            $name = $this->input->get_post('name');
            $address = $this->input->get_post('address');
            $longitude = $this->input->get_post('longitude');
            $latitude = $this->input->get_post('latitude');
            $date = $this->input->get_post('date');
            $starttime = $this->input->get_post('starttime');
            $endtime = $this->input->get_post('endtime');
            $hosts = $this->input->get_post('host');
            $partytype = $this->input->get_post('type');
            $partystatus = $this->input->get_post('status');
            $partyroll = $this->input->get_post('roll');
            $partyfee = $this->input->get_post('fee');
            
            if(!$userid || !$name || !$address ||!$longitude || !$latitude){
                echo json_encode(array('result' => 'fail'));
                return;
            }

            // parse host
            $hostArray = explode(',', $hosts);
            $failedlist = array();
            $invitelist = array();

            $result = $this->mobilemodel->insert_party($userid, $name, $address, $longitude, $latitude, $date, $starttime, $endtime, $partytype, $partystatus, $partyroll, $partyfee);
            if(!$result){
                echo json_encode(array('result' => 'fail'));
                return;
            }
            
            $party = $result;
            foreach ($hostArray as $hostID) 
            {
                $result1 = $this->mobilemodel->insert_host($userid, $party->id, $hostID);
                if($result1){
                    $invitelist[] = $hostID;
                }
                else{
                    $failedlist[] = $hostID;
                }
            }
            
            $partyImg = $_FILES['partyimage'];

            if(isset($partyImg))
            {
                $config = $this->set_partyPhotoUpload($party->id);
                $fileName = $party->id.'_party.png';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('partyimage'))
                {
                    $result = $this->mobilemodel->update_party_picture($party->id, $fileName);
                } else {

                }
            }    
            echo json_encode(array('result' => 'success','party' => $party));
    }

    public function search_user(){
            $this->load->model('mobilemodel');

            $id = $this->input->get_post('userid');
            $text = $this->input->get_post('text');

            $result = $this->mobilemodel->get_user_searchText($id, $text);
            if($result){
                    echo json_encode(array('result' => 'success','users' => $result));
            }else{
                    echo json_encode(array('result' => 'fail'));
            }
    }

    public function get_invite_list(){
            $this->load->model('mobilemodel');

            $userID = $this->input->get_post('userid');
            $result = $this->mobilemodel->get_users_forInvite($userID);
            if($result){
                    echo json_encode(array('result' => 'success', 'users' => $result));
            }else{
                    echo json_encode(array('result' => 'fail'));
            }
    }

    public function get_invitelist_someparty(){
            $this->load->model('mobilemodel');

            $userID = $this->input->get_post('userid');
            $partyID = $this->input->get_post('partyid');
            $mainHostID = $this->input->get_post('mainhostid');

            $result = $this->mobilemodel->get_users_forInvite_toparty($userID, $partyID, $mainHostID);
            if($result){
                    echo json_encode(array('result' => 'success', 'users' => $result));
            }else{
                    echo json_encode(array('result' => 'fail'));
            }
    }

    public function invite_someone(){
            $this->load->model('mobilemodel');

            $mainhostID = $this->input->get_post('userid');
            $partyID = $this->input->get_post('partyid');
            $guests = $this->input->get_post('guestids');
            $guestArray = explode(',', $guests);
            $failedlist = array();
            $invitelist = array();
            foreach ($guestArray as $guestID) {
                    $result = $this->mobilemodel->insert_guest($mainhostID, $partyID, $guestID);
                    if($result){
                            $invitelist[] = $guestID;
                    }else{
                            $failedlist[] = $guestID;
                    }
            }
            echo json_encode(array('result' => 'success', 'failedlist' => $failedlist, 'invitedlist' => $invitelist));
    }

    public function invite_someone_publicparty(){
            $this->load->model('mobilemodel');

            $mainhostID = $this->input->get_post('userid');
            $partyID = $this->input->get_post('partyid');
            $guests = $this->input->get_post('guestids');
            $guestArray = explode(',', $guests);
            $failedlist = array();
            $invitelist = array();
            $attenderlist = array();
            foreach ($guestArray as $guestID) { 
                    $result_exist_guest = $this->mobilemodel->get_guest($guestID, $partyID, $mainhostID); 
                    if(!$result_exist_guest){
                            $result = $this->mobilemodel->insert_guest_allow_0($guestID, $partyID, $mainhostID);
                            if($result){
                                    $invitelist[] = $guestID;
                            }else{
                                    $failedlist[] = $guestID;
                            }
                    }
                    $queryForAddedAttender = "SELECT users.id, users.firstname, users.lastname, users.photo, users.coverphoto FROM users WHERE users.id = (SELECT guest.guestid FROM guest WHERE guest.partyid = '".$partyID."' AND guest.guestid = '".$guestID."' AND guest.mainhostid = '".$mainhostID."')";
                    $resultForAttender = $this->db->query($queryForAddedAttender);
                    if($resultForAttender && $resultForAttender->num_rows() > 0){
                            $attenderlist[] = $resultForAttender->row();
                    }

            }
            echo json_encode(array('result' => 'success', 'failedlist' => $failedlist, 'invitedlist' => $invitelist, 'attender' => $attenderlist));

            //send invite push notification 
            $this->send_Invite_PushNotification($mainhostID, $partyID, $guestArray);
    }

    public function invite_someone_publicparty_as_guest(){
            $mainhostID = $this->input->get_post('userid');
            $partyID = $this->input->get_post('partyid');
            $guests = $this->input->get_post('guestids');
            $guestArray = explode(',', $guests);
            $this->send_Invite_PushNotification($mainhostID, $partyID, $guestArray);
    }

    public function send_Invite_PushNotification($mainhostID, $partyID, $guestArray){
            $this->load->model('mobilemodel');		
            $result = $this->mobilemodel->get_partyName($partyID);
            if(!$result){
                    echo json_encode(array( result => 'fail')); 
                    return;
            }	
            $partyName = $result->name;
            $text = "has requested invite to".$partyName; 
            foreach ($guestArray as $guestID) {
                    $this->sendNotification($mainhostID, $partyID, $guestID, $text, 2);
            }

    }

    public function get_host_list(){

    }

    public function unfollow_someone(){
            $this->load->model('mobilemodel');

            $useerID = $this->input->get_post('userid');
            $unfollowID = $this->input->get_post('unfollowid');

            $result = $this->mobilemodel->delete_guest($useerID, $unfollowID);
            if($result)
            {
                    echo json_encode(array( 'result' => 'success'));
            }else{
                    echo json_encode(array( 'result' => 'fail'));
            }
    }

    public function allow_following_me(){
            $this->load->model('mobilemodel');

            $userID = $this->input->get_post('userid');
            $allowID = $this->input->get_post('allowid');

            $result = $this->mobilemodel->update_guest_allow($userID, $allowID);
            if($result){
                    echo json_encode(array( 'result' => 'success'));
            }else{
                    echo json_encode(array( 'result' => 'fail'));
            }
    }

    public function delete_party(){
            $this->load->model('mobilemodel');

            $userID = $this->input->get_post('userid');
            $partyID = $this->input->get_post('partyid');

            $result = $this->mobilemodel->delete_party($userID, $partyID);
            if($result){
                    echo json_encode(array( 'result' => 'success'));
            }else{
                    echo json_encode(array( 'result' => 'fail'));
            }
    }

    public function attend_publicparty(){
            $this->load->model('mobilemodel');

            $userID = $this->input->get_post('userid');
            $partyID = $this->input->get_post('partyid');
            $hostID = $this->input->get_post('hostid');

            $result_exist_party = $this->mobilemodel->get_party($partyID);
            if(!$result_exist_party){
                    echo json_encode(array( 'result' => 'failed', 'reason' => 'noexistparty'));	
                    return;
            }

            $result_exist_guest = $this->mobilemodel->get_guest($userID, $partyID, $hostID);
            if($result_exist_guest){
                    echo json_encode(array( 'result' => 'success' ));
                    //return;
            }

            $result = $this->mobilemodel->insert_guest_allow_0($userID, $partyID, $hostID);
            if ($result){
                    echo json_encode(array( 'result' => 'success' ));
            } else{
                    echo json_encode(array( 'result' => 'fail', 'reason' => 'failedtoquery'));
            }

            $this->sendNotification($userID, $partyID, $hostID, "is following you.", 1);		
    }
}


?>