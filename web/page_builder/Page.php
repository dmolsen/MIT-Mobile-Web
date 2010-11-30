<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

class Page {

  protected $title;
  protected $header;
  protected $stylesheets = array();
  protected $javascripts = array();
  protected $container;
  protected $footer="";
  protected $footer_script = NULL;
  protected $compressed_mode = True;

  protected $phone;
  protected $parts;

  public static $phoneType;

  protected $aquire_mode = False;
  protected $aquired = False;

  public static function factory($phone_type) {
    $type = $phone_type . "Page";
    self::$phoneType = $phone_type;
    return new $type();
  }

  public function cache() {
    header("Cache-Control: max-age=86400");
    return $this;
  }

  public function title($title) {
    $this->title = $title;
    return $this;
  }

  public function header($header) {
    $this->header = $header;
    return $this;
  }

  public function add_stylesheet($stylesheet_name) {
    $this->stylesheets[] = $stylesheet_name;
    return $this;
  }

  public function add_javascript($js_name) {
    $this->javascripts[] = $js_name;
    return $this;
  }

  public function footer_script($script) {
    $this->footer_script = $script;
    return $this;
  }

  public function extra_footer($footer) {
    $this->footer = $footer . ' ';
    return $this;
  }

  protected $help_on = True;

  public function help_off() {
    $this->help_on = False;
    return $this;
  }

  public function content_begin() {
    if($this->aquire_mode) {
      throw new Exception("Content already begun");
    } elseif ($this->aquired) {
      throw new Exception("Content already set");
    } else {
      ob_start();
      $this->aquire_mode = True;
    }
  }

  public function content_end() {
    if($this->aquire_mode) {
      $this->content = ob_get_clean();
      $this->aquire_mode = False;
      $this->aquired = True;
    }
  }

  public function output($base = false) {
    require "../../config.gen.inc.php";

    foreach($this->varnames as $varname) {
      ${$varname} = $this->$varname;
    }
    $phone = $this->phone;
    $prefix = $this->requirePrefix();
    
    ob_start();
    require "../templates/$prefix/base.html";
    $uncompressed_html = ob_get_clean();

    // replace large chunks of spaces with a single space
    $compressed_html = preg_replace('/\s*?\n\s*/', "\n", $uncompressed_html);    $compressed_html = preg_replace('/( |\t)( |\t)*/', " ", $compressed_html);
    if($this->compressed_mode) {
       echo $compressed_html;
    } else {
      echo $uncompressed_html;
    }  
  }

  protected function draw_content() {
    //draw the main content of the page
    echo $this->content;
  }

  // matches device to templates
  private static $phoneTable = array(
	"iphone42" => "webkit",
	"iphone4" => "webkit",
    "iphone3" => "webkit",
	"iphone2" => "touch",
	"ipod42" => "webkit",
	"ipod4" => "webkit",
    "ipod3" => "webkit",
	"ipod2" => "touch",
	"ipad" => "basic",
    "android22" => "webkit",
	"android21"	=> "webkit",
	"android20" => "webkit",
	"android" => "touch",
    "palm" => "touch",
	"opera_mini" => "touch",
	"opera_mobile" => "touch",
	"blackberry9800" => "touch",
	"blackberry" => "basic",
	"webkit" => "webkit",
    "smart_phone" => "basic",
    "feature_phone" => "basic",
    "computer" => "basic",
    "spider" => "basic"
  );

  // provide the nice looking name for a device & OS for stats
  public static $deviceEnglish = array(
    "iphone42" => "iPhone 4.2",
	"iphone4" => "iPhone 4.0",
    "iphone3" => "iPhone 3",
	"iphone2" => "iPhone 2",
	"ipod42" => "iPod 4.2",
	"ipod4" => "iPod 4",
    "ipod3" => "iPod 3",
	"ipod2" => "iPod 2",
	"ipad" => "iPad",
    "android22" => "Android 2.2",
	"android21"	=> "Android 2.1",
	"android20" => "Android 2.0",
	"android" => "Android (Generic)",
    "palm" => "Web OS",
	"opera_mini" => "Opera Mini",
	"opera_mobile" => "Opera Mobile",
	"blackberry9800" => "Blackberry 9800 (aka Torch)",
	"blackberry" => "BlackBerry (Generic)",
	"webkit" => "WebKit (Generic)",
    "smart_phone" => "Smart Phone (Generic)",
    "feature_phone" => "Feature Phone (Generic)",
    "computer" => "Computer (Generic)",
    "spider" => "Spider (Generic)"
  );

  private static $is_computer;
  private static $is_spider;

  # returns the general device type based on user agent string matching. can get very specific depending on usage.
  public static function classify_device_type() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    $accept = $_SERVER['HTTP_ACCEPT']; 

