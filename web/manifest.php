<?php require("../config.gen.inc.php"); ?>
<?=header('Content-Type: text/cache-manifest');?>
CACHE MANIFEST
## v0.0.25, change version to get the cache to reload, this is webkit only.
##          on EDGE cache seems to be behaving poorly. won't kick in unless
##          users leave browser open while files download again.
##			NOT USING THIS IN PRODUCTION. TOO FLAKY.

## JavaScript Files
templates/webkit/javascripts/jqtouch/jquery.1.4.min.js
min/?g=js

## Stylesheets
min/?g=css

## Images
## Not done programmatically because their are lots of extra images in the theme
themes/<?=$theme?>/webkit/images/action-email-w.png
themes/<?=$theme?>/webkit/images/action-email.png
themes/<?=$theme?>/webkit/images/action-external-w.png
themes/<?=$theme?>/webkit/images/action-external.png
themes/<?=$theme?>/webkit/images/action-map-w.png
themes/<?=$theme?>/webkit/images/action-map.png
themes/<?=$theme?>/webkit/images/action-phone.png
themes/<?=$theme?>/webkit/images/action-phone2.png
themes/<?=$theme?>/webkit/images/button.png
themes/<?=$theme?>/webkit/images/button_clicked.png
themes/<?=$theme?>/webkit/images/back_button.png
themes/<?=$theme?>/webkit/images/back_button_clicked.png
themes/<?=$theme?>/webkit/images/body_background.jpg
themes/<?=$theme?>/webkit/images/chevron.png
themes/<?=$theme?>/webkit/images/favorite_selected.png
themes/<?=$theme?>/webkit/images/favorite_unselected.png
themes/<?=$theme?>/webkit/images/goldwv.png
themes/<?=$theme?>/webkit/images/info.png
themes/<?=$theme?>/webkit/images/wifi2.png
themes/<?=$theme?>/webkit/images/email-button.png
themes/<?=$theme?>/webkit/images/search-button.png
themes/<?=$theme?>/webkit/images/loading_yellow_bg.gif
themes/<?=$theme?>/webkit/images/toolbar_background.png
themes/<?=$theme?>/webkit/images/homescreen/bookmark.png
themes/<?=$theme?>/webkit/images/homescreen/da.png
themes/<?=$theme?>/webkit/images/homescreen/directory.png
themes/<?=$theme?>/webkit/images/homescreen/emergency.png
themes/<?=$theme?>/webkit/images/homescreen/events.png
themes/<?=$theme?>/webkit/images/homescreen/hours.png
themes/<?=$theme?>/webkit/images/homescreen/libraries.png
themes/<?=$theme?>/webkit/images/homescreen/links.png
themes/<?=$theme?>/webkit/images/homescreen/map.png
themes/<?=$theme?>/webkit/images/homescreen/prt.png
themes/<?=$theme?>/webkit/images/homescreen/soccer.png
themes/<?=$theme?>/webkit/images/homescreen/u92.png
themes/<?=$theme?>/webkit/images/homescreen/wvuedu.png
themes/<?=$theme?>/webkit/images/homescreen/wvutoday.png
themes/<?=$theme?>/webkit/images/homescreen/youtube.png
themes/<?=$theme?>/webkit/images/loading_big_grey_bg.gif

# All the sites/directories that need to be called by the system
# Needs to include all modules called by AJAX
NETWORK:
calendar/
emergency/
ga.php
gameday/
home/
hours/
libraries/
links/
map/
mobile-about/
news/
people/
prt/
radio/
sms/
templates/
youtube/
http://maps.google.com/
http://www.google-analytics.com/
http://maps.gstatic.com/
http://gg.google.com/
http://mt0.google.com/
http://mt1.google.com/