<?php

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

# Use RSS to show emergency status for your campus
# Note: a service like e2campus offers an RSS feed
$show_rss = false;
$emergency_rss_url = "http://emergency.inst.edu/rss/";

# The phone numbers to show on the main emergency page
$main = array(
  i("3042932677", "Campus Police"),
  i("3042936924", "Health Sciences Safety Office")
);

# The other phone numbers to show related to emergency info
$others = array(
  i("3042933136", "Campus Police"),
  i("3042930111", "Campus Operator/Information"),
  i("3042934431", "Carruth Center for Counseling and Psychological Services"),
  i("3042936997", "Disability Services")
);

# Extra phone numbers a user might use
$show_extra = true; # this needs to be true if you want to show residences or schools
$extra = array(
  i("3042932121", "Admissions and Records"),
  i("3042935496", "ADA Office"),
  i("3042934731", "Alumni Association"),
  i("8009884263", "Athletic Ticket Office")
);

# Phone numbers of the residence halls
$show_res = true;
$residence = array(
  i("3042932840", "Arnold Hall"),
  i("3042932010", "Boreman North"),
  i("3042932010", "Boreman South")
);

# Phone numbers of the schools or colleges
$show_schools = true;
$schools = array(
  i("3042932395", "Davis College of Agriculture, Natural Resources & Design"),
  i("3042934661", "Eberly College of Arts & Sciences"),
  i("3042934092", "Business & Economics")
);

?>