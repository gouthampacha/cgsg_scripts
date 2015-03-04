<?php

//Program lists top 4 upcoming events in the format required for our Events-script to parse and place on Homepage
//Author: Goutham Pacha Ravi <gpachar@g.clemson.edu>
//Webmaster: 2014-15

class GSG_Event
{
    public $title;
    public $date;
    public $time;
    public $location;
    public $event_link;
}
// Load and parse XML from Google Calendar feed
$xml = file_get_contents('http://www.google.com/calendar/feeds/clemson.gsg@gmail.com/public/basic');
$x = simplexml_load_string($xml);
// Extract desired data from XML feed
foreach ( $x->entry as $entry ) {
    $evt = new GSG_Event();
    $evt->title = (string) $entry->title;
    $evt->event_link = $entry->link[0]->attributes()->href;

    // Extract time, place, and description from content entry
    $parts = explode('<br />', $entry->content);
    foreach ( $parts as $part ) {
        $part = trim(str_replace('&nbsp;', '', $part));
        if ( strpos($part, 'When') === 0 ) {
            $part = substr($part, strlen('When: '));
            // Get date
            $matches = array();
            preg_match('/(.{3} .{3} [0-9]{1,2}, [0-9]{4})/', $part, $matches);
            $evt->date = $matches[1];
            // Get time
            // Some entries are formatted: date time to time
            // Others are: date time to date time
            // So remove all instances of the date string before searching for time
            $part = trim(str_replace('  ', ' ', str_replace($evt->date, '', $part)));
            $match = preg_match('/([0-9]{1,2}(:[0-9]{2})?[a|p]m to [0-9]{1,2}(:[0-9]{2})?[a|p]m)/', $part, $matches);
            if ( $match )
                $evt->time = $matches[1];
        }
        else if ( strpos($part, 'Where') === 0 )
            $evt->location = substr($part, strlen('Where: '));
    }

    $All_Events[] = $evt;
}


    // Sort events in chronological order
    function dateCompare(GSG_Event $e1, GSG_Event $e2) {
        return strtotime($e1->date) > strtotime($e2->date);
    }
    

    usort($All_Events, 'dateCompare');


    function beforeToday(GSG_Event $e) {
        // Midnight this morning
        $today = strtotime(date('F j, Y', time()));
        return $today > strtotime($e->date);
    }

    function todayOrAfter(GSG_Event $e) {
        // Midnight this morning
        $today = strtotime(date('F j, Y', time()));
        return $today <= strtotime($e->date);
    }
    //Not necessary to pull prior events
    $past = array_reverse(array_values(array_filter($All_Events, 'beforeToday')));
    $upcoming = array_values(array_filter($All_Events, 'todayOrAfter'));

    
    //Printing the next 4 events from now, in the format necessary

    $evtCount = 1;
    foreach ( $upcoming as $EVT ) {

        echo "<div class='event '>";
        echo "<div class='event-date'><span class='month'>".date('M', strtotime($EVT->date))."</span><span class='day'>".date('j', strtotime($EVT->date))."</span></div>";
        echo "<p class='event-title'><a href='".$EVT->event_link."'>".$EVT->title."</a></p>";
        echo "<p class='event-time'>". $EVT->time."</p>";
        echo "<p class='event-location'>".$EVT->location."</p>";
        echo "</div>";

    }



/*
$workshops = array();
foreach ( $x->entry as $entry ) {
    $w = new Workshop();
    $w->title = (string) $entry->title;
    // Extract time, place, and description from content entry
    $parts = explode('<br />', $entry->content);
    foreach ( $parts as $part ) {
        $part = trim(str_replace('&nbsp;', '', $part));
        if ( strpos($part, 'When') === 0 ) {
            $part = substr($part, strlen('When: '));
            // Get date
            $matches = array();
            preg_match('/(.{3} .{3} [0-9]{1,2}, [0-9]{4})/', $part, $matches);
            $w->date = $matches[1];
            // Get time
            // Some entries are formatted: date time to time
            // Others are: date time to date time
            // So remove all instances of the date string before searching for time
            $part = trim(str_replace('  ', ' ', str_replace($w->date, '', $part)));
            $match = preg_match('/([0-9]{1,2}(:[0-9]{2})?[a|p]m to [0-9]{1,2}(:[0-9]{2})?[a|p]m)/', $part, $matches);
            if ( $match )
                $w->time = $matches[1];
        }
        else if ( strpos($part, 'Where') === 0 )
            $w->location = substr($part, strlen('Where: '));
        else if ( strpos($part, 'Event Description') === 0 )
            $w->description = str_replace("\n", '<br>', substr($part, strlen('Event Description: ')));
    }
    // Link to Google Calendar event
    $w->event_link = $entry->link[0]->attributes()->href;
    // Only save events that are part of the Graduate Student Seminar series
    if ( strpos(strtolower($w->description), 'graduate student seminar') !== false )
        $workshops[] = $w;
}
// Sort workshops in chronological order
function dateCompare(Workshop $w1, Workshop $w2) {
    return strtotime($w1->date) > strtotime($w2->date);
}
usort($workshops, 'dateCompare');
function beforeToday(Workshop $w) {
    // Midnight this morning
    $today = strtotime(date('F j, Y', time()));
    return $today > strtotime($w->date);
}
function todayOrAfter(Workshop $w) {
    // Midnight this morning
    $today = strtotime(date('F j, Y', time()));
    return $today <= strtotime($w->date);
}
$past = array_reverse(array_values(array_filter($workshops, 'beforeToday')));
$upcoming = array_values(array_filter($workshops, 'todayOrAfter'));
echo '
<h3>Upcoming Workshops</h3>
';
foreach ( $upcoming as $w ) {
  echo '
<p><strong>' . $w->title . ':</strong> ' . date('F j, Y', strtotime($w->date)) . ' ' . $w->time . '<br>' . $w->location . '</p>
';
  echo '
<p>' . $w->description . '</p>
';
  echo '<br>';
}
echo '
<hr>
';
echo '
<h3>Past Workshops</h3>
';
foreach ( $past as $w ) {
  echo '
<p><strong>' . $w->title . ':</strong> ' . date('F j, Y', strtotime($w->date)) . ' ' . $w->time . '<br>' . $w->location . '</p>
';
  echo '
<p>' . $w->description . '</p>
';
  echo '<br>';
} */
?>
