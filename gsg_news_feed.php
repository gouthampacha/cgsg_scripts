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

    $count = 1;
     
    foreach($xml->channel->item as $entry) {
    	    // Allowing only 4 posts in the feed
       		if($count > 4) break;
       		else $count++;
            
       		$img_src = "";

            //$desc = simplexml_load_file($entry->description, null, LIBXML_NOCDATA);
            //HACK FOR THE CDATA SECTION
            //Converting the whole thing to a string and extracting src out of it
            str_replace(array('<\![CDATA[',']]>'), '', $entry->description);
            $desc = explode(' ', htmlspecialchars($entry->description));
            foreach ($desc as $cell) {
            	if (substr($cell, 0, 3) == "src"){
            	        $img_src = explode('=', $cell);
            	        break;
            	   	}
            }

            echo "<li>";
               echo "<a href='".$entry->link."' title='"."$entry->title'>";
                if(isset($img_src[1]) && $img_src[1] != "") {
                	    //Strip the htmlspecialchars induced
            			$pattern = '/&([#0-9A-Za-z]+);/';
						$img_link = preg_replace($pattern, '', $img_src[1]);
					echo  "<img  src= $img_link alt='$entry->title' height='187px' width='340px'>";
                } else {
                    echo "<img alt='' src='http://www.clemson.edu/students/cgsg/global/images/GSGLogo-Orange.jpeg' height='187px' width='340px'>"; 
                }
             echo "<p class='last'>" . $entry->title . "</p>";
             echo "</a>";
			 echo "</li>";
    }

    echo "<ul></ul>";

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