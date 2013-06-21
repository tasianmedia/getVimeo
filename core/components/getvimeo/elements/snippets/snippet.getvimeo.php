<?php
/**
 * A simple video retrieval Snippet for MODX Revolution
 *
 * @author David Pede <dev@tasianmedia.com>
 * @version 1.0.0-beta
 * @since June 12, 2013
 * @package getvimeo
 */

//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(E_ERROR);
 
$channel = !empty($channel) ? $channel : '';
$id = !empty($id) ? explode(',', $id) : array(''); // Receives CSV list, converts to array. Hardcode default: array('default')
$tpl = !empty($tpl) ? $tpl : '';
$tplAlt = !empty($tplAlt) ? $tplAlt : '';
$tplWrapper = !empty($tplWrapper) ? $tplWrapper : ''; // Blank default makes '&tplWrapper' optional
$sortby = !empty($sortby) ? $sortby : 'upload_date';
$sortdir = !empty($sortdir) && ($sortdir == "ASC") ? SORT_ASC : SORT_DESC; // If parameter is not empty AND equals 'ASC' assign 'SORT_ASC'
$toPlaceholder = !empty($toPlaceholder) ? $toPlaceholder : ''; // Blank default makes '&toPlaceholder' optional
$i = 1; //Starts row count at 1

if (!empty($channel)) {
	
	$output = '';
	$rowOutput = '';

	$videos = unserialize(file_get_contents("http://vimeo.com/api/v2/channel/$channel/videos.php"))
	or exit($modx->log(modX::LOG_LEVEL_ERROR, 'getVimeo() @ Resource ' . $modx->resource->get('id') . ' - Unable to find Channel: ' . $channel));
	
	if (!empty($sortby)) {
		foreach ($videos as $rows) {
			$array[] = $rows["$sortby"];
		}
		array_multisort($array, $sortdir, $videos);
	}
	foreach($videos as $video) {
		if (!empty($tpl)) {
			if (!empty($tplAlt)) {
				if($i % 2 == 0) { // Checks if row count can be divided by 2 (alt)
					$rowTpl = $tplAlt;
				}else{
					$rowTpl = $tpl;
				}
			}else{
				$rowTpl = $tpl;
			}
		}else{
			exit($modx->log(modX::LOG_LEVEL_ERROR, 'getVimeo() @ Resource ' . $modx->resource->get('id') . ' - &tpl is required'));
		}
		if (in_array("all", $id)){ //If &id is 'all' then output all vids in channel
			$array = (array) $video; //Add each video to an array
			$rowOutput .= $modx->getChunk($rowTpl,$array);
			$i++; //Increases row count by +1
		}else{
			if(in_array($video['id'], $id)){
				$array = (array) $video; //Add each video to an array
				$rowOutput .= $modx->getChunk($rowTpl,$array);
				$i++; //Increases row count by +1
			}
		}
	}
  if(!empty($rowOutput)) {
		if (!empty($toPlaceholder)) {
      $modx->setPlaceholder($toPlaceholder,$rowOutput); //Set '$toPlaceholder' placeholder 
			if (!empty($tplWrapper)) {
				$results = array('$toPlaceholder' => $rowOutput); //Convert '$rowOutput' to an array (getChunk needs array). Use '$toPlaceholder' as Key
				$output = $modx->getChunk($tplWrapper,$results); //Pass array to the '$tplWrapper' chunk
			}
    }else{
			$output = $rowOutput;
		}
	}
}else{
	exit($modx->log(modX::LOG_LEVEL_ERROR, 'getVimeo() @ Resource ' . $modx->resource->get('id') . ' - &channel is required'));
}
return $output;