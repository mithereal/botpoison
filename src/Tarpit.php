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

namespace Mithereal\Tarpit;

class Tarpit implements Blackhole_Interface {

public $target; 	
public $settings;
//private $DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];

	
	public function tarpit(){
		require 'lib/config.php';
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
	public function isFlagged($ip=null){
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
	 * This function will add a ip address to the database
	 */
	public function addIp($ip=null)
	{
		$success=null;
	$tmestamp  = time();
	$datestamp = date("l, F jS Y @ H:i:s", $tmestamp);
	$filename =  $this->settings['blacklistfile'];
	$fp = fopen($filename, 'a+'); // append to blacklistfile
	flock($fp, LOCK_EX);
	fwrite($fp, $_SERVER['REMOTE_ADDR'] ." - ". $_SERVER['REQUEST_METHOD'] ." - ". $_SERVER['SERVER_PROTOCOL'] ." - ". $datestamp ." - ". $_SERVER['HTTP_USER_AGENT'] ."\n");
	flock($fp, LOCK_UN);
	$success=true;
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
	flock($fp, LOCK_EX);
	fwrite($fp,  "\n" );
	flock($fp, LOCK_UN);
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

	public function removeIp($ip=null)
	{
		$IPtrap=$this->iptrap();
		$fp = fopen($this->settings['blacklistfile'], 'w') or die("\t\t\t<p>Error opening file...</p>\n\t\t</div>\n\t</body>\n</html>");
		flock($fp, LOCK_EX);
		if ( $fp != 0 ) {
			while ($line = fgets($fp)) {
				//echo 'blockip is ' . $blockip .'<br>'; //Fixme:: blockip is showing werong
				//if($blockip != $_SERVER['REMOTE_ADDR']){
				if(!preg_match("/^ \$_SERVER['REMOTE_ADDR']/",$line)){
				fputs($fp,"$line");
				}
			}
			flock($fp, LOCK_UN);
			fclose($fp);
		}
	}
	
	public function iptrap()
	{
		$IPtrap=file($this->settings['blacklistfile']);
		sort($IPtrap);
		reset($IPtrap);

		//fclose($file);
		return $IPtrap;
	}
	
	private function getBantime($string=null)
	{
		$time=$string;
		$filename =  $this->settings['blacklistfile'];
		$file=file($filename);
		fclose($file);
		return $time;
	}
	
	public function processCaptcha($data = null)
{
	$success=false;
  $_SESSION['ctform'] = array(); // re-initialize the form session data

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ct_captcha'])) {
  	// if the form has been submitted
  	
    $captcha = @$_POST['ct_captcha']; // the user's entry for the captcha code

    $errors = array();  // initialize empty error array

    // Only try to validate the captcha if the form has no errors
    // This is especially important for ajax calls
    
    if (sizeof($errors) == 0) {
     require_once 'securimage/securimage.php';
      $securimage = new Securimage();
      
      if ($securimage->check($captcha) == false) {
        $errors['captcha_error'] = 'Incorrect security code entered<br />';
      }
      // no errors, 
     // echo "Correct security code entered<br>";
      $this->free_ip(); //get banned ip and remove ip
      $_SESSION['ctform']['error'] = false;  // no error with form
      $success=true;
      
      if (sizeof($errors) != 0) {
		  $success = false;
		   $_SESSION['ctform']['error'] = true; // set error floag
	  }
    } else {
		$success = false;
      // do nothing
//echo "Incorrect security code entered<br>";

/*
      foreach($errors as $key => $error) {
      	// set up error messages to display with each field
        $_SESSION['ctform'][$key] = "<span style=\"font-weight: bold; color: #f00\">$error</span>";
       // echo $_SESSION['ctform'][$key];
      }
*/

      $_SESSION['ctform']['error'] = true; // set error floag
    }
  } // POST
  return $success;
}

}
?>