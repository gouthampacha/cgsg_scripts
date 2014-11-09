<?php
	function listdirs($dir) {
	    static $alldirs = array();
	    $dirs = glob($dir . '/*', GLOB_ONLYDIR);
	    if (count($dirs) > 0) {
	        foreach ($dirs as $d) $alldirs[] = $d;
	    }
	    foreach ($dirs as $dir) listdirs($dir);
	    return $alldirs;
	}

	$directory_list = listdirs('http://media.clemson.edu/cgsg/');
	print_r($directory_list);
?>