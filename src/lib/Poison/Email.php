<?php

namespace Mithereal\Blackhole;

class Email extends Poison
{
    public $settings = [
        'count' => 200,
        'word_file' => 200,
        'total_words' => 0,
        'word_cache_file' => "cache/word_cache.txt",
        'email_cache_file' => "cache/email_cache.txt",
        'cached_words' => 1000,
        'minword_len' => 4,
        'maxword_len' => 10,
        'cache_ttl' => 7200,
        'presalt_user' => 0,
        'postsalt_user' => 2,
        'presalt_dom' => 2,
        'postsalt_dom' => 0,
        'numsalt_ratio' => 10,
        'internat_ratio' => 3,
        'symbol_ratio' => 10,
        'use_spammer_list' => true,
        'spammer_file' => "lib/spammers.txt",
        'spammer_ratio' => 5,
        'spammer_genratio' => 2,
        'script_version' => '1.0'
    ];


    public $common_symbols = array(".", ".", ".", ".", ",", ",", ",", ",", ",", ",", ",", ";", ";", ";", "?", "!");
    public $end_symbols = array(".", ".", ".", ".", ".", ".", ".", ".", ".", ".", ".", ".", "?", "!");
    public $tldomains = array("com", "com", "com", "com", "com", "com", "com", "com", "com", "com", "com",
        "com", "com", "com", "com", "com", "com", "com", "com", "com", "com", "com",
        "com", "com", "com", "com", "com", "com", "com", "com", "com", "com", "com",
        "com", "com", "com", "com", "com", "com", "com", "com", "com", "com", "com",
        "net", "net", "net", "net", "net", "net", "net", "net",
        "net", "net", "net", "net", "net", "net", "net", "net",
        "org", "org", "org", "org",
        "biz", "biz", "info", "info", "edu", "gov");
    public $ctldomains = array("de", "de", "de", "de", "de", "de", "de", "de", "de", "de",
        "de", "de", "de", "de", "de", "de", "de", "de", "de", "de",
        "uk", "uk", "uk", "uk", "uk", "uk", "uk", "uk", "uk", "uk",
        "uk", "uk", "uk", "uk", "uk", "uk", "uk", "uk", "uk", "uk",
        "ca", "ca", "ca", "ca", "ca", "ca", "ca", "ca", "ca", "ca",
        "ca", "ca", "ca", "ca", "ca", "ca", "ca", "ca", "ca", "ca",
        "fr", "fr", "fr", "fr", "fr", "fr", "fr", "fr", "fr", "fr",
        "cn", "cn", "cn", "cn", "cn", "cn", "cn", "cn", "cn", "cn",
        "kr", "kr", "kr", "kr", "kr", "kr", "kr", "kr", "kr", "kr",
        "au", "au", "au",
        "jp", "jp", "jp",
        "es", "es", "es",
        "it", "it", "it",
        "hk", "hk", "hk",
        "nl", "nl", "nl",
        "in", "in", "in",
        "dk", "dk", "dk",
        "tr", "tr", "tr",
        "at", "at", "se", "se", "ky", "ky", "ch", "ch", "no", "no",
        "fi", "be", "mx", "ru", "br", "vg", "pl", "th", "ie",
        "cz", "sg", "ar", "my", "il", "ir", "nz", "tw", "pt", "za",
        "bg", "id", "ro", "mc", "ua", "ve");

    public function __construct()
    {
        $total_words = $this->linecount($file);
        $this->fill_word_cache($this->settings['word_file'], $this->settings['word_cache_file'], $total_words, $this->settings['cached_words'], $this->settings['cache_ttl']);
    }

    /* @return string
     * @param string
     *  Function
     */
    public function linecount($file)
    {
        $linecount = 0;
        $handle = fopen($file, "r");

        while (!feof($handle)) {
            $line = fgets($handle);
            $linecount++;
        }

        fclose($handle);

        return $linecount;
    }

    /* @return property
     * @param property name
     * @param property value
     * Getter Function
     */
    public function get($var)
    {
    return $this->settings[$var];
    }

    /* @return bool
     * @param property name
     * @param property value
     * Setter Function
     */
    public function set($var, $value)
    {
      $this->settings[$var] = $value;
      return true;
    }

    /* @return property
     * Main Function
     */
    public function activate()
    {

        $words = $this->load_words($this->cache_file, $this->title_insertrate);

        if ($this->use_spammer_list) {
            $spammers = $this->load_words($this->spammer_file);
        } else {
            $spammers = $this->generate($this->settings['count']);
        }
      return  $this->generate_view($spammers);

    }

    /* @return bool
     * @param property name
     * @param property value
     * Load the words from the cache file to memory.
     */
    public function fill_word_cache($sourcefilename, $targetfilename, $totalsourcewords, $totaltargetwords,$minword_len, $maxword_len, $ttl)
    {
        $this->settings['count'] = $totalsourcewords;
        $sourcefile = null;
        if ($totalsourcewords > $totaltargetwords) {
            $wordsratio = round($totalsourcewords / $totaltargetwords);
        } else {
            $wordsratio = 1;
        }
        if (($sourcefilename) && ($targetfilename)) {
            if ((file_exists($targetfilename) && is_writable($targetfilename) && ((time() - filemtime($targetfilename)) > $ttl)) || !file_exists($targetfilename)) {
                if (file_exists($sourcefilename) && is_readable($sourcefilename)) {
                    $sourcefile = fopen($sourcefilename, "rb");
                    $targetfile = fopen($targetfilename, 'wb');
                }
                $ignorecount = 1;
                $randomline = rand(1, $wordsratio);
                if (($sourcefile) && ($targetfile)) {
                    while ($wordcontent = fgets($sourcefile, 1024)) {
                        if ($ignorecount == $randomline) {
                            $wordcontent = ereg_replace("[^[:alpha:]_]", "", strtolower($wordcontent)) . "\n";
                            if ((strlen($wordcontent) >= $minword_len) && (strlen($wordcontent) <= $maxword_len)) {
                                fwrite($targetfile, $wordcontent);
                            }
                        } elseif ($ignorecount >= $wordsratio) {
                            $ignorecount = 1;
                            $randomline = rand(1, $wordsratio);
                        }
                        $ignorecount++;
                    }
                    @fclose($targetfile);
                    @fclose($sourcefile);
                }
            }
        }
        return true;
    }

