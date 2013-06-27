<?php
/*
 * @package getvimeo
 * @subpackage build
 */
 
$properties = array(
  array(
    'name' => 'channel',
    'desc' => 'Name or ID of the target Vimeo Channel. [REQUIRED PARAMETER]',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'id',
    'desc' => 'A comma-separated list of Video IDs to output from target Channel. Use `all` to output all Videos. [REQUIRED PARAMETER]',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'tpl',
    'desc' => 'Name of a chunk serving as a template. [REQUIRED PARAMETER]',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'tplAlt',
    'desc' => 'Name of a chunk serving as a template for every other Video.',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'tplWrapper',
    'desc' => 'Name of a chunk serving as a wrapper template for the output. [NOTE: Only works when placeholder is set with &toPlaceholder]',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'sortby',
    'desc' => 'A placeholder name to sort by. Defaults to `upload_date`. [NOTE: Please see placeholder docs for more details]',
    'type' => 'textfield',
    'options' => '',
    'value' => 'upload_date',
  ),
  array(
    'name' => 'sortdir',
    'desc' => 'Order which to sort by. Defaults to `DESC`.',
    'type' => 'list',
    'options' => array(
      array('text' => 'ASC','value' => 'ASC'),
      array('text' => 'DESC','value' => 'DESC'),
      ),
    'value' => 'DESC',
  ),
  array(
    'name' => 'toPlaceholder',
    'desc' => 'If set, will assign the output to this placeholder instead of outputting it directly.',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
);
return $properties;