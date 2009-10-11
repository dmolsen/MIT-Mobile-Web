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
$install_dir       = "/path/to/install/";         # the directory mobile web has been installed in, trailing slash is required
$ga_code		   = "UA-8296934-34";			  # Google Analytics Code

/* Institution & Org Info */
$inst_name         = "HEU";                       # name of educational institution. highly recommend an acronym.
$inst_name_full    = "Higher Ed University";      # full name of education institution
$org_name          = "Higher Ed Tech Office";     # name of organization supporting this instance of mobile web (shows in footer)

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

/* Shared iPhone Graphics Info */
$homescreen_icon   = "icon.png";      		   	  # bookmark graphic for iPhone homescreen. should be in web/ip/images
$homelink_icon	   = "homelink.png"; 			  # homelink breadcrumb graphic for iPhone. should be in web/ip/images

?>
