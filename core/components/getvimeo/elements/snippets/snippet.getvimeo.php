<?php
/**
 * A simple video retrieval Snippet for MODX Revolution.
 *
 * @author David Pede <dev@tasianmedia.com>
 * @version 1.0.1-pl
 * @released July 01, 2013
 * @since June 12, 2013
 * @package getvimeo
 *
 *getVimeo is free software; you can redistribute it and/or modify it under the
 *terms of the GNU General Public License as published by the Free Software
 *Foundation; either version 3 of the License, or any later version.

 *getVimeo is distributed in the hope that it will be useful, but WITHOUT ANY
 *WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 *A PARTICULAR PURPOSE. See the GNU General Public License for more details.

 *You should have received a copy of the GNU General Public License along with
 *getVimeo; if not, write to the Free Software Foundation, Inc., 59 Temple
 *Place, Suite 330, Boston, MA 02111-1307 USA
 */

/* set default properties */
$channel = !empty($channel) ? $channel : '';
$id = !empty($id) ? explode(',', $id) : array(''); // Receives CSV list, converts to array. Hardcode default: array('default')
$tpl = !empty($tpl) ? $tpl : '';
$tplAlt = !empty($tplAlt) ? $tplAlt : '';
$tplWrapper = !empty($tplWrapper) ? $tplWrapper : ''; // Blank default makes '&tplWrapper' optional
$sortby = !empty($sortby) ? $sortby : 'upload_date';
$sortdir = !empty($sortdir) && ($sortdir == "ASC") ? SORT_ASC : SORT_DESC; // If parameter is not empty AND equals 'ASC' assign 'SORT_ASC'
$toPlaceholder = !empty($toPlaceholder) ? $toPlaceholder : ''; // Blank default makes '&toPlaceholder' optional
$idx = 0; //Starts index at 0

$output = '';

if (!empty($channel)) {

  $rowOutput = '';

  $videos = unserialize(file_get_contents("http://vimeo.com/api/v2/channel/$channel/videos.php"))
  or $modx->log(modX::LOG_LEVEL_ERROR, 'getVimeo() @ Resource ' . $modx->resource->get('id') . ' - Unable to find Channel: ' . $channel);
	
  if (!empty($sortby)) {
    foreach ($videos as $rows) {
      $array[] = $rows["$sortby"];
    }
    array_multisort($array, $sortdir, $videos);
  }
  foreach($videos as $video) {
    if (!empty($tpl)) {
      if (!empty($tplAlt)) {
        if($idx % 2 == 0) { // Checks if index can be divided by 2 (alt)
          $rowTpl = $tpl;
        }else{
          $rowTpl = $tplAlt;
        }
      }else{
        $rowTpl = $tpl;
      }
    }else{
      $modx->log(modX::LOG_LEVEL_ERROR, 'getVimeo() @ Resource ' . $modx->resource->get('id') . ' - &tpl is required');
    }
    if (in_array("all", $id)){ //If &id is 'all' then output all videos in channel
      $array = (array) $video; //Add each video as an array
      $rowOutput .= $modx->getChunk($rowTpl,$array);
      $idx++; //Increases index by +1
    }else{
      if(in_array($video['id'], $id)){
        $array = (array) $video; //Add each video as an array
        $rowOutput .= $modx->getChunk($rowTpl,$array);
        $idx++; //Increases index by +1
      }
    }
  }
  if(!empty($rowOutput)) {
    $modx->setPlaceholder('total',$idx); //Set 'total' placeholder 
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
  $modx->log(modX::LOG_LEVEL_ERROR, 'getVimeo() @ Resource ' . $modx->resource->get('id') . ' - &channel is required');
}
return $output;
