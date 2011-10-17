<?php
require 'pwp.php';

class blog extends pwp{
public $title='The Bot Blog';


//public function blog(){
	
//}

public function tellStory()
{	
	$page=null;
  $this->fill_wordcache ($this->word_file, $this->cache_file, $this->total_words, $this->cached_words, $this->cache_ttl);
  $this->words = $this->load_words ($this->cache_file, $this->target, $this->title_insertrate);
  
  if ($this->words) {
    $page .= "<h1>" . $this->build_dummytext ($this->mintitle_words + rand (0, ($this->maxtitle_words - $this->mintitle_words)), $this->words, 0, $this->target) . "</h1>\n";
    for ($pc = 1; $pc <= $this->pre_dummypar; $pc++) {
     $page .= "<p>" . $this->build_dummytext ($this->mindummy_words + rand (0, ($this->maxdummy_words - $this->mindummy_words)), $this->words, $this->link_firstratio, $this->target) . "</p>\n";
    }
    if ($this->maxsleeptime > 0) {
      sleep ($this->minsleeptime + rand (0, ($this->maxsleeptime - $this->minsleeptime)));
    }
    for ($pc = 1; $pc <= $this->post_dummypar; $pc++) {
     $page .= "<p>" . $this->build_dummytext ($this->mindummy_words + rand (0, ($this->maxdummy_words - $this->mindummy_words)), $this->words, $this->link_lastratio, $this->target) . "</p>\n";
    }
    
  }else{
       $page= '<div class="error" id="nowordlist">Unable to load Wordlist</div>';
  }
$story=$page;
	
	return $story;
}

public function getComments(){
	   $comments= $this->build_comment ($this->minemails + rand (0, ($this->maxemails - $this->minemails)), $this->words, $this->spammers); // build a <p>email</p>
	   return $comments;
}

// Create an email link.
public function build_comment ($totalmails, &$wordlist, &$spammerlist) {
  $list = null;
  for ($ce = 1; $ce < $totalmails; $ce++) {
    $newemail = "";
 $list .= "<div class='comment'>\n";
     $list .= "<div class='commentText'>" . $this->build_dummytext ($this->mindummy_words + rand (0, ($this->maxdummy_words - $this->mindummy_words)), $this->words, $this->link_firstratio, $this->target) . "</div>\n";
     
   // $list .="<div class='commentText'>some random comments will go here</div>";
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
      $list .= "<div class='email'><a href=\"mailto:" . $newemail . "\">" . $newemail . "</a></div>\n";
    }
    $list .= "</div>\n";
  }
  
  return $list;
}



}
?>
