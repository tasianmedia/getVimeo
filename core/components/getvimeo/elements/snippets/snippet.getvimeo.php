<?php
/**
 * A simple video retrieval Snippet for MODX Revolution.
 *
 * @author David Pede <dev@tasianmedia.com> <https://twitter.com/davepede>
 * @version 1.1.0-pl
 * @released October 16, 2013
 * @since June 12, 2013
 * @package getvimeo
 *
 * Copyright (C) 2013 David Pede. All rights reserved. <dev@tasianmedia.com>
 *
 * getVimeo is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or any later version.
 *
 * getVimeo is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * getVimeo; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

/* set default properties */
$channel = !empty($channel) ? $channel : '';
$id = !empty($id) ? explode(',', $id) : ''; // Receives CSV list, converts to array. Hardcode default: array('default')
$tpl = !empty($tpl) ? $tpl : '';
$tplAlt = !empty($tplAlt) ? $tplAlt : '';
$tplWrapper = !empty($tplWrapper) ? $tplWrapper : ''; // Blank default makes '&tplWrapper' optional
$sortby = !empty($sortby) ? $sortby : 'upload_date';
$sortdir = !empty($sortdir) && ($sortdir == "ASC") ? SORT_ASC : SORT_DESC; // If parameter is not empty AND equals 'ASC' assign 'SORT_ASC'
$toPlaceholder = !empty($toPlaceholder) ? $toPlaceholder : ''; // Blank default makes '&toPlaceholder' optional

$limit = isset($limit) ? (integer) $limit : 0;
$offset = isset($offset) ? (integer) $offset : 0;
$totalVar = !empty($totalVar) ? $totalVar : 'total';
$total = 0;

$output = '';

if (!empty($channel)) {

  $url = array();
  $page = 1;

  do {
     $pagedata = unserialize(file_get_contents("http://vimeo.com/api/v2/channel/$channel/videos.php?page=$page"))
     or $modx->log(modX::LOG_LEVEL_ERROR, 'getVimeo() - Unable to find Channel: ' . $channel);
     $url = array_merge($url,$pagedata);
     $page++;
  } while ((count($pagedata)) == 20 && $page <= 3);

  if (!empty($id)) {
    if (!empty($tpl)) {
      /* ADD REQUESTED VIDEOS TO ARRAY */
      if (in_array("all", $id)) { //If &id is 'all' then output all videos in channel
        foreach($url as $data) {
          $videos[] = $data;
        }
      }else{
        foreach($url as $data) {
          if (in_array($data['id'], $id)) {
            $videos[] = $data;
          }
        }
      }
      if (isset($videos)) {
        /* SORT THE ARRAY */
        if (!empty($sortby)) {
          foreach ($videos as $rows) {
            $array[] = $rows["$sortby"];
          }
          array_multisort($array, $sortdir, $videos);
        }
        /* SETUP GETPAGE */
        $total = count($videos);
        $modx->setPlaceholder($totalVar, $total);
        $itemIdx = 0;
        $idx = 0;
        /* PROCESS THE ARRAY READY FOR MODX */
        foreach($videos as $video) {
          if ($idx >= $offset) {
            if (!empty($tplAlt)) {
              if($idx % 2 == 0) { // Checks if index can be divided by 2 (alt)
                $rowTpl = $tpl;
              }else{
                $rowTpl = $tplAlt;
              }
            }else{
              $rowTpl = $tpl;
            }
            $itemIdx++; //Increases item index by +1
            $modx->setPlaceholder('idx',$idx+1);
            $results[] = $modx->getChunk($rowTpl,$video);
            if ($limit > 0 && $itemIdx+1 > $limit) break;
          }
          $idx++;
        }
        if(!empty($results)) {
          $results = implode("\n", $results);
          if (!empty($tplWrapper) || !empty($toPlaceholder)) {
            if (!empty($toPlaceholder)) {
              $output = $modx->setPlaceholder($toPlaceholder,$results); //Set '$toPlaceholder' placeholder
            }
            if (!empty($tplWrapper)) {
              $output = $modx->getChunk($tplWrapper, array('output' => $results)); //Convert to array and pass to the '$tplWrapper' chunk
            }
          }else{
            $output = $results;
          }
        }
      }else{
        $modx->log(modX::LOG_LEVEL_ERROR, 'getVimeo() - &id not found in Channel: ' . $channel);
      }
    }else{
      $modx->log(modX::LOG_LEVEL_ERROR, 'getVimeo() - &tpl is required');
    }
  }
}else{
  $modx->log(modX::LOG_LEVEL_ERROR, 'getVimeo() - &channel is required');
}
return $output;