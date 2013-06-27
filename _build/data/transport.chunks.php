<?php
/*
 * @package getvimeo
 * @subpackage build
 */

$chunks = array();

$chunks[0]= $modx->newObject('modChunk');
$chunks[0]->fromArray(array(
  'id' => 0,
  'name' => 'vimeoTpl',
  'description' => 'Example Chunk serving as a Template.',
  'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/vimeotpl.chunk.tpl'),
  'properties' => '',
),'',true,true);

return $chunks;