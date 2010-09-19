<? require("../../config.gen.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="title" content="WVU Mobile Web" />
<meta name="description" content="An on-the-go mobile-friendly website for accessing West Virginia University information and services." />
<link rel="image_src" href="/about/images/facebook_share.jpg" />
<title>WVU Mobile Web | West Virginia University</title>

<link href="stylesheets/styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?=$ga_code?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>




<body id="index">

	<div id="masthead">
		<div class="wrap">
			<a href="http://www.wvu.edu/" id="logo"><span>West Virginia University</span></a>
			<ul>
				<li class="first"><a href="http://www.wvu.edu/SiteIndex/">A-Z Site Index</a></li>
				<li><a href="http://www.wvu.edu/CampusMap/">Campus Map</a></li>
				<li><a href="http://directory.wvu.edu/">Directory</a></li>
				<li><a href="mailto:<%= email_address -%>">Contact Us</a></li>
				<li class="last"><a href="http://www.wvu.edu/">WVU Home</a></li>
			</ul>
		</div>
	</div>

	<div id="container" class="container_12">


	<div id="header" class="grid_12">

		<h1>WVU Mobile Web</h1>
		
		<div class="tagline grid_6 suffix_6 alpha omega">Get essential WVU information &amp; services anytime, anywhere on your mobile device.</div>
		

		<!--<div class="preview grid_6 alpha omega">
			&raquo; <a href="#">Click here to preview the site on your desktop or laptop.</a>
		</div>-->

		<div class="clear">
			
		</div>
		<div class="grid_6 alpha omega">
			<h2>How do I use it?</h2>
			<p>On your web-capable mobile device such as an iPhone, Smartphone, or PDA, launch your web browser and go to <a href="http://m.wvu.edu/" style="font-weight: bold; background-color: #dfdede; padding: 2px 3px 2px 3px;">m.wvu.edu</a>. You will need a web/data plan from your carrier or a WiFi connection. You can also <a href="/home/" style="font-weight: bold; background-color: #dfdede; padding: 2px 3px 2px 3px;">preview</a> the smartphone version of the site from your desktop or laptop.</p>
				
			<p><br /><br /><a href="http://itunes.apple.com/WebObjects/MZStore.woa/wa/viewSoftware?id=325958194&mt=8"><img src="images/iwvu_ad.jpg" style="margin-left: 20px;" border="0" alt="Have an iPhone or iPod Touch? You can also check out WVU’s official iPhone app, iWVU. Available on iTunes." /></a></p>
		</div>
		
	</div>


	<div id="main" class="grid_12">
<h4>Features:</h4>
		<div class="widget grid_6 alpha">
		
		<div class="grid_2 alpha">
			<img src="images/emergency.jpg" alt="Screenshot of Emergency Info View" />
		</div>
			
		<div id="grid_4 omega">
			<h2>Emergency Info</h2>

			<p>Learn about any emergencies on campus, and get one-click access to campus police, medical services and other emergency phone numbers.</p>
			
		</div>
			
			<div class="clear">
				
			</div>
			<div class="grid_2 alpha">
				<img src="images/map.png" alt="Screenshot of Maps View" />
			</div>
			<div id="grid_4 omega">
			<h2>Campus Map</h2>
			
			<p>Find buildings with this interactive live map. Search by building name, code or address. You can also browse by different categories like dining locations, computer labs, or athletic facilities.</p>
			</div>
			<div class="clear">
				
			</div>
			
			<div class="grid_2 alpha">
				<img src="images/prt.jpg" alt="Screenshot of PRT View" />
			</div>

			<div id="grid_4 omega">
				<h2>PRT Status</h2>

				<p>Check to see the status of the PRT as you head to or from class.</p>
			</div>

				
			
			
		</div>
		<div class="widget grid_6 omega">
	
			<div class="grid_2 alpha">
				<img src="images/athletics.jpg" alt="Screenshot of Athletics View" />
			</div>

			<div id="grid_4 omega">
				<h2>Athletics</h2>

				<p>Find out which athletic events are coming up as well as get quick access to team news, stats, and rosters.</p>
			</div>

				<div class="clear">

				</div>
				<div class="grid_2 alpha">
					<img src="images/calendar.jpg" alt="Screenshot of Calendar View" />
				</div>
				<div id="grid_4 omega">
				<h2>Events Calendar</h2>

				<p>Find out what's going on around campus. Search by keyword and time period, or browse by category.</p>
				</div>
				<div class="clear">

				</div>
				<div class="grid_2 alpha">
					<img src="images/directory.jpg"  alt="Screenshot of Directory View" />
				</div>
				<div id="grid_4 omega">
				<h2>People Directory</h2>

				<p>Find students, faculty and staff at WVU by searching part or all of their name, email address, or phone number. Get one-click access to call or email them.</p>
				</div>
		</div>
		
	
	</div>
	
	
	<div class="clear">

	</div>

	<div id="footer">
		<div class="grid_3 suffix_9">
		
		</div>


		<div class="clear">

		</div>

		<div id="credits" class="grid_8">
	&copy; <?=date("Y");?> West Virginia University. <span class="designcredits">Site design by <a href="http://webservices.wvu.edu/">WVU Web Services</a>.</span><br />
		West Virginia University is an Equal Opportunity/Affirmative Action Institution.

		</div>

		<div id="icons" class="grid_4 shared">
			<ul>
				<li id="mix">
					<a href="http://mix.wvu.edu/"><img src="http://slate.wvu.edu/themes/shared/images/icon_mix.gif" alt="MIX" /></a>
				</li>
				<li id="youtube">
					<a href="http://www.youtube.com/westvirginiau"><img src="http://slate.wvu.edu/themes/shared/images/icon_youtube.gif" alt="WVU on YouTube" /></a>
				</li>
				<li id="twitter">
					<a href="http://twitter.wvu.edu/"><img src="http://slate.wvu.edu/themes/shared/images/icon_twitter.gif" alt="WVU on Twitter" /></a>
				</li>
				<li id="facebook">
					<a href="http://facebook.wvu.edu/"><img src="http://slate.wvu.edu/themes/shared/images/icon_facebook.gif" alt="WVU on Facebook" /></a>
				</li>
				<li id="itunesu">
					<a href="http://itunes.wvu.edu/"><img src="http://slate.wvu.edu/themes/shared/images/icon_itunesu.gif" alt="iTunes U" /></a>
				</li>	
				<li id="give">
					<a href="http://www.wvuf.org/"><img src="http://slate.wvu.edu/themes/shared/images/icon_give.gif" alt="Give" /></a>
				</li>
				<li id="wvualert">
					<a href="http://emergency.wvu.edu/alert/"><img src="http://slate.wvu.edu/themes/shared/images/icon_wvualert.gif" alt="WVU Alert" /></a>
				</li>
				<li id="trak">
					<a href="http://careerservices.wvu.edu/Home/mountaineertrak_logins"><img src="http://slate.wvu.edu/themes/shared/images/icon_trak.gif" alt="Mountaineer Trak" /></a>
				</li>

		</ul>
		</div> <!-- icons -->
	</div>

</div>
</body>
</html>
