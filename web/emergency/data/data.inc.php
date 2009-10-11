<?

/* Emergency Info */
# WVU uses e2Campus for emergency alerts and we're using their RSS feed to populate info
$show_rss = true;
$emergency_rss_feed = "http://feeds.omnilert.net/rss/d014a0436fd6c76e17d4931495231bea/b8d5ae4de409bcc5b5977f77e4222413/10/3178a/2/";

# The phone numbers to show on the main emergency page
$main = array(
  i("3042932677", "Campus Police"),
  i("3042936924", "Health Sciences Safety Office"),
  i("8009880096", "WVU Emergency Line"),
  i("3042930111", "WVU General Information")
);

# The other phone numbers to show related to emergency info
$others = array(
  i("3042933136", "Campus Police"),
  i("3042930111", "Campus Operator/Information"),
  i("3042934431", "Carruth Center for Counseling and Psychological Services"),
  i("3042936997", "Disability Services"),
  i("3042933792", "Environment, Health & Safety"),
  i("3042935590", "Faculty-Staff Assistance Program"),
  i("3042936924", "Health Sciences Safety Office"),
  i("8009880096", "Parents Club Hotline"),
  i("3042934357", "Physical Plant"),
  i("3042932311", "Student Health Services"),
  i("3042934444", "Telephone Service Problems"),  
  i("8009880096", "WVU Emergency Line"),
  i("3042930111", "WVU General Information")
);

# Extra phone numbers a user might use
$show_extra = true; # this needs to be true if you want to show residences or schools
$extra = array(
  i("3042932121", "Admissions and Records"),
  i("3042935496", "ADA Office"),
  i("3042934731", "Alumni Association"),
  i("8009884263", "Athletic Ticket Office"),
  i("3042937029", "Center for Black Culture and Research"),
  i("3042937469", "Creative Arts Center Box Office"),
  i("3042936700", "Disability Services"),
  i("3042935691", "Extension & Public Service"),
  i("3042935242", "Financial Aid"),
  i("3042934491", "Housing & Residence Life"),
  i("3042917433", "Mountain Line Bus Service"),
  i("3042937469", "Mountainlair Box Office"), 
  i("3042932264", "New Student Orientation"),
  i("3042936997", "News & Information Services"),
  i("3042935502", "Parking Enforcement"),
  #i("3042935011", "PRT Maintenance"),
  i("3042935531", "President's Office"),
  i("3042934126", "Scholars Office"),
  i("3042935496", "Social Justice"),
  i("3042934006", "Student Accounts"),
  i("3042935811", "Student Affairs"),
  i("3042937529", "Student Recreation Center"),
  i("3042938028", "Trademark Licensing"),
  i("3042933489", "Visitors Center/Tour Info"),
  i("8002255982", "West Virginia Tourism Info"),
  i("3042844000", "WVU Foundation")
);

# Phone numbers of the residence halls
$show_res = true;
$residence = array(
  i("3042932840", "Arnold Hall"),
  i("3042932010", "Boreman North"),
  i("3042932010", "Boreman South"),
  #i("3042936798", "College Park, The Ridge"),
  i("3042934601", "Dadisman Hall"),
  i("3042932813", "Bennett Tower"),
  i("3042932814", "Braxton Tower"),
  i("3042932814", "Brooke Tower"),
  i("3042932813", "Lyon Tower"),
  i("3042937050", "Fieldcrest Hall"),  
  i("3042932010", "International House"),
  i("3042936170", "Lincoln Hall"),
  i("3042933116", "Pierpont Apartments"),
  i("3042938149", "Stalnaker"),
  i("3042933123", "Summit")
);

# Phone numbers of the schools or colleges
$show_schools = true;
$schools = array(
  i("3042932395", "Davis College of Agriculture, Natural Resources & Design"),
  i("3042934661", "Eberly College of Arts & Sciences"),
  i("3042934092", "Business & Economics"),
  i("3042934841", "Creative Arts"),
  i("3042932521", "School of Dentistry"),
  i("3042935695", "Engineering & Mineral Resources"),
  i("3042932100", "Honors College"),
  i("3042935703", "Human Resources & Education"),
  i("3042933505", "Perley Isaac Reed School of Journalism"),
  i("3042935304", "College of Law"),  
  i("3042936607", "School of Medicine"),
  i("3042934831", "School of Nursing"),
  i("3042935101", "School of Pharmacy"),
  i("3042933295", "College of Physical Activity & Sports Sciences"),
  i("3047886800", "Potomac State College of WVU"),
  i("2034423071", "WVU Institute of Technology")
);

?>