<?php
	if (!is_dir('temp')){mkdir('temp');file_put_contents('temp/index.html', '');}
	// handle the post packing of files
	if ($_POST&&count($_POST)>1){
		set_time_limit (60);
		$post=array_map("strip_tags",$_POST);
		$filename='temp/'.$post['zipname'].'.zip';unset($post['zipname']);
		$tozip=array();
		foreach($post as $file){
			$temp=file_curl_contents($file);
			$basetempfilename='temp/'.basename($file);
			$extension=pathinfo($file,PATHINFO_EXTENSION);
			if (strtolower($extension)=='php'){str_ireplace('.php','.SECURED_PHP_FILE',$basetempfilename);}
			file_put_contents($basetempfilename,$temp);
			$tozip[]=$basetempfilename;
		}
		create_zip($tozip, $filename, true);  
		//once zip created, delete all temp files (avoid hacking use) except zip !
		$temp=glob('temp/*.*');
		foreach ($temp as $file){if (basename($file)!='index.html'&&basename($file)!=basename($filename)){unlink($file);}}

		header('location: '.$filename);
	}else{
		// kill all remaining temp files (including zip)
		$temp=glob('temp/*.*');
		foreach ($temp as $file){if (basename($file)!='index.html'){unlink($file);}}
	}

	// INIT
	$version='v1.4';
	$me='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$bookmarklet="<a class=\"btn\" href=\"javascript:javascript:(function(){var url = location.href;window.open('".$me."?p=' + encodeURIComponent(url)+'&ext=#ext','_blank','menubar=yes,height=600,width=1000,toolbar=yes,scrollbars=yes,status=yes');})();\" >#nom</a>";
	$icon='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAxhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDM0MiwgMjAxMC8wMS8xMC0xODowNjo0MyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDowRTY2OUM2NTY2NzgxMUUzQkE3QkJFQjk5OTU5OEYyMSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDowRTY2OUM2NjY2NzgxMUUzQkE3QkJFQjk5OTU5OEYyMSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjBFNjY5QzYzNjY3ODExRTNCQTdCQkVCOTk5NTk4RjIxIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjBFNjY5QzY0NjY3ODExRTNCQTdCQkVCOTk5NTk4RjIxIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+lLLB+gAACNpJREFUeNq8WnlsTWkUP60qpbai9tpD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';
	$noise='data:image/png;base64,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';
	$listextensions=array(
		'all files'=>'jpg,jpeg,gif,png,svg,mp3,ogg,mp4,flv,swf,css,js,php,html,htm,shtml,pdf,doc,odt,xml,json,txt,ini,zip,gz,rar,7z,tar',
		'images only'=>'jpg,jpeg,gif,png,svg',
		'music only'=>'mp3,ogg',
		'videos only'=>'mp4,flv',
		'flash only'=>'swf',
		'coding'=>'css,js,php,html,htm,shtml',
		'documents'=>'pdf,doc,odt,xml,json,txt,ini',
		'zip&co'=>'zip,gz,rar,7z,tar',
		);

	if (!empty($_GET['p'])){define('URL',strip_tags(urldecode($_GET['p'])));}else{define('URL','None');}
	
