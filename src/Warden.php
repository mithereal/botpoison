<?php
/*
Title: BotPoison
Description: Automatically trap, block and poison bots that don't obey robots.txt rules
Project URL: https://github.com/mithereal/botpoison
Author: Jason Clark (mithereal@gmail.com)
Release: 10.13.2016
Version: 1.0
*/

namespace Mithereal\Botpoison;

class Warden implements Jail_Interface
{

    public $suspect;
    public $settings;

    /* @return false
     * Construct Function
     */
    public function __construct($settings = ['suspect' => null, 'settings' => null])
    {
        if (null === $settings) {
            require 'lib/config.php';
        }
        if (!is_empty($settings['settings'])) {
            $this->settings = $settings['settings'];
        }

        investigate($settings['suspect']);
    }

    public function investigate($suspect = null)
    {

        $this->suspect = $suspect;

        if ($suspect === null) {
            $this->suspect['ip'] = $_SERVER['REMOTE_ADDR'];
            $this->suspect['query_string'] = $_SERVER['QUERY_STRING'];
            $this->suspect['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $this->suspect['referrer'] = $_SERVER['HTTP_REFERER'];
            $this->suspect['protocol'] = $_SERVER['SERVER_PROTOCOL'];
            $this->suspect['method'] = $_SERVER['REQUEST_METHOD'];
            $this->suspect['request_uri'] = $_SERVER['REQUEST_URI'];
            $this->suspect['timestamp'] = date('Y/m/d @ h:i:s a', current_time('timestamp'));
        }
    }

    /* @return bool
     * @param string
     * This function will determine weather the ip is valid
     */
    public function validate($ip = null)
    {

        $valid = false;

        switch ($ip) {
            case !$ip:
                #   $message['message'] = " Error: You did not specify a valid target host or IP.";
                #   $this->debug($message);
                $valid = false;
                break;
            case preg_match("/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $ip):
                #   $message['message'] = " Error: You did not specify a valid target host or IP.";
                #   $this->debug($message);
                $valid = true;
                break;
            case preg_match("/^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/", $ip):
                #   $message['message'] = " Error: You did not specify a valid target host or IP.";
                #   $this->debug($message);
                $valid = true;
                break;
            default:
                $valid = false;
        }


        return $valid;
    }

    /* @return bool
     * @param  string
     * This function will determine weather the user is a known bot (ie already in the database)
     */
    public function lookup($ip = null)
    {
        $record = null; // set default value
        $filename = $this->settings['blacklistfile']; // scan to prevent duplicates
        $fp = fopen($filename, "r") or die("\t\t\t<p>Error opening file...</p>\n\t\t</div>\n\t</body>\n</html>");
        while ($line = fgets($fp)) {
            if (!preg_match("/(googlebot|slurp|msnbot|teoma|yandex)/i", $line)) {
                $u = explode(" ", $line);
                if ($u[0] == $_SERVER['REMOTE_ADDR']) {
                    $record++;
                }
            }
        }
        fclose($fp);
        return $record;
    }

    /* @return bool
     * @param  string
     * This function will add a ip address to the database
     */
    public function admit($ip = null)
    {
        $success = null;
        $timestamp = time();
        $datestamp = date("l, F jS Y @ H:i:s", $timestamp);
        $filename = $this->settings['blacklistfile'];
        $fp = fopen($filename, 'a+'); // append to blacklistfile
        flock($fp, LOCK_EX);
        fwrite($fp, $_SERVER['REMOTE_ADDR'] . " - " . $_SERVER['REQUEST_METHOD'] . " - " . $_SERVER['SERVER_PROTOCOL'] . " - " . $datestamp . " - " . $_SERVER['HTTP_USER_AGENT'] . "\n");
        flock($fp, LOCK_UN);
        $success = true;
        fclose($fp);
        return $success;
    }


    /* @return string
     * @param string
     * @param string
     * This function grabs whois query data
     */
    public function whois($ip = null, $msg = null)
    {
        $ip = $this->suspect;
        $server = "whois.arin.net";

        if (!$ip = gethostbyname($this->suspect)) {
            $message['message'] .= " Can't IP Whois without an IP address.";
        } else {
            $sock = null;
            $buffer = null;
            $nextServer = null;

            if (!$sock = fsockopen($server, 43, $num, $error, 20)) {
                unset($sock);
                $message['message'] .= " Timed-out connecting to $server (port 43).";
            } else {
                fputs($sock, "$ip\n");
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
                $server_message = [];
                $buffer = "";
                $buffer = "";
                $server_message['message'] = " Deferred to specific whois server: $nextServer...";
                $this->debug($server_message);
                if (!$sock = fsockopen($nextServer, 43, $num, $error, 10)) {
                    unset($sock);
                    $msg .= " Timed-out connecting to $nextServer (port 43)";
                } else {
                    fputs($sock, "$ip$extra\n");
                    while (!feof($sock))
                        $buffer .= fgets($sock, 10240);
                    fclose($sock);
                }
            }
            $message['message'] .= nl2br($buffer);
        }
        $message['message'] = trim(ereg_replace('#', '', strip_tags($message['message'])));
        $this->debug($message);
    }

    /* @return string
     * @param array
     * This function will create a message with timestamp and ip
     */
    public function debug($data = [])
    {
        $timestamp = time();
        $message = "\t\t\t" . "Timestamp: " . $timestamp . "\n";
        $message .= "\t\t\t" . "IP Address: " . $this->suspect['ip'] . "\n";
        $message .= "\t\t\t" . "Message: " . $data['message'] . "\n";
        return $message;
    }

    /* @return bool;
     * @param array
     * This function will send email to your an email address
     */
    public function contact($data = [])
    {
        $timestamp = time();
        $datestamp = date("l, F jS Y @ H:i:s", $timestamp);
        $headers  = 'X-Mailer: BotPoison'. "\n";
    	$headers .= $data['sender']. "\n";
	    $headers .= 'Content-Type: text/plain; charset=UTF-8' . "\n";
        $recipient = $data['recipient'];
        $subject = $data['subject'];
        $message = $datestamp . "\n\n";
        $message .= "URL Request: " . $_SERVER['REQUEST_URI'] . "\n";
        $message .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $message .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n\n";
        if (isset($data['whois'])) {
            $message .= "Whois Lookup: " . "\n";
            $message .= "\n" . $data['whois'] . "\n";
        }
        $result = mail($recipient, $subject, $message, $headers);
        return $result;
    }

    /* @return bool
     * @param string
     * This function will touch a file
     */
    public function touch($filename = null)
    {
        $fp = fopen($filename, 'w+');
        fwrite($fp, "\n");
        fclose($fp);
        return true;
    }

    /* @return bool
     * This function will wipe all ip addresses from the database
     */
    public function empty()
    {
        $filename = $this->settings['blacklistfile'];
        $fp = fopen($filename, 'w+');
        flock($fp, LOCK_EX);
        fwrite($fp, "\n");
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    /* @return string
     * @param string
     * This function will inspect the headers
     */
    public function inspect($headers = null)
    {
        if ($headers === null) {
            $headers = array('REMOTE_ADDR', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_X_REAL_IP', 'HTTP_CF_CONNECTING_IP');
        }
        $result = [];

        foreach ($headers as $header) {

            if (array_key_exists($header, $_SERVER) === true) {

                foreach (explode(',', $_SERVER[$header]) as $value) {

                    $trimmed = trim($value);

                    $ip = $this->clean($trimmed);

                    if ($this->validate($ip)) {
                        $result[$header] = $ip;
                    }
                }
            }
        }
        return $result;
    }

    /* @return string
     * @param string
     * This function will cleanup the ip
     */
    private function clean($ip = null)
    {
        if (strpos($ip, ':') !== false && substr_count($ip, '.') == 3 && strpos($ip, '[') === false) {

            $ip = explode(':', $ip);
            $ip = $ip[0];

        } else {

            $ip = explode(']', $ip);
            $ip = ltrim($ip[0], '[');

        }

        return $ip;
    }

    /* @return string
     * @param string
     * This function will remove one ip address from the database
     */
    public function discharge($ip = null)
    {
        $dataset = $this->jail();
        $fp = fopen($this->settings['blacklistfile'], 'w') or die("\t\t\t<p>Error opening file...</p>\n\t\t</div>\n\t</body>\n</html>");
        flock($fp, LOCK_EX);
        if ($fp != 0) {
            while ($line = fgets($fp)) {
                if (!preg_match("/^ \$_SERVER['REMOTE_ADDR']/", $line)) {
                    fputs($fp, "$line");
                }
            }
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    /* @return file
     * This function will return the database
     */
    public function jail()
    {
        $dataset = file($this->settings['blacklistfile']);
        sort($dataset);
        reset($dataset);

        return $dataset;
    }

    /* @return string
     * This function will inject the poison into the view and return the view
     */
    public function exploit($file, $poison_type)
    {
        $subject = file_get_contents($file);
        $poison = new $poison_type();
        $effect = new Poison();
        $result = $effect->inject($subject, $poison);
        return $result;
    }


}

?>
