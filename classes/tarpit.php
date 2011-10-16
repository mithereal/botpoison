<?php
 /*
Title: Php-labrea
Description: Automatically trap and block bots that don't obey robots.txt rules
Project URL: http://code.google.com/p/php-labrea/
Author: Jason Clark, aka mithereal
Release: Oct 8th, 2011
Version: 1.0

Credits: The Blackhole includes customized/modified versions of these fine scripts:
 - Network Query Tool @ http://www.drunkwerks.com/docs/NetworkQueryTool/
 - Kloth.net Bot Trap @ http://www.kloth.net/internet/bottrap.php
 * perishablepress.com @ http://perishablepress.com/press/2010/07/14/blackhole-bad-bots/
 */

class tarpit{

public $target; 	
public $settings;
//private $DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];

	
	public function tarpit(){
		require 'includes/config.php'; 
		$this->settings=$settings;
		$this->target = $_SERVER['REMOTE_ADDR'];
	}
	
	/*@return bool
	 * This function will determine weather the ip is vaid
	 */
	public function targetValid($target=null){
	$target=$this->target;
		if ((!$target) || (!preg_match("/^[\w\d\.\-]+\.[\w\d]{1,4}$/i", $this->target))) { 
			$this->message("Error: You did not specify a valid target host or IP.");
			$isvalid=false;
		}else{
			$isvalid=true;
		}
	return $isvalid;
	}
	
	/* @return bool
	 * This function will determine weather the user is a known bot (ie already in the database)
	 */
	public function isBot($type=null){
	$badbot = null; // set default value
	$filename =  $this->settings['blacklistfile']; // scan to prevent duplicates
	$fp = fopen($filename, "r") or die("\t\t\t<p>Error opening file...</p>\n\t\t</div>\n\t</body>\n</html>");
	while ($line = fgets($fp)) {
		if (!preg_match("/(googlebot|slurp|msnbot|teoma|yandex)/i", $line)) {
			$u = explode(" ", $line);
			if ($u[0] == $_SERVER['REMOTE_ADDR'])
			{
			 $badbot++;
			}
		}
	}
	fclose($fp);
	return $badbot;
	}
	
	/* @return bool
	 * This function will determine weather the user is a known bot (ie already in the database)
	 */
	public function isBot2($type=null){
		
	}
	
	/* @return bool
	 * This function will add a ip address to the database
	 */
	public function addBot()
	{
		$success=null;
	$tmestamp  = time();
	$datestamp = date("l, F jS Y @ H:i:s", $tmestamp);
	$filename =  $this->settings['blacklistfile'];
	$fp = fopen($filename, 'a+'); // append to blacklistfile
	fwrite($fp, $_SERVER['REMOTE_ADDR'] ." - ". $_SERVER['REQUEST_METHOD'] ." - ". $_SERVER['SERVER_PROTOCOL'] ." - ". $datestamp ." - ". $_SERVER['HTTP_USER_AGENT'] ."\n");
	$succerss=true;
	fclose($fp);
	return $success;
	}
	
	/* @return string
	 * This function grabs whoid query data
	 */
	public function arin($target=null,$msg=null) {
	$target=$this->target;
	$server = "whois.arin.net";
	if (!$target = gethostbyname($this->target)) {
		$msg .= "Can't IP Whois without an IP address.";
	} else {
		$sock=null;
		$buffer=null;
		$nextServer=null;

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

	/* @return string
	 * This function will format a string for email sending
	 */
	public function message($msg) {
	$timestamp = time();
	$message= "\t\t\t" . "<h3>Your IP Address is " . $this->target . "</h3>" . "\n";
	$message .= "\t\t\t" . "<pre>WHOIS Lookup for " . $this->target . "\n" . date("l, F jS Y @ H:i:s", $timestamp) . "\n\n" . $msg . "</pre>" . "\n";
	return $message;
	}
	
	/* @return bool;
	 * This function will send email to your alertemail address as set in the config
	 */
	public function sendEmail($msg=null)
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
	$success=mail($recipient, $subject, $message, "From: $sender"); // send email
	return $success;
	}
	
	/*@return bool
	 * This function will clear one ip address from the database (pit)
	 */
	public function touch()
	{
	$cleared=null;
	$filename =  $this->settings['kronfile'];
	$fp = fopen($filename, 'w+'); //   overwrite  the blacklistfile 
	fwrite($fp,  "\n" );
	$cleared=true;
	fclose($fp);
	return $cleared;
	}
	
	/*@return bool
	 * This function will wipe all ip addresses from the database (pit)
	 */
	public function wipePit()
	{
	$wiped=null;
	$filename =  $this->settings['blacklistfile'];
	$fp = fopen($filename, 'w+'); //   overwrite  the blacklistfile 
	fwrite($fp,  "\n" );
	$wiped=true;
	fclose($fp);
	return $wiped;
	}
	
	/*@return string
	 * This function will return the date/time the ip address was banned
	 */
	public function getTimebanned($ipaddress=null)
	{
		$time=null;
		$filename =  $this->settings['blacklistfile']; // scan to prevent duplicates
	$fp = fopen($filename, "r") or die("\t\t\t<p>Error opening file...</p>\n\t\t</div>\n\t</body>\n</html>");
	while ($line = fgets($fp)) {
		if (!preg_match("/(googlebot|slurp|msnbot|teoma|yandex)/i", $line)) {
			$u = explode(" ", $line);
			if ($u[0] == $_SERVER['REMOTE_ADDR'])
			{
			$ipinfo=$line; 
			}
		}
	}
	fclose($fp);
	
	$time=$this->getBantime($ipinfo);
	return $time;
	}
	
	private function getBantime($string=null)
	{
		$time=$string;
		$filename =  $this->settings['blacklistfile'];
		$file=file($filename);
		//fclose($file);
		return $time;
	}
	
	
}
?>
