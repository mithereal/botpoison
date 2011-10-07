<?php
class tarpit{
	
public $settings;
	
public function tarpit(){
		require 'config.php'; 
		$this->settings=$settings;
	}
	
public function isBot($type=null){
	$type=$this->settings['datastorage'];
if($type !='database')
{
$badbot = 0; // set default value
$filename =  $this->settings['dbname']; // scan to prevent duplicates
$fp = fopen($filename, "r") or die("\t\t\t<p>Error opening file...</p>\n\t\t</div>\n\t</body>\n</html>");
while ($line = fgets($fp)) {
	if (!preg_match("/(googlebot|slurp|msnbot|teoma|yandex)/i", $line)) {
		$u = explode(" ", $line);
		if ($u[0] == $_SERVER['REMOTE_ADDR'])
		{
		 $badbot++;
		// $this->addBot();
		}
	}
}
fclose($fp);
}

return $badbot;
}

	public function addBot()
	{
	$filename =  $this->settings['dbname'];
	$fp = fopen($filename, 'a+'); // append to blacklistfile
	fwrite($fp, $_SERVER['REMOTE_ADDR'] ." - ". $_SERVER['REQUEST_METHOD'] ." - ". $_SERVER['SERVER_PROTOCOL'] ." - ". $datestamp ." - ". $_SERVER['HTTP_USER_AGENT'] ."\n");
	fclose($fp);
	}
}
?>
