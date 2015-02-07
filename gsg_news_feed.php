<?php
    
    $feed_url = 'http://blogs.clemson.edu/graduatestudentgovernment/feed/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $feed_url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);

    
    echo "<!DOCTYPE html> <html><head><title></title></head><body>";
    

    $xml = produce_XML_object_tree($data);

     
    foreach($xml->channel->item as $entry) {
            $desc = simplexml_load_string($entry->description, null, LIBXML_NOCDATA);
       
           echo "<ul>";
            echo "<li>";
                echo "<a href='".$entry->link."' title='"."$entry->title'>";
                if(!empty($desc->img)) {

                    echo "<img alt='' src=' ". $desc->img['src'][0]. "' height='187px' width='340px'>";
                } else {
                    echo "<img alt='' src='http://www.clemson.edu/students/cgsg/global/images/GSGLogo-Orange.jpeg' height='187px' width='340px'>"; 
                }
            echo "</li>";
           echo "</ul>";
           echo "<p class='last'>" . $entry->title . "</p>";
        
        
    }



    //Function to produce XML tree without dumping errors to the page
    function produce_XML_object_tree($raw_XML) {
    libxml_use_internal_errors(true);
    try {
        $xmlTree = new SimpleXMLElement($raw_XML);
    } catch (Exception $e) {
        // Something went wrong.
        $error_message = 'SimpleXMLElement threw an exception.';
        foreach(libxml_get_errors() as $error_line) {
            $error_message .= "\t" . $error_line->message;
        }
        trigger_error($error_message);
        return false;
    }
    return $xmlTree;
}


?>