<?php

// the iCal date format. Note the Z on the end indicates a UTC timestamp.
define('DATE_ICAL', 'Ymd\THis\Z');

// max line length is 75 chars. New line is \\n

$output = "BEGIN:VCALENDAR\r\n".
	"VERSION:2.0\r\n".
	"METHOD:PUBLISH\r\n".
	"PRODID:-//Jonathan Knapp//Tascal//EN\r\n".
	"X-WR-CALNAME:".$group->title."\r\n".
	"CALSCALE:GREGORIAN\r\n";
	//~ "X-WR-TIMEZONE:America/Los_Angeles\r\n";

//loop over tasks
foreach ($group->tasks as $t) {
	// loop over events
	foreach ($t->events as $event) {
		$output .=
			"BEGIN:VEVENT\r\n" .
			"SUMMARY:" . $t->title . "\r\n".
			"UID:" . $group->gid."-".$t->tid."-".$event->eid ."\r\n".
			"DTSTART;TZID=".date('e', strtotime($event->start)).":".date(DATE_ICAL, strtotime($event->start))."\r\n".
			"DTEND;TZID=".date('e', strtotime($event->end)).":".date(DATE_ICAL, strtotime($event->end))."\r\n".
			"END:VEVENT\r\n";
	}
}

// close calendar
$output .= "END:VCALENDAR\r\n";

echo $output;
?>
