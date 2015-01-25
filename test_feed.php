<?php
	$rss = new DOMDocument();
	$rss->load('http://blogs.clemson.edu/graduatestudentgovernment/feed/');
			// $html = $rss->saveHTML();
			// echo $html;
	$feed = array();
	foreach ($rss->getElementsByTagName('item') as $node) {
	    $item = array (
	        'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
	        'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
	        'description' => $node->getElementsByTagName('description')->item(0)->nodeValue,
	        );
	    array_push($feed, $item);
	}
	$limit = 10;
	for($x=0;$x<$limit;$x++) {
	    $title = str_replace(' & ', ' & ', $feed[$x]['title']);
	    $link = $feed[$x]['link'];
	    $image = $feed[$x]['description']['img'];
	    var_dump($image)

?>
	<ul>
	    <li class="thumb">
	        <a href="<?php echo $link; ?>" class="title" target="_blank">
	        	<?php 
	        		if($image) var_dump($image); //"<img src=\"$image \""; 
	        	?>

	        </a>
	    </li>
	    <li><a href="<?php echo $link; ?>" class="title" target="_blank"><?php echo $title; ?></a></li>
	</ul> 
	<?php } 
	?>