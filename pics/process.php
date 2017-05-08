<?php

$tmp = null;

$i = 1;
$page = 1;

$template = file_get_contents('/users/adam/Documents/misc.pics/template.html');

$glob = glob("/users/adam/Documents/misc.pics/pics/*");

$count = count($glob);

echo "\n\n";

foreach($glob as $filename) {
	
	$hash = md5_file($filename);
	
	echo $filename.': '.$hash."\n";
	
	if($filename !== '/users/adam/Documents/misc.pics/pics/'.$hash.'.jpg') {
		touch($filename);
		rename($filename, '/users/adam/Documents/misc.pics/pics/'.$hash.'.jpg');
	}
	else {
		touch($filename);
	}
	
	$tmp .= '<div class="grid-item"><a href="/pics/'.$hash.'.jpg"><img src="/pics/'.$hash.'.jpg" alt="misc pic"></a>'."</div>\n";
	
	if($i == $count) { // last page
		$page++;
		$template_tmp = $template;
		$template_tmp = str_replace('{img}', $tmp, $template_tmp);
		$template_tmp = str_replace('{next}', '<p>there are no more</p>', $template_tmp);
		$indexfile = $page - 1;
		file_put_contents('/users/adam/Documents/misc.pics/misc.pics/pages/'.$indexfile.'.html', $template_tmp);
		//exit;
	}
	
	if($i % 99 == 0) { // all other pages
		$page++;
		$template_tmp = $template;
		$template_tmp = str_replace('{img}', $tmp, $template_tmp);
		$template_tmp = str_replace('{next}', '<p><a href="/pages/'.$page.'">there are more</a></p>', $template_tmp);
		/*if($i == 99) {
			$indexfile = 'index';
		}
		else {
			$indexfile = $page - 1;
		}*/
		$indexfile = $page - 1;
		file_put_contents('/users/adam/Documents/misc.pics/misc.pics/pages/'.$indexfile.'.html', $template_tmp);
		$tmp = null;
	}
	$i++;
}

copy('/users/adam/Documents/misc.pics/misc.pics/pages/1.html', '/users/adam/Documents/misc.pics/misc.pics/index.html');

exec('cp /users/adam/Documents/misc.pics/* /users/adam/Documents/misc.pics/misc.pics/pics/');

exit;

?>