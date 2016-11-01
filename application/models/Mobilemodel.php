<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mobilemodel extends CI_Model {


    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db = $this->load->database();
    }
	
	function get_user_all($username,$userPassword){
		$query = "SELECT `id`, `firstname`, `lastname`, `username`, `email`, `password`, `photo`, `coverphoto`,`facebookid` FROM `users` WHERE `username` = '" .$username."' AND `password` = '" .$userPassword. "' ";
		$result = $this->db->query($query);
		if($result && ($result->num_rows() > 0)){
			return $result->row();
		}
		else{
			return FALSE;
		}
	}
	
	function get_userwithfacebook($facebookID){
		$query = "SELECT * FROM users WHERE users.facebookid = '".$facebookID."'";
		$result = $this->db->query($query);
		if($result && ($result->num_rows() > 0)){
			return $result->row();
		}
		else{
			return FALSE;
		}
	}
    
	function insert_facebook_user($facebookID, $firstname, $lastname, $email){
		$query = "INSERT INTO `rockz`.`users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `photo`, `coverphoto`, `online`, `facebookid`) VALUES (NULL, '".$firstname."', '".$lastname."', '', '".$email."', '', '', 'default_cover.png', '', '".$facebookID."')";
		$result = $this->db->query($query);
		if($result){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	function get_useridWithFacebookID($facebookID){
		$query = "SELECT * FROM users WHERE users.facebookid = '".$facebookID."'"; 
		$result = $this->db->query($query);
		if($result && ($result->num_rows() > 0)){
			return $result->row(); 
		}
		else{
			return FALSE; 
		}  echo var_dump($result->row()->id);
	}
	
	function check_username($username){
		$query = "SELECT users.id  FROM users WHERE users.username = '".$username."'";
		$result = $this->db->query($query);
		if($result->num_rows() > 0){
			return true;
		}
		else{
			return FALSE;
		}
	}
	
	function insert_user($firstname,$lastname,$email,$username,$password)
	{
		$query = "INSERT INTO `rockz`.`users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `photo`, `online`) VALUES (NULL, '" .$firstname. "', '" .$lastname. "', '" .$username. "', '" .$email. "', '" .$password. "','', '1')";
		$result = $this->db->query($query);
		return $result;
	}
	
	function update_user($userid, $password, $firstname, $lastname, $email){
		$query = "UPDATE `users` SET `firstname`='".$firstname."',`lastname`='".$lastname."',`email`= '".$email."',`password`='".$password."'  WHERE users.id = ".$userid;
		$result = $this->db->query($query);
		return $result;
	}
	
	function update_user_photo($userID,$filename){
		$query = "UPDATE `users` SET `photo`='".$filename."' WHERE users.id = '".$userID."'";
		$result = $result = $this->db->query($query);
		if($result){
			return TRUE;
		}else{
			return FALSE;	
		}
	}
	
	function update_user_coverimage($userID,$filename){
		$query = "UPDATE `users` SET users.coverphoto ='".$filename."' WHERE users.id = '".$userID."'";
	    $result = $this->db->query($query);
		if($result){
			return TRUE;
		}else{
			return FALSE;	
		}
	}
	
	function update_party_picture($partyID, $filename){
		$query = "UPDATE `party` SET party.picture ='".$filename."' WHERE party.id = ".$partyID;
		$result = $this->db->query($query);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	function update_user_online($userid){
		$query = "UPDATE `users` SET `online`= 0 WHERE `id` = '" . $userid ."'";
		echo $query;
		if($this->db->query($query)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	function get_facebookuser_userid($facebookID, $firstname, $lastname, $email){
		$query = "SELECT `id` FROM `users` WHERE `facebookid` = '" .$facebookID. "'";
		$userid = $this->db->query($query);
		if($userid){
			if($userid->num_rows() > 0){
				return $userid;
			}
			else{
				return $this->insert_facebookuser($firstname, $lastname, $email, $facebookID);
			}
		}
		else{
			return FALSE;
		}
	}
	
	function insert_device_token($userID, $deviceToken,$deviceType){
		$query = "INSERT INTO `rockz`.`device_token` (`id`, `userid`, `devicetoken`, `devicetype`) VALUES (NULL, '" .$userID. "', '" .$deviceToken."', '" .$deviceType. "');";
		if($this->db->query($query)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	function get_deviceToken_Foruserid($deviceToken){
		$query = "SELECT device_token.id FROM `device_token` WHERE device_token.devicetoken = '".$deviceToken."'";
		$result = $this->db->query($query); 
		if($result && $result->num_rows() > 0){
			return $result->row();
		}else{
			return FALSE;
		}
	}
	
	function update_device_token($id, $userID){
		$query = "UPDATE `device_token` SET `userid`= '".$userID."'  WHERE device_token.id = ".$id;
		$result = $this->db->query($query);
		if($result){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function get_SuitableParties($userID){
		$allArray = array();
		// find all public party's id
		$query = "SELECT `id` FROM `party` WHERE  party.status = 0 OR (party.status = 1 AND party.id = ANY( SELECT guest.partyid FROM guest WHERE guest.guestid = ".$userID." )) OR (party.id = 1 AND party.id = ANY(SELECT host.partyid FROM host WHERE host.hostid = ".$userID." )) OR party.mainhost = ".$userID." OR (party.status = 2 AND party.id = ANY(SELECT guest.partyid FROM guest WHERE guest.guestid = ".$userID.")) OR (party.id = 2 AND party.id = ANY(SELECT host.partyid FROM host WHERE host.hostid = ".$userID." ))";
		$result = $this->db->query($query);
		if($result && ($result->num_rows() > 0)){
			foreach($result->result() as $row){
				$query1 = "SELECT *, party.id AS 'partyid',(SELECT COUNT(*) FROM `guest` WHERE guest.partyid = ".$row->id.") AS 'guestsnumber',(SELECT COUNT(*) FROM `host` WHERE host.partyid = ".$row->id.") AS 'hostsnumber' FROM party LEFT JOIN users on party.mainhost = users.id WHERE party.id = ". $row->id;
				$result1 = $this->db->query($query1);
				$queryForAttendList = "SELECT users.id, guest.allow, users.firstname, users.lastname FROM `guest` LEFT JOIN `users` ON guest.guestid = users.id WHERE guest.partyid = ".$row->id;
				$result2 = $this->db->query($queryForAttendList);
				$queryForHostList = "SELECT users.id, host.allow, users.firstname, users.lastname FROM host LEFT JOIN users on host.hostid = users.id WHERE host.partyid = ".$row->id;
				$resultForHostList = $this->db->query($queryForHostList);
				$subarr = array('main' => $result1->row(), 'attend' => $result2->result(), 'hosts' => $resultForHostList->result());
				$allArray[] = $subarr;
			}
			return $allArray;
		}else{
			return FALSE;
		}
	}
	
	function get_user_partinfo($userID){
		$allArray = array();
		$query = "SELECT users.id, users.firstname, users.lastname, (SELECT COUNT(*) FROM party WHERE party.mainhost = ".$userID.") AS 'parties', (SELECT COUNT(*) FROM guest WHERE guest.mainhostid = ".$userID.") AS 'following', (SELECT COUNT(*) FROM guest WHERE guest.guestid = ".$userID.") AS 'followers' FROM users WHERE users.id = ".$userID;
		$result = $this->db->query($query);
		$query1 = "SELECT party.id, party.picture FROM party WHERE party.mainhost =".$userID;
		$result1 = $this->db->query($query1);
		$query2 = "SELECT party.id, party.picture FROM guest LEFT JOIN party on guest.partyid = party.id WHERE guest.guestid = ".$userID;
		$result2 = $this->db->query($query2);

		if($result && ($result->num_rows() > 0)){
			$allArray[] = array( 'main' =>$result->result(),'hostparty' => $result1->result(),'guestparty' => $result2->result());
			return $allArray;
		}else{
			return FALSE;
		}
	}
	 //you must modify following 2 function 
	function get_user_following($userID){
		$query = "SELECT users.id, users.firstname, users.lastname, users.email, users.photo, users.coverphoto, users.facebookid FROM guest LEFT JOIN users ON guest.mainhostid = users.id WHERE guest.guestid = ".$userID." GROUP BY users.id";
		$result = $this->db->query($query);
		if($result && $result->num_rows() > 0){
			return $result->result();
		}
		else{
			return FALSE;
		}
	}
	function get_user_follower($userID){
		$query = "SELECT users.id, users.firstname, users.lastname, users.email, users.photo, users.coverphoto, users.facebookid, guest.allow FROM guest LEFT JOIN users ON guest.guestid = users.id WHERE guest.mainhostid = ".$userID." AND (guest.allow = 0 OR guest.allow = 2) GROUP BY users.id";
		$result = $this->db->query($query);
		if($result && $result->num_rows() > 0){
			return $result->result();
		}
		else{
			return FALSE;
		}
	}
	
	function get_user_partiesforhost($userID){
		$query = "SELECT * FROM party WHERE party.mainhost = ".$userID;
		$result = $this->db->query($query);
		if($result && $result->num_rows() > 0){
			return $result->result();
		}
		else{
			return FALSE;
		}
	}
	
	function get_user_partiesforguest($userID){
		$query = "SELECT users.id, users.firstname, users.lastname, users.email, users.photo, users.coverphoto, users.facebookid FROM guest LEFT JOIN users ON guest.mainhostid = users.id WHERE guest.guestid = ".$userID;
		$result = $this->db->query($query);
		if($result && $result->num_rows() > 0){
			return $result->result();
		}
		else{
			return FALSE;
		}
	}
	
	function get_hosted_myparty($userID){
		$query = "SELECT party.id FROM party WHERE party.mainhost = " .$userID;
		$partyICreated = $this->db->query($query);
		$query1 = "SELECT host.partyid FROM host WHERE host.hostid = " .$userID;
		$partyIamHost = $this->db->query($query1);
		$allhostedpartyIDs = array();
		foreach ($partyICreated->result() as $row) {
			$allhostedpartyIDs[] = $row->id;
		}
		foreach ($partyIamHost->result() as $row){
			$allhostedpartyIDs[] = $row->id;
		}
		$allHostedParties = array();
		foreach ($allhostedpartyIDs as $row){
			$partyArr;
			$queryForParty = "SELECT  users.id, users.firstname, users.lastname, users.photo , party.id AS 'partyid', party.name, party.date, party.starttime, party.endtime, party.status, party.type, party.picture, (SELECT COUNT(*) FROM host WHERE host.partyid = ".$row.") AS 'hostnum', (SELECT COUNT(*) FROM guest WHERE guest.partyid = ".$row.") AS 'attenderNum' FROM users LEFT JOIN party ON users.id = party.mainhost WHERE party.id = ".$row;
			$partyInfo = $this->db->query($queryForParty);
			if($partyInfo){
				$queryForAttender = "SELECT users.id, users.photo, users.firstname, users.lastname , guest.allow FROM guest LEFT JOIN users ON guest.guestid = users.id   WHERE guest.partyid = ".$row;
				$atteners = $this->db->query($queryForAttender);
				$partyArr = array('main' => $partyInfo->result(), 'attender' => $atteners->result());
				$allHostedParties[] = $partyArr;
			}
		}
		return $allHostedParties;
	}
	
	function get_guested_myparty($userID){
		$query = "SELECT guest.partyid FROM guest WHERE guest.guestid = " .$userID;
		$partyIDs = $this->db->query($query);
		if($partyIDs && $partyIDs->num_rows() > 0){
		}
		else{
			return FALSE;
		}
		$allGuestedParties = array();
		foreach ($partyIDs->result() as $row) {
			$partyArr;
			$query1 = "SELECT  users.id, users.firstname, users.lastname, users.photo , party.id AS 'partyid', party.name, party.date, party.starttime, party.endtime, party.status, party.type, party.picture, (SELECT COUNT(*) FROM host WHERE host.partyid = ".$row->partyid.") AS 'hostnum', (SELECT COUNT(*) FROM guest WHERE guest.partyid = ".$row->partyid.") AS 'attenderNum' FROM users LEFT JOIN party ON users.id = party.mainhost WHERE party.id = ".$row->partyid;
			$partyInfo = $this->db->query($query1);
			$queryForAttender = "SELECT users.id, users.photo, users.firstname, users.lastname , guest.allow FROM guest LEFT JOIN users ON guest.guestid = users.id   WHERE guest.partyid = ".$row->partyid;
			$atteners = $this->db->query($queryForAttender);
			$partyArr = array('main' => $partyInfo->result(), 'attender' => $atteners->result());
			$allGuestedParties[] = $partyArr;
		}
		return $allGuestedParties;
	}

	function get_user_searchText($id, $text){
		$query = "SELECT users.id, users.photo, users.coverphoto, users.firstname, users.lastname FROM users WHERE users.id != ".$id." AND ( users.firstname LIKE '%{$text}%' OR users.lastname LIKE '%{$text}%' ) ORDER BY users.firstname ASC ";
		//echo $query;
		$result = $this->db->query($query);
		if($result){
			return $result->result();
		}else{
			return false;
		}
	}
	
	function insert_party($id, $name, $address, $longitude, $latitude, $date, $starttime, $endtime, $partytype, $partystatus, $partyroll, $partyfee){
		$query = "INSERT INTO `rockz`.`party` (`id`, `name`, `address`, `longitude`, `latitude`, `date`, `starttime`, `endtime`, `mainhost`, `status`, `type`, `comment`, `price`, `picture`) VALUES (NULL, '".$name."', '".$address."', '".$longitude."', '".$latitude."', '".$date."', '".$starttime."', '".$endtime."', '".$id."', '".$partystatus."', '".$partytype."', '".$partyroll."', '".$partyfee."', 'default_party.png');";
		$result = $this->db->query($query);
		if($result){
			$getIDQuery = "SELECT MAX(party.id) AS 'partyid' FROM party;";
			$id = $this->db->query($getIDQuery);
			$partyid = $id->row()->partyid;
			$resultForParty = $this->db->query("SELECT * FROM party WHERE party.id = ".$partyid);
			return $resultForParty->row();
		}else{
			return FALSE;
		}
	}
	
	function get_users_forInvite($userID){
		$query = "SELECT users.id, users.firstname, users.lastname, users.photo FROM users WHERE users.id != ".$userID;
		$result = $this->db->query($query);
		if($result && $result->num_rows() > 0){
			return $result->result();
		}else{
			return false;
		}
	}
	
	function get_users_forInvite_toparty($userID, $partyID, $mainHostID){
		$query = "SELECT users.id, users.firstname, users.lastname, users.photo FROM users WHERE users.id NOT IN(SELECT guest.guestid FROM guest WHERE guest.partyid = ".$partyID.") AND users.id NOT IN(SELECT host.hostid FROM host WHERE host.partyid = ".$partyID.") AND users.id != ".$userID." AND users.id != ".$mainHostID;
		$result = $this->db->query($query);
		if($result && $result->num_rows() > 0){
			return $result->result();
		}else{
			return false;
		}
	}
	
	function insert_guest($mainhostID, $partyID, $guestID){
		$query = "INSERT INTO `rockz`.`guest` (`id`, `partyid`, `guestid`, `mainhostid`, `allow`) VALUES (NULL, '".$partyID."', '".$guestID."', '".$mainhostID."', '1');";
		$result = $this->db->query($query);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	function insert_host($id, $partyID, $hostID){
		$query = "INSERT INTO `rockz`.`host` (`id`, `partyid`, `mainhostid`, `hostid`, `allow`) VALUES (NULL, '".$partyID."', '".$id."', '".$hostID."', '1');";
		$result = $this->db->query($query);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	function delete_guest($userID, $unfollowID){
		$query = "DELETE FROM `rockz`.`guest` WHERE `guest`.`mainhostid` = ".$unfollowID." AND `guest`.`guestid` = ".$userID;
		$result = $this->db->query($query);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	function update_guest_allow($userID, $allowID){
		$query = "UPDATE `guest` SET `allow`= 0 WHERE ( guest.guestid = ".$allowID." AND guest.mainhostid = ".$userID." AND guest.allow = 2 )";
		$result = $this->db->query($query);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	function delete_party($userID, $partyID){
		$query = "DELETE FROM `rockz`.`party` WHERE `party`.`id` = ".$partyID." AND `party`.`mainhost` = ".$userID;
		$result = $this->db->query($query);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	function insert_guest_allow_0($userID, $partyID, $hostID){
		$query = "INSERT INTO `rockz`.`guest` (`id`, `partyid`, `guestid`, `mainhostid`, `allow`) VALUES (NULL, '".$partyID."', '".$userID."', '".$hostID."', '0')";
		$result = $this->db->query($query);
		if($result){
			return true;
		}else{
			return false;
		}
		
	}
	
	function get_guest($userID, $partyID, $hostID){ 
		$query = "SELECT guest.id FROM guest WHERE guest.partyid = '".$partyID."' AND guest.guestid = '".$userID."' AND guest.mainhostid = '".$hostID."'"; 
		$result = $this->db->query($query); 
		if($result && $result->num_rows() > 0){ 
			return true;
		}else{
			return false;
		}
	}
	
	function get_party($partyID){
		$query = "SELECT party.id FROM party WHERE party.id = ".$partyID;
		$result = $this->db->query($query); 
		if ($result && $result->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function get_deviceToken($userID){
		$query = "SELECT device_token.devicetoken, device_token.devicetype FROM device_token WHERE device_token.userid = ".$userID;
		$result = $this->db->query($query);
		if($result && $result->num_rows() > 0){ 
			return $result->result(); 
		}else{
			return false;
		}
	}
	
	function get_userNameAndPhoto($userID){
		$query = "SELECT users.firstname, users.lastname, users.photo FROM users WHERE users.id = ".$userID;
		$result = $this->db->query($query);
		if($result && $result->num_rows() > 0){
			return $result->row();
		}else{
			return false;
		}
	}
	
	function get_partyName($partID){
		$query = "SELECT party.name FROM party WHERE party.id = ".$partID;
		$result = $this->db->query($query);
		if($result && $result->num_rows() > 0){
			return $result->row();
		}else{
			return false;
		}
	}
}

?>