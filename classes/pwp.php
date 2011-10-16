<?php
class pwp{
    public $csslink='views/css/index.css';
    public $scripturl;
    public $level;
    public $words;
public $target;
public $page;
private $scriptname = "index.php";                                // Name of the parent script. THIS MUST BE SET BEFORE USING.
public $minemails = 5;                                  // Minimum emails per page.
public $maxemails = 30;                                 // Maximum emails per page.
public $maxlevel = 15;                                  // Deepest level to create links.
public $word_file = "includes/pwpwords.txt";                     // Source word file, relative to calling script.
public $total_words = 99204;                            // Lines in source word file.
public $cache_file = "includes/cache.txt";                       // Cache word file, relative to calling script.
public $cached_words = 300;                             // Words to extract from source word list.
public $minword_len = 4;                                // Minimum length of words to use.
public $maxword_len = 10;                               // Maximum length of words to use.
public $cache_ttl = 7200;                               // Time before the cache file is rebuild (in seconds).
public $minsleeptime = 0;                              // Minimum time to sleep before finishing page (in seconds).
public $maxsleeptime = 0;                              // Maximum time to sleep before finishing page (in seconds).
public $mintitle_words = 2;                             // Minimum words to use as title.
public $maxtitle_words = 5;                             // Maximum words to use as title.
public $mindummy_words = 10;                            // Minimum words to use in paragraphs.
public $maxdummy_words = 25;                            // Maximum words to use in paragraphs.
public $pre_dummypar = 2;                               // Numbers of paragraphs before email list.
public $post_dummypar = 4;                              // Numbers of paragraphs after email list.
public $presalt_user = 0;                               // Salt characters to add at the beginning of username in emails.
public $postsalt_user = 2;                              // Salt characters to add at the ending of username in emails.
public $presalt_dom = 2;                                // Salt characters to add at the beginning of domains in emails.
public $postsalt_dom = 0;                               // Salt characters to add at the ending of domains in emails.
public $numsalt_ratio = 10;                             // One out of X times a number is used as salt (0 = never).
public $internat_ratio = 3;                             // One out of X times an international domain is used.
public $link_firstratio;          // One out of each X words is converted to a link in first paragraphs.
public $link_lastratio ;           // One out of each X words is converted to a link in last paragraphs.
public $title_insertrate = 5;                           // Percentage of the time that the title is used as body text.
public $symbol_ratio = 10;                              // One out of each X words is appended a punctuation symbol.
public $use_spammer_list = true;                       // If we will include the spammer database as source of emails.
public $spammer_file = "includes/spammers.txt";                  // Spammer list file, relative to calling script.
public $spammer_ratio = 5;                              // One out of X emails a spammer email will be used.
public $spammer_generate = true;                        // If random emails will be generated using spammer domains.
public $spammer_genratio = 2;                           // One out of X times the spammer email is generated.
// The following variables contain the appearance of the generated page, use them to match the appearance of your website.
public $html_preheader = "<html><head><title>\n";
public $html_postheader = "</title><meta NAME=\"ROBOTS\" CONTENT=\"NOINDEX, NOFOLLOW\">\n</head>\n<body>\n";
public $html_footer = "</body></html>\n";
public $script_version = "1.0";
public $title = "My Bot Blog";

// Set some constants...
public $common_symbols = array (".", ".", ".", ".", ",", ",", ",", ",", ",", ",", ",", ";", ";", ";", "?", "!");
public $end_symbols = array (".", ".", ".", ".", ".", ".", ".", ".", ".", ".", ".", ".", "?", "!");

// Based on stats at http://www.webhosting.info/registries/global_stats/
public $tldomains = array ("com", "com", "com", "com", "com", "com", "com", "com", "com", "com", "com",
                        "com", "com", "com", "com", "com", "com", "com", "com", "com", "com", "com",
                        "com", "com", "com", "com", "com", "com", "com", "com", "com", "com", "com",
                        "com", "com", "com", "com", "com", "com", "com", "com", "com", "com", "com",
                        "net", "net", "net", "net", "net", "net", "net", "net",
                        "net", "net", "net", "net", "net", "net", "net", "net",
                        "org", "org", "org", "org",
                        "biz", "biz", "info", "info", "edu", "gov");

// Based on stats at http://www.webhosting.info/domains/country_stats/
public $ctldomains = array ("de", "de", "de", "de", "de", "de", "de", "de", "de", "de",
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
						 
public function pwp(){
$this->link_firstratio = $this->maxdummy_words;          // One out of each X words is converted to a link in first paragraphs.
$this->link_lastratio = $this->mindummy_words; 
// Get the URL and split it, finding the target and the level...


$req_uri = $_SERVER["REQUEST_URI"];
if (!$req_uri) {
  $req_uri = $HTTP_SERVER_VARS["REQUEST_URI"];
  if (!$req_uri) {
    $req_uri = $_ENV["REQUEST_URI"];
    if (!$req_uri) {
      $req_uri = getenv("REQUEST_URI");
    }
    else $req_uri = "";
  }
}
$url_array = explode ("/", $req_uri);

    $target = $url_array[(count ($url_array) - 1)];

if ($target == $this->scriptname) {
  $target = "";
}
else {
  unset ($url_array[(count ($url_array) - 1)]);
}
$level = ereg_replace("[^[:digit:]_]", "", $target);
if (is_numeric ($level)) { $level = abs ($level); } else { $level = 0; }
$target = ereg_replace("[^[:alpha:]_]", "", $target);
$scripturl = implode ("/", $url_array);
$this->target=$target;
$this->level=$level;
$this->scripturl=$scripturl;
}

public function displayPage()
{

if ($this->scriptname) {
  $page=null;
  $page=$this->html_preheader . $this->target . $this->html_postheader; 
  $this->fill_wordcache ($this->word_file, $this->cache_file, $this->total_words, $this->cached_words, $this->cache_ttl);
  $this->words = $this->load_words ($this->cache_file, $this->target, $this->title_insertrate);
  
  if ($this->use_spammer_list) {
    $this->spammers = $this->load_spammers ($this->spammer_file);
  }
  else {
    $this->spammers = false;
  }
  
  if ($this->words) {
    $page .= "<h1>" . $this->build_dummytext ($this->mintitle_words + rand (0, ($this->maxtitle_words - $this->mintitle_words)), $this->words, 0, $this->target) . "</h1>\n";
    for ($pc = 1; $pc <= $this->pre_dummypar; $pc++) {
     $page .= "<p>" . $this->build_dummytext ($this->mindummy_words + rand (0, ($this->maxdummy_words - $this->mindummy_words)), $this->words, $this->link_firstratio, $this->target) . "</p>\n";
    }
   $page .= $this->build_maillist ($this->minemails + rand (0, ($this->maxemails - $this->minemails)), $this->words, $this->spammers); // build a <p>email</p>
    if ($this->maxsleeptime > 0) {
      sleep ($this->minsleeptime + rand (0, ($this->maxsleeptime - $this->minsleeptime)));
    }
    for ($pc = 1; $pc <= $this->post_dummypar; $pc++) {
     $page .= "<p>" . $this->build_dummytext ($this->mindummy_words + rand (0, ($this->maxdummy_words - $this->mindummy_words)), $this->words, $this->link_lastratio, $this->target) . "</p>\n";
    }
    
  }else{
      $page= '<div class="error" id="nowordlist">Unable to load Wordlist</div>';
  }
      $page .=  $this->html_footer; 
}
else {
$page = '<div class="error" id="notconfigured">DON\'T FORGET TO CONFIGURE THE SCRIPT!</div>';
}

echo $page;
}

public function fill_wordcache ($sourcefilename, $targetfilename, $totalsourcewords, $totaltargetwords, $ttl) {
//global $this->minword_len, $this->maxword_len;
$sourcefile=null;
  if ($totalsourcewords > $totaltargetwords) {
    $wordsratio = round ($totalsourcewords / $totaltargetwords);
  }
  else {
    $wordsratio = 1;
  }
  if (($sourcefilename) && ($targetfilename)) {
    if ((file_exists ($targetfilename) && is_writable ($targetfilename) && ((time () - filemtime ($targetfilename)) > $ttl)) || !file_exists ($targetfilename)) {
      if (file_exists ($sourcefilename) && is_readable ($sourcefilename)) {
        $sourcefile = fopen ($sourcefilename, "rb");
        $targetfile = fopen ($targetfilename, 'wb');
      }
      $ignorecount = 1;
      $randomline = rand (1, $wordsratio);
      if (($sourcefile) && ($targetfile)) {
        while ($wordcontent = fgets ($sourcefile, 1024)) {
          if ($ignorecount == $randomline) {
            $wordcontent = ereg_replace("[^[:alpha:]_]", "", strtolower ($wordcontent)) . "\n";
            if ((strlen ($wordcontent) >= $this->minword_len) && (strlen ($wordcontent) <= $this->maxword_len)) {
              fwrite ($targetfile, $wordcontent);
            }
          }
          elseif ($ignorecount >= $wordsratio) {
            $ignorecount = 1;
            $randomline = rand (1, $wordsratio);
          }
          $ignorecount++;
        }
        @fclose ($targetfile);
        @fclose ($sourcefile);
      }
    }
  }
}


// Load the words from the cache file to memory.
public function load_words ($sourcefilename, $pagetitle, $insertrate) {
  if (file_exists ($sourcefilename) && is_readable ($sourcefilename)) {
    $wordlist = file ($sourcefilename);
    $tinserts = round (count ($wordlist) / (100 / $insertrate));
    if (count ($wordlist) > 0) {
      foreach ($wordlist as $key => $value) { $wordlist[$key] = trim ($value); }
    }
    if ($pagetitle) {
      for ($wc = 0; $wc < $tinserts; $wc++) { $wordlist[] = $pagetitle; }
    }
    return $wordlist;
  }
  else {
      echo $sourcefilename;
    return false;
  }
}


// Load the spammer emails from the spammers file to memory.
public function load_spammers ($sourcefilename) {
  if (file_exists ($sourcefilename) && is_readable ($sourcefilename)) {
    $spammerlist = file ($sourcefilename);
    return $spammerlist;
  }
  else {
    return false;
  }
}


// Add a short random string to the beginning or end of a given string.
public function add_salt ($textstr, $presalt, $postsalt) {
//global $this->numsalt_ratio;
  $presaltstr = "";
  $postsaltstr = "";
  if ($presalt > 0) {
    for ($sc = 1; $sc <= $presalt; $sc++) {
      if (rand (1, $this->numsalt_ratio) == $this->numsalt_ratio) {
        $presaltstr .= chr (rand (ord ("0"), ord ("9")));
      }
      else {
        $presaltstr .= chr (rand (ord ("a"), ord ("z")));
      }
    }
  }
  if ($postsalt > 0) {
    for ($sc = 1; $sc <= $postsalt; $sc++) {
      if (rand (1, $this->numsalt_ratio) == $this->numsalt_ratio) {
        $postsaltstr .= chr (rand (ord ("0"), ord ("9")));
      }
      else {
        $postsaltstr .= chr (rand (ord ("a"), ord ("z")));
      }
    }
  }
  return ($presaltstr . $textstr . $postsaltstr);
}


// Convert to uppercase the first letter of each sentence.
public function ucfirst ($string) {
//global $this->end_symbols;
  if ($string) {
    $strarray = explode (" ", $string);
    $totwords = count ($strarray);
    $restart = false;
    for ($cw = 0; $cw < $totwords; $cw++) {
      if ($restart) {
        $strarray[$cw] = ucfirst ($strarray[$cw]);
      }
      $restart = in_array (substr ($strarray[$cw], -1), $this->end_symbols);
    }
    $strarray[0] = ucfirst ($strarray[0]);
    return (implode (" ", $strarray));
  }
  return ("");
}


// Create a paragraph using the word list, optionally create links within.
public function build_dummytext ($totalwords, &$wordlist, $linkratio, $title) {
  if (count ($wordlist) > 1) {
    shuffle ($wordlist);
    if ($totalwords > count ($wordlist)) {
      $totalwords = count ($wordlist);
    }
    $newlist = array_rand ($wordlist, $totalwords);
    $newtext = "";
    foreach ($newlist as $word) {
      if ((rand (1, $linkratio) == $linkratio) && ($wordlist[$word] != $title) && ($this->level < $this->maxlevel)) {
        $insertpos = rand (1, strlen ($wordlist[$word])) - 1;
        $newlink = substr ($wordlist[$word], 0, $insertpos) . ($this->level + 1) . substr ($wordlist[$word], $insertpos, strlen ($wordlist[$word]));
        if(isset($this->displayscripturl)){
        $newlink = $this->scripturl . "/" . $newlink;  //edit here to change seo suff
        }else{
        $newlink = $this->scripturl . "/$this->scriptname/" . $newlink;
        }
        $newtext .= "\n<a href=\"" . $newlink . "\">" . $wordlist[$word] . "</a>";
      }
      else {
        $newtext .= $wordlist[$word];
      }
      if (rand (1, $this->symbol_ratio) == $this->symbol_ratio) {
        $newtext .= $this->common_symbols[array_rand ($this->common_symbols, 1)];
      }
      $newtext .= " ";
    }
    $newtext = substr ($newtext, 0, -1);
    if (in_array (substr ($newtext, -1), $this->end_symbols)) {
      $newtext = substr ($newtext, 0, -1);
    }
    $newtext = $this->ucfirst ($newtext);
    $newtext .= $this->end_symbols[array_rand ($this->end_symbols, 1)];
    return $newtext;
  }
}


// Create a domain name.
public function build_domain (&$wordlist) {
//global $this->tldomains, $this->ctldomains, $this->internat_ratio;
  $newdomain = $wordlist[array_rand ($wordlist, 1)];
  $newdomain .= "." . $this->tldomains[array_rand ($this->tldomains, 1)];
  if (rand (1, $this->internat_ratio) == $this->internat_ratio) {
    $newdomain .= "." . $this->ctldomains[array_rand ($this->ctldomains, 1)];
  }
  return $newdomain;
}

// Create an username.
public function build_username (&$wordlist) {
  return $wordlist[array_rand ($wordlist, 1)];
}

// Extract domain name from email address.
public function extract_domain ($email) {
  return strstr ($email, "@");
}

// Create an email link.
public function build_maillist ($totalmails, &$wordlist, &$spammerlist) {
  $list = "<p>\n";
  for ($ce = 1; $ce < $totalmails; $ce++) {
    $newemail = "";
    if ($spammerlist && (rand (1, $this->spammer_ratio) == $this->spammer_ratio)) {
      if ($this->spammer_generate && (rand (1, $this->spammer_genratio) == $this->spammer_genratio)) {
        $newuser = $this->add_salt ($this->build_username ($wordlist), $this->presalt_user, $this->postsalt_user);
        $newdom = $this->extract_domain (trim ($spammerlist[array_rand ($spammerlist, 1)]));
        if ($newdom) {
          $newemail = $newuser . $newdom;
        }
      }
      else {
        $newemail = trim ($spammerlist[array_rand ($spammerlist, 1)]);
      }
    }
    else {
      $newuser = $this->add_salt ($this->build_username ($wordlist), $this->presalt_user, $this->postsalt_user);
      $newdom = $this->add_salt ($this->build_domain ($wordlist), $this->presalt_dom, $this->postsalt_dom);
      $newemail = $newuser . "@" . $newdom;
    }
    if ($newemail) {
      $list .= "<a href=\"mailto:" . $newemail . "\">" . $newemail . "</a><br>\n";
    }
  }
  $list .= "</p>\n";
  return $list;
}

}
?>
