<?php

// the iCal date format. Note the Z on the end indicates a UTC timestamp.
define('DATE_ICAL', 'Ymd\THis\Z');

// max line length is 75 chars. New line is \\n

$output = "BEGIN:VCALENDAR\r\n".
	"METHOD:PUBLISH\r\n".
	"VERSION:2.0\r\n".
	"PRODID:-//Jonathan Knapp//Tascal//EN\r\n".
	"X-WR-CALNAME:".$group->title."\r\n".
	"CALSCALE:GREGORIAN\r\n";

//loop over tasks
foreach ($group->tasks as $t) {
	// loop over events
	foreach ($t->events as $event) {
		$output .=
			"BEGIN:VEVENT\n" .
			"SUMMARY:" . $t->title . "\r\n".
			"UID:" . $event->eid ."\r\n".
			//"STATUS:" . "Confirmed" . "\r\n".
			"DTSTART:" . date(DATE_ICAL, strtotime($event->start)) . "\r\n".
			"DTEND:" . date(DATE_ICAL, strtotime($event->end)) . "\r\n".
			//"LAST-MODIFIED:" . date(DATE_ICAL) . "\r\n".
			//"LOCATION:" . "locationString" ."\r\n".
			"END:VEVENT\r\n";
	}
}

// close calendar
$output .= "END:VCALENDAR\r\n";

echo $output;
?>