    /* @return array
     * @param property name
     * @param property value
     * Load the words from the cache file to memory.
     */
    public function load_words($sourcefilename, $insertrate)
    {
        $wordlist = [];
        if (file_exists($sourcefilename) && is_readable($sourcefilename)) {
            $wordlist = file($sourcefilename);
            $tinserts = round(count($wordlist) / (100 / $insertrate));

            if (count($wordlist) > 0) {
                foreach ($wordlist as $key => $value) {
                    $wordlist[$key] = trim($value);
                }
            }
        }
         return $wordlist;
    }


    /* @return array
     * @param property name
     * @param property value
     * Add a short random string to the beginning or end of a given string.
     */
    public function add_salt($textstr, $presalt, $postsalt)
    {

        $presaltstr = "";
        $postsaltstr = "";
        if ($presalt > 0) {
            for ($sc = 1; $sc <= $presalt; $sc++) {
                if (rand(1, $this->numsalt_ratio) == $this->numsalt_ratio) {
                    $presaltstr .= chr(rand(ord("0"), ord("9")));
                } else {
                    $presaltstr .= chr(rand(ord("a"), ord("z")));
                }
            }
        }
        if ($postsalt > 0) {
            for ($sc = 1; $sc <= $postsalt; $sc++) {
                if (rand(1, $this->numsalt_ratio) == $this->numsalt_ratio) {
                    $postsaltstr .= chr(rand(ord("0"), ord("9")));
                } else {
                    $postsaltstr .= chr(rand(ord("a"), ord("z")));
                }
            }
        }
        return ($presaltstr . $textstr . $postsaltstr);
    }

    /* @return array
     * @param property name
     * @param property value
     * Convert to uppercase the first letter of each sentence.
     */
    public function ucfirst($string)
    {

        $result = ("");

        if ($string) {
            $strarray = explode(" ", $string);
            $totwords = count($strarray);
            $restart = false;
            for ($cw = 0; $cw < $totwords; $cw++) {
                if ($restart) {
                    $strarray[$cw] = ucfirst($strarray[$cw]);
                }
                $restart = in_array(substr($strarray[$cw], -1), $this->end_symbols);
            }
            $strarray[0] = ucfirst($strarray[0]);
            $result = (implode(" ", $strarray));
        }
        return $result;
    }

    /* @return array
     * @param property string
     * @param property array
     * Create a Domain.
     */
    public function build_domain($wordlist)
    {
//global $this->tldomains, $this->ctldomains, $this->internat_ratio;
        $newdomain = $wordlist[array_rand($wordlist, 1)];
        $newdomain .= "." . $this->tldomains[array_rand($this->tldomains, 1)];
        if (rand(1, $this->internat_ratio) == $this->internat_ratio) {
            $newdomain .= "." . $this->ctldomains[array_rand($this->ctldomains, 1)];
        }
        return $newdomain;
    }

    /* @return string
     * @param array
     * Create a username.
     */
    public function build_username($wordlist)
    {
        return $wordlist[array_rand($wordlist, 1)];
    }

    /* @return string
     * @param string
     * Extract domain name from email address.
     */
    public function extract_domain($email)
    {
        return strstr($email, "@");
    }

    /* @return string
     * @param string
     * Create a view snippet.
     */
    public function generate_view($spammers)
    {
    $dom = new DOMDocument();
    $elem = $dom->createElement('div');

    $view ='<ul>';
    foreach($spammers as $s){
    $view .='<li>' . $s . '</li>';
    }
    $view ='</ul>';

   $result = $this->appendHTML($elem, $view);

    return $result;
    }

     /* @return string
     * @param string
     * @param array
     * Create a username.
     */
    public function generate($total, $wordlist)
    {

        for ($i = 1; $i < $total; $i++) {
            $newemail = ' ';
            if ((rand(1, $this->spammer_ratio) == $this->spammer_ratio)) {

                $newuser = $this->add_salt($this->build_username($wordlist), $this->presalt_user, $this->postsalt_user);
                $newdom = $this->extract_domain(trim($spammerlist[array_rand($spammerlist, 1)]));
                if ($newdom) {
                    $newemail = $newuser . $newdom;
                }
            } else {
                $newuser = $this->add_salt($this->build_username($wordlist), $this->presalt_user, $this->postsalt_user);
                $newdom = $this->add_salt($this->build_domain($wordlist), $this->presalt_dom, $this->postsalt_dom);
                $newemail = $newuser . "@" . $newdom;
            }

        }

        return $newemail;
    }

public function appendHTML(DOMNode $parent, $source) {
    $tmpDoc = new DOMDocument();
    $tmpDoc->loadHTML($source);
    foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $node) {
        $node = $parent->ownerDocument->importNode($node);
        $parent->appendChild($node);
    }
}
}

?>