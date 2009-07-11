function jumpSchedule(objSelect) {
// Use the value of the 'jump to schedule loop' select control to jump to a different loop in the displayed schedule -- this is just a placeholder
	if(objSelect) {
		alert("The schedule table would now update to show the scheduled route loop beginning at " + objSelect.value);
	}
	location.href = "#scrolldown";
	objSelect.selectedIndex=0;
}

function updateSchedule() {
// Update the schedule's inner HTML
	var objSchedule = document.getElementById("schedule");
	if(objSchedule) {
		showLoadingMsg("schedule");
		// Stub -- this is a placeholder for future AJAX function
			setTimeout("fakeAjaxSchedule()",1000);
		// End stub
	}
	var objTimestamp = document.getElementById("timestamp");
	if(objTimestamp) {
		// Stub -- this is a placeholder for future AJAX function
			objTimestamp.innerHTML = "GPS updated 17:12:15";
		// End stub
	}
	var objMap = document.getElementById("map");
	var objMapImage = document.getElementById("mapimage");
	if(objMap && objMapImage) {
		showLoadingMsg("map");
		// Stub -- this is a placeholder for future AJAX function
			setTimeout("fakeAjaxImage()",1000);
		// End stub
	}
}

function fakeAjaxSchedule() {
// Fake. AJAX. Schedule.
	var strScheduleHTML = "";
	strScheduleHTML += "<tr>";
	strScheduleHTML += "	<th>Stop</th>";
	strScheduleHTML += "	<th>Time*</th>";
	strScheduleHTML += "</tr>";
	strScheduleHTML += "<tr>";
	strScheduleHTML += "	<td><span class=\"sid\">A.</span> 84 Mass Ave.</td>";
	strScheduleHTML += "	<td>17:28</td>";
	strScheduleHTML += "</tr>";
	strScheduleHTML += "<tr>";
	strScheduleHTML += "	<td><span class=\"sid\">B.</span> Mass Ave/Beacon</td>";
	strScheduleHTML += "	<td>17:31</td>";
	strScheduleHTML += "</tr>";
	strScheduleHTML += "<tr>";
	strScheduleHTML += "	<td><span class=\"sid\">C.</span> 487 Comm Ave</td>";
	strScheduleHTML += "	<td>17:33</td>";
	strScheduleHTML += "</tr>";
	strScheduleHTML += " <tr>";
	strScheduleHTML += "	<td><span class=\"sid\">D.</span> 64 Bay State (TXI)</td>";
	strScheduleHTML += "	<td>17:36</td>";
	strScheduleHTML += "</tr>";
	strScheduleHTML += "<tr class=\"current\">";
	strScheduleHTML += "	<td><span class=\"sid\">E.</span> 111 Bay State (SH)</td>";
	strScheduleHTML += "	<td>17:13</td>";
	strScheduleHTML += "</tr>";
	strScheduleHTML += "<tr>";
	strScheduleHTML += "	<td><span class=\"sid\">F.</span> 478 Comm Ave</td>";
	strScheduleHTML += "	<td>17:17</td>";
	strScheduleHTML += "</tr>";
	strScheduleHTML += "<tr>";
	strScheduleHTML += "	<td><span class=\"sid\">G.</span> 450 Beacon St (PLP)</td>";
	strScheduleHTML += "	<td>17:21</td>";
	strScheduleHTML += "</tr>";
	strScheduleHTML += "<tr>";
	strScheduleHTML += "	<td><span class=\"sid\">H.</span> Beacon/Mass Ave</td>";
	strScheduleHTML += "	<td>17:24</td>";
	strScheduleHTML += "</tr>";
	strScheduleHTML += "<tr>";
	strScheduleHTML += "	<td><span class=\"sid\">I.</span> 77 Mass Ave</td>";
	strScheduleHTML += "	<td>17:26</td>";
	strScheduleHTML += "</tr>";
	document.getElementById("schedule").innerHTML = strScheduleHTML;
}

function fakeAjaxImage() {
// Fake. AJAX. Image.
	var strImageHTML = "<img src=\"routes/boston-daytime-e.gif\" width=\"240\" height=\"240\" alt=\"Map\" id=\"mapimage\" />";
	document.getElementById("map").innerHTML = strImageHTML;
}