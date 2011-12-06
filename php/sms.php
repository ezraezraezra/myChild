<?php

require "twilio/Services/Twilio.php";

class SMS {
	
	var $AccountSid;
	var $AuthToken;
	
	function SMS($sid, $token) {
		$this->AccountSid = $sid;
		$this->AuthToken = $token;
		
		$this->client = new Services_Twilio($this->AccountSid, $this->AuthToken);
	}
	
	function confirmRegistration($to_number) {
		$confirm_message = "myChild: You have been registered and will receive an SMS weekly";
		$sms = $this->client->account->sms_messages->create("415-599-2671", $to_number, "$confirm_message");
	}
}

?>