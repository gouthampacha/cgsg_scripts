<?php
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://blogs.clemson.edu/graduatestudentgovernment/feed/');
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    $xml = new SimpleXMLElement($data, LIBXML_NOCDATA);

    echo "<ul>";
     
    foreach($xml->channel->item as $entry) {
        $desc = simplexml_load_string($entry->description);
        echo "<li>";
        if($desc->img) {

            echo "<img src=' ". $desc->img['src'][0]. "' height='200px'>";
        } else {
            echo "<img src='http://www.clemson.edu/students/cgsg/global/images/GSGLogo-Orange.jpeg' height='200px'>"; 
        }
        echo "<br /><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
        
        
    }
    echo "</ul>";


?>