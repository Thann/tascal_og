<?php

// the iCal date format. Note the Z on the end indicates a UTC timestamp.
define('DATE_ICAL', 'Ymd\THis\Z');

// max line length is 75 chars. New line is \\n

$output = "BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0
PRODID:-//Jonathan Knapp//Tascal//EN\n";

//loop over tasks
foreach ($group->tasks as $t) {
	// loop over events
	foreach ($t->events as $event) {
		$output .=
			"BEGIN:VEVENT\n" .
			"SUMMARY:" . $t->title . "\n".
			"UID:" . $event->eid ."\n".
			"STATUS:" . "TRUE" . "\n".
			"DTSTART:" . date(DATE_ICAL, strtotime($event->start)) . "\n".
			"DTEND:" . date(DATE_ICAL, strtotime($event->end)) . "\n".
			"LAST-MODIFIED:" . date(DATE_ICAL) . "\n".
			"LOCATION:" . "locationString" ."\n".
			"END:VEVENT\n";
	}
}
 
// close calendar
$output .= "END:VCALENDAR";
 
echo $output;
 
?>