	if (preg_match('/ipod/i',$user_agent)) {
		if (preg_match('/OS\ 4.2/i',$user_agent)) {
			$type = 'ipod42';
		} else if (preg_match('/OS\ 4/i',$user_agent)) {
			$type = 'ipod4';
		} else if (preg_match('/OS\ 3/i',$user_agent)) {
			$type = 'ipod3';
		} else {
			$type = 'ipod2'; 
		}
	} 
	else if (preg_match('/iphone/i',$user_agent)) {
		if (preg_match('/OS\ 4.2/i',$user_agent)) {
			$type = 'iphone42';
		} else if (preg_match('/OS\ 4/i',$user_agent)) {
			$type = 'iphone4';
		} else if (preg_match('/OS\ 3/i',$user_agent)) {
			$type = 'iphone3';
		} else {
			$type = 'iphone2'; 
		}
	} 
	else if (preg_match('/ipad/i',$user_agent)) {
		$type = 'ipad';
	}
	else if (preg_match('/android/i',$user_agent)) {
		if (preg_match('/Android\ 2.2/i',$user_agent)) {
			$type = 'android22';
		} else if (preg_match('/Android\ 2.1/i',$user_agent)) {
			$type = 'android21';
		} else if (preg_match('/Android\ 2.0/i',$user_agent)) {
			$type = 'android2'; 
		} else {
			$type = "android";
		}
    }
	else if (preg_match('/WebOS/i',$user_agent)) {
		$type = "palm";
	}
	else if (preg_match('/opera mini/i',$user_agent)) {
		$type = "opera_mini";
	} 
	else if (preg_match('/opera mobi/i',$user_agent)) {
		$type = "opera_mobile";
	}
	else if (preg_match('/blackberry/i',$user_agent)) {
		if (preg_match('/9800/i',$user_agent)) {
			$type = "blackberry9800";
		} else {
			$type = "blackberry";
		}
	}
	else if (preg_match('/webkit/i',$user_agent)) {
		$type = "webkit"; // catch generic webkit browsers, also future proofs a little
	}
	else if (preg_match('/(palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine|windows ce; ppc;|windows ce; smartphone;|windows ce; iemobile|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|pda|psp|treo)/i',$user_agent)) {
		$type = "smart_phone";
    }
    else if ((strpos($accept,'text/vnd.wap.wml') > 0) || (strpos($accept,'application/vnd.wap.xhtml+xml') > 0) || isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']) || in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex',
'anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','comp'=>'comp','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai',
'emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac',
'iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno',
'm1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21',
'mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki',
'nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600',
'raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams',
'sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-',
'telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','tosh'=>'tosh','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu',
'x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java',
'jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-',
'send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-'))) {
		$type = "feature_phone";
    }
    else {
		$type = "computer";
	}

    return $type;
  }

  # this returns the device family for use in choosing the templates to use for the device
  public static function classify_device_family() {
	$type = self::classify_device_type();
	
	// classify ipads as computers for the is_computer() checks
	if (($type == "computer") || ($type == "ipad")) {
		self::$is_computer = true;
	}
	self::$is_spider = ($type == "spider");
	
    return self::$phoneTable[$type];
  }

  public static function is_computer() {
    return self::$is_computer;
  }

  public static function is_spider() {
    return self::$is_spider;
  }

  public static $requireTable = array(
    "webkit" => "webkit",
    "touch" => "touch",
    "basic" => "basic"
  );

  public function requirePrefix() {
    return self::$requireTable[$this->phone];
  }
}

class webkitPage extends Page {
  public function __construct() {
    $this->phone = "webkit";
	$this->varnames = array();
  }
}

class touchPage extends Page {

  protected $navbar_image;
  protected $breadcrumb_root = False;
  protected $breadcrumbs = array();
  protected $last_breadcrumb;
  protected $breadcrumb_links;
  protected $extra_onload = "scrollTo(0,1);";
  protected $onorientationchange;
  protected $raw_js = array();
  protected $scalable = "yes";
  protected $fixed = False;

  public function __construct() {
    $this->phone = "touch";
    $this->varnames= array(
       "title", "header", "navbar_image", "stylesheets", "javascripts", "breadcrumb_links",
       "home", "breadcrumbs", "last_breadcrumb", "help_on", "footer", "footer_script",
       "extra_onload", "onorientationchange", "raw_js", "scalable", "fixed"
    );
  }

  public function fixed() {
    $this->fixed = True;
    return $this;
  }

  public function not_scalable() {
    $this->scalable = "no";
    return $this;
  }

  public function navbar_image($navbar_image) {
    $this->navbar_image = $navbar_image;
    return $this;
  }

  public function breadcrumbs() {
    $this->breadcrumbs = func_get_args();
    $this->last_breadcrumb = array_pop($this->breadcrumbs);
    $this->breadcrumb_links = array();
    return $this;
  }

  public function breadcrumb_links() {
    $tmp = func_get_args();
    for($cnt = 0; $cnt < count($tmp); $cnt++) {
      $this->breadcrumb_links[$cnt] = $tmp[$cnt];
    }
    return $this;
  }

  public function breadcrumb_home() {
    $this->home = True;
    return $this;
  }

  public function extra_onload($js) {
    $this->extra_onload .= " $js";
    return $this;
  }

  public function onorientationchange($js) {
    $this->onorientationchange = $js;
    return $this;
  }

  public function add_inline_script($js) {
    $this->raw_js[] = $js;
    return $this;
  }
}

class notIPhonePage extends Page {

  protected $extra_links = array();
  protected $help_links = array();
  protected $bottom_nav_links = array();

  public function __construct() {
    $this->varnames = array(
       "header", "title", "stylesheets", "extra_links", 
       "help_on", "help_links", "bottom_nav_links",
       "width1", "height1"
    );
  }

  public function extra_link($href, $text, $class=NULL) {
    $this->extra_links[] = array("url" => $href, "text" => $text, "class" => $class);
    return $this;
  }

  public function help_link($href, $text, $class=NULL, $phone=NULL) {
    $this->help_links[] = array("url" => $href, "text" => $text, "class" => $class, "phone" => $phone);
    return $this;
  }

  public function nav_link($href, $text) {
    $this->bottom_nav_links[] = array("url" => $href, "text" => $text);
    return $this;
  }
}

class basicPage extends notIPhonePage {
  protected $width1 = "48";
  protected $height1 = "19";

  protected $phone = "basic";  
}

?>
