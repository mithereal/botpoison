<?php
class controller{
	public $target; 
	public $settings;
	
	public function controller(){
		require 'config.php';
		$this->settings=$settings;
		$this->target = $_SERVER['REMOTE_ADDR'];
	}
	
	public function targetValid($target=null){
		$target=$this->target;
		if ((!$target) || (!preg_match("/^[\w\d\.\-]+\.[\w\d]{1,4}$/i", $this->target))) { 
			$this->message("Error: You did not specify a valid target host or IP.");
			$isvalid=false;
		}else{
			$isvalid=true;
		}
	return isvalid;
	}
	
	public function arin($target=null,$msg=null) {
	//global $msg, $target;
	$target=$this->target;
	$server = "whois.arin.net";
	if (!$target = gethostbyname($this->target)) {
		$msg .= "Can't IP Whois without an IP address.";
	} else {
		if (! $sock = fsockopen($server, 43, $num, $error, 20)) {
			unset($sock);
			$msg .= "Timed-out connecting to $server (port 43).";
		} else {
			fputs($sock, "$target\n");
			while (!feof($sock))
			$buffer .= fgets($sock, 10240); 
			fclose($sock);
		}
		if (eregi("RIPE.NET", $buffer)) {
			$nextServer = "whois.ripe.net";
		} else if (eregi("whois.apnic.net", $buffer)) {
			$nextServer = "whois.apnic.net";
		} else if (eregi("nic.ad.jp", $buffer)) {
			$nextServer = "whois.nic.ad.jp";
			$extra = "/e"; // suppress JaPaNIC character output
		} else if (eregi("whois.registro.br", $buffer)) {
			$nextServer = "whois.registro.br";
		}
		if ($nextServer) {
			$buffer = "";
			message("Deferred to specific whois server: $nextServer...");
			if (! $sock = fsockopen($nextServer, 43, $num, $error, 10)) {
				unset($sock);
				$msg .= "Timed-out connecting to $nextServer (port 43)";
			} else {
				fputs($sock, "$target$extra\n");
				while (!feof($sock))
				$buffer .= fgets($sock, 10240);
				fclose($sock);
			}
		}
		$msg .= nl2br($buffer);
	}
	$msg = trim(ereg_replace('#', '', strip_tags($msg)));
	$this->message($msg);
}

	public function message($msg) {
	//global $msg, $target;
	$timestamp = time();
	echo "\t\t\t" . "<h3>Your IP Address is " . $this->target . "</h3>" . "\n";
	echo "\t\t\t" . "<pre>WHOIS Lookup for " . $this->target . "\n" . date("l, F jS Y @ H:i:s", $timestamp) . "\n\n" . $msg . "</pre>" . "\n";
	flush();
	}
	
	public function sendEmail()
	{
	$tmestamp  = time();
	$datestamp = date("l, F jS Y @ H:i:s", $tmestamp);
	$sender    = "$this->settings['emailsender']";
	$recipient = "$this->settings['alertemail']";
	$subject   = "Bad Bot Alert!";
	$message   = $datestamp . "\n\n";
	$message  .= "URL Request: " . $_SERVER['REQUEST_URI'] . "\n";
	$message  .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
	$message  .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n\n";
	$message  .= "Whois Lookup: " . "\n";
	$message  .= "\n" . $msg . "\n";
	mail($recipient, $subject, $message, "From: $sender"); // send email
	}


}
?>