?>
<head>
	<title>Tipiaking <?php echo URL;?></title>
	<link rel="shortcut icon" href="<?php echo $icon;?>" />
    
	<style>
	*{box-sizing: border-box}
		html,body{padding:0;margin:0;font-family: Palatino, Georgia, Helvetica, sans-serif;}
		body{padding-top:100px;padding-bottom:70px;min-width:320px;min-height:320px;background:url('<?php echo $noise;?>') #eee;}
		header{position:fixed;top:0;width:100%;vertical-align:middle;margin:0;padding:10px; font-size:24px;color:#fff; text-shadow:0 1px 2px black;background:url('<?php echo $noise;?>') rgba(0,0,0,0.5);box-shadow:0 1px 2px rgba(0,0,0,0.5);}
		header img{vertical-align:middle;}
		footer{position:fixed;bottom:0;margin:0;margin-top:10px;padding:10px;width:100%; font-size:20px;color:#fff; text-shadow:0 -1px 2px black;background:url('<?php echo $noise;?>') rgba(0,0,0,0.5);box-shadow:0 1px 2px rgba(0,0,0,0.5);}
		footer a,header a{text-decoration:none;color:#bbd;padding-bottom:2px;}
		header a:hover{border-bottom:2px dashed #cce;color:#cce;}
		header form {display:inline-block;width:100%;}
		header form a{font-size:24;font-style:normal;}
		header form a:hover{text-decoration:none;}
		header form input[type=text]{display:inline-block;width:50%;padding:2px;font-size:20px; border-radius: 3px;margin-left:10px;}
		header form input[type=submit]{display:inline-block;width:50px;padding:2px;font-size:20px; border-radius: 3px;margin-left:10px;}
		header form select{display:inline-block;padding:2px;font-size:20px; border-radius: 3px;margin-left:10px;}
		h1{font-size:20;color:#888;text-shadow:0 1px 1px #fff;margin-left:20px;}
		li{list-style:none;padding:5px;margin-left:20px;}
		li:hover{background-color:#DDD;}
		li label{border-radius:3px;cursor:pointer;padding:4px;}
		input[type=checkbox]:checked+label{background-color:rgba(0,0,0,0.2);box-shadow:inset 0 1px 2px black;text-shadow:0 1px 1px white;}
		li a{display:inline-block;width:64px;text-align:center;font-family:courier;text-decoration:none;border-radius:2px; border:1px solid rgba(0,0,0,0.2);background-color:#EEE;color:#555;text-shadow:0 1px 1px white;box-shadow:0 1px 2px #444;padding:3px;}

		li.css a{background-color:#9F0;color:#250;box-shadow:0 1px 2px #250;}
		li.htm a,li.html a{background-color:#BF0;color:#450;box-shadow:0 1px 2px #450;}
		li.swf a{background-color:violet;color:darkviolet;box-shadow:0 1px 2px darkviolet;}
		li.php a{background-color:darkviolet;color:violet;box-shadow:0 1px 2px darkviolet;}
		li.svg a{background-color:#aaa;color:#444;box-shadow:0 1px 2px #444;}
		li.png a{background-color:#0f0;color:#050;box-shadow:0 1px 2px #050;}
		li.gif a{background-color:#beb;color:#454;box-shadow:0 1px 2px #232;}
		li.js a{background-color:#0FF;color:#099;text-shadow:0 1px 1px white;box-shadow:0 1px 2px #077;}
		li.jpg a, li.jpeg a{background-color:#af0;color:#450;box-shadow:0 1px 2px #450;}
		li.pdf a{background-color:darkred;color:red;box-shadow:0 1px 2px darkred;}
		li.mp4 a,li.flv a{background-color:blue;color:lightblue;box-shadow:0 1px 2px black;}
		li.mp3 a,li.ogg a{background-color:orange;color:maroon;box-shadow:0 1px 2px maroon;}
		li.zip a,li.rar a, li.tar a,li.gz a{background-color:#555;color:#eee;box-shadow:0 1px 2px black;}
		li.xml a,li.txt a, li.ini a,li.json a{background-color:#aaa;color:#444;text-shadow:0 1px 1px white;box-shadow:0 1px 2px #333;}
		.error{text-align:center;color:red;}
		footer .btn,form input[type=submit] {
			margin:1px;
		  background: #3498db;
		  background-image: linear-gradient(to bottom, #3498db, #2980b9);
		  -webkit-border-radius: 3;
		  -moz-border-radius: 3;
		  border-radius: 3px;
		  font-family: Arial;
		  color: #ffffff;
		  font-size: 14px;
		  padding: 3px 10px 3px 10px;
		  text-decoration: none;
		  box-shadow:0 1px 1px blue;
		}

		.btn:hover {
		  background: #3cb0fd;
		  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
		  text-decoration: none;
		}
		form input.tipiakselected{margin:auto;display:block;font-size:20px;}
		hr{border: 1px solid #888;}
	</style>
</head>
<body><header> 
	<form action="#" method="get"><img src="<?php echo $icon;?>"/> Tipiaking 
		<?php 
			if (URL!='None'){echo '<input value="'.URL.'" type="text" name="p"/>'.makeselect().'<input type="submit" value="GO!"/> <a title="Visit page..." href="'.URL.'"> &#9656;</a></form>'; }
			else{echo '<input placeholder="Url to tipiak" type="text" name="p"/>'.makeselect().'<input type="submit" value="GO!"/>';}
		?>
	</form> 
</header>
<?php
	// prepare the extension regex ------------------------------------
	if (!empty($_GET['ext'])){   
		$regex=strip_tags($_GET['ext']); 
	}else{ 
		$regex=$listextensions['all files'];
	} 
	$regex=explode(',',$regex);
	$temp='';
	foreach($regex as $r){$temp.='\.'.$r.'|';}
	$temp=substr($temp,0,strlen($temp)-1);
	$reg='#(?<=")([^\'"\?]+('.$temp.'))(?=["\?])|(?<=\')([^\'"\?]+('.$temp.'))(?=[\'\?])|(?<=url\()([^\'"\?]+('.$temp.'))(?=[\)])#i';

	// -----------------------------------------------------------------

	// functions -------------------------------------------------------
	ini_set('allow_url_fopen', '1');
	function aff($a,$stop=true,$line='?'){echo 'Arret a la ligne '.$line.' du fichier '.__FILE__.'<pre>';print_r($a);echo '</pre>';if ($stop){exit();}}	
	function makeselect(){
		global $listextensions;
		$temp= '<select name="ext">';
		foreach ($listextensions as $nom=>$ext){
			$temp.= '<option value="'.$ext.'">'.$nom.'</option>';
		}
		$temp.= '</select>';
		return $temp;
	}
	function makebookmarklets(){
		global $listextensions,$bookmarklet;
		foreach ($listextensions as $nom=>$ext){
			echo str_replace(array('#nom','#ext'),array($nom,$ext),$bookmarklet);
		}
	}
	function remote_file_exists($url){if(@fclose(@fopen($url, 'r'))){return true;}else {return false;}}
	function filterchars($string){return preg_replace('#([^a-zA-Z0-9-_\.]+)#','',$string);}
	function file_curl_contents($url,$pretend=true){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Charset: UTF-8'));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		if (!ini_get("safe_mode") && !ini_get('open_basedir') ) {curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);}
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		if ($pretend){curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:19.0) Gecko/20100101 Firefox/19.0');}    
		//curl_setopt($ch, CURLOPT_REFERER, random_referer());// notez le referer "custom"
		$data = curl_exec($ch);
		$response_headers = curl_getinfo($ch);
		// Google seems to be sending ISO encoded page + htmlentities, why??
		if($response_headers['content_type'] == 'text/html; charset=ISO-8859-1') $data = html_entity_decode(iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $data)); 
		curl_close($ch);
		return $data;
	}
	function curl_get_file_size( $url ) {
		 $ch = curl_init($url);

		     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		     curl_setopt($ch, CURLOPT_HEADER, TRUE);
		     curl_setopt($ch, CURLOPT_NOBODY, TRUE);

		     $data = curl_exec($ch);
		     $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

		     curl_close($ch);
		     if ($size!=-1){return $size;}else{return false;}
		}
	function fuck_slashes($string){return preg_replace('#(?<=[^:])//#','/',stripslashes($string));}
	function try_autocomplete_relatives_url($url){
		if ($url!=''){
			$url=fuck_slashes($url);
			$data_url=parse_url($url);
			if (!isset($data_url['path'])){return false;}
			if (isset($data_url['scheme'])&&$data_url['scheme']!=''){
				return $url;
			}else{
				extract(parse_url(URL));
				$scheme=$scheme.'://';
				if (substr($data_url['path'],0,1)!='/'){$data_url['path']='/'.$data_url['path'];}
				return $scheme.$host.$data_url['path'];
			}
		}
		return false;
	}
	function create_zip($files = array(),$destination = '',$overwrite = false) {  
  	    if(file_exists($destination) && !$overwrite) { return false; } 
	    $valid_files = array();  
	    if(is_array($files)) {  
	        foreach($files as $file) {  
	            if(file_exists($file)) {  
	                $valid_files[] = $file;  
	            }  
	        }  
	    }  
	    if(count($valid_files)) {  
	        $zip = new ZipArchive();  
	        if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {  
	            return false;  
	        }  
	        foreach($valid_files as $file) {  
	            $zip->addFile($file,$file);  
	        }  	        
	        $zip->close();  	          
	        return file_exists($destination);  
	    }else{ return false; }  
	}
	// -----------------------------------------------------------------






if (URL!='None'){
	echo '<form action="#" method="POST" name="tipiak"><input type="hidden" name="zipname" value="'.filterchars(URL).'"/>';
	if ($page=file_curl_contents(URL)){
		//TODO: tester les iframes et charger puis ajouter leur contenu à la suite de $page
		if (preg_match_all($reg,$page,$results)){
			$results=array_unique($results[0]);
			echo'<h1>'.count($results).' files tipiakable.<input type="button" onclick="selunselall(true)" value="&#10004;" title="select all"/><input type="button" onclick="selunselall(false)" value="&#10008;" title="unselect all"/></h1>';
	
			foreach ($results as $key=>$val){
				if ($val=try_autocomplete_relatives_url($val)){
					//if (remote_file_exists($val)){
						$extension=pathinfo($val,PATHINFO_EXTENSION);
						if ($length=curl_get_file_size($val)){
							
							$length= '( '.round(($length/1024),2).' ko)';
						}else{$length='(no size)';}
						if (!empty($extension)){
							echo '<li class="'.$extension.'"><a href="'.$val.'" title="Download" download="'.strip_tags(basename($val)).'">'.$extension.'</a> - <input type="checkbox" name="file'.$key.'" id="file'.$key.'" value="'.$val.'"/><label title="select to make a zip" for="file'.$key.'">'.$val.' <em>'.$length.'</em></label></li>
							';
						}
					//}
				}
			}

		}else{echo '<p class="error">no tipiakable data</p>';}
	}else{echo '<p class="error">no file access</p>';}
	echo '<hr/><input class="tipiakselected" type="submit" value="Tipiak all selected"/></form>';
}else{echo '<p class="error">no URL to process...</p>';}


?>
<footer>
	<span style="float:left">Bookmarklets: <?php makebookmarklets();?> </span>
	<span style="float:right"><?php echo $version;?> Cod&#233;e &#224; la <em>va-comme-jte-pousse</em> par <a href="http://www.warriordudimanche.net/article217/tipiak-recuperer-les-ressources-d-une-page-web">Bronco</a></span>
	<div style="clear:both"></div>
</footer>
<script>
	function selunselall(trufal){
		var checkboxes = new Array(); 
  		checkboxes = document['tipiak'].getElementsByTagName('input');
	    for (var i=0; i<checkboxes.length; i++)  {		   
		      checkboxes[i].checked = trufal;
		}
	}
</script>
</body>
