<?php

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/* Quick Configuration File for Mobile Web */

/* Technical Info */
$install_path      = "/path/to/install/";         # the directory mobile web has been installed in, trailing slash is required
$ga_code		   = "";			  			  # Google Analytics Code, highly highly highly recommended
$minifier_support  = false;						  # minifier support for css & js files. only set to true when you go to production mode
$min_cache_support = true;						  # cache support for the minifier. will only work if $minifier_support is set to true
$fed_cache_support = true;						  # cache support for the federated search. only caches the modules that support federated search and NOT results

/* MySQL Config Info */
$db_type           = 'mysqli';					  # can be any database that MDB2 supports (e.g. pgsql)
$db_host           = 'localhost';				  # host that your database is at
$db_username       = 'username';				  # username for your database user
$db_passwd         = 'passwd';					  # password for your database user
$db_name           = 'db';					  	  # name of your database

/* Institution & Org Info */
$inst_name         = "HEU";                       # name of educational institution. highly recommend an acronym.
$inst_name_full    = "Higher Ed University";      # full name of education institution
$org_name          = "Higher Ed U Tech Office";   # name of organization supporting this instance of mobile web (shows in footer)
$city_state		   = "Somewhere, WV";			  # city and state (or province, whatever) where your institution is located. shows on map results.

/* Theme Info */
$theme 			   = "heu";						  # the name of the theme folder for this install

/* Misc. Copy Configuration (where "copy" means text) */
$contact_addy      = "mobile@mail.inst.edu";      # email address to contact w/ questions
$mobile_web_addy   = "m.inst.edu";                # web address for the mobile web site
$main_site_addy    = "www.inst.edu";              # web address for your regular web site
$email_ending	   = "mail.inst.edu";             # ending email addy used on directory page example
$area_code         = "304";                       # area code for school
$exchange          = "293";                       # next three digits in a phone number for your school
$has_oc_prefix     = true;                        # does your school have a shorter, on-campus prefix?
$oncampus_prefix   = "3";						  # number for on-campus prefix
$has_dir_search_va = false;                       # does your school have voice-assisted directory search?
$dir_search_va_num = "304.293.0000";              # number for voice-assisted directory search
$dir_search_samp_l = "3042930000";                # long phone number example for directory search
$dir_search_samp_s = "30000";                     # short phone number example for directory search, leave empty if you don't want to use it

/* Shared Graphics for Touch Templates (not WebKit) */
$homescreen_icon   = "icon_h.png";      		  # bookmark graphic for iPhone homescreen. should be in web/templates/touch/images
$homelink_icon	   = "homelink_h.png"; 			  # homelink breadcrumb graphic for iPhone. should be in web/templates/touch/images

/* Mobile Web OSP Version */
$mosp_version 	   = "2.5.0";

/*******************************/
/* CURRENTLY DEPRECATED/BROKEN */
/*******************************/

/* MySQL or SQLite */
$db_use_sqlite     = false;  					  # if using mysql change this to false

/* SQLite Config */
$sqlite_path 	   = $install_path."db/development.sqlite3"; # file system path to your SQLite database

?>