<?php
/**
 * @package getvimeo
 *
 * Copyright (C) 2014 David Pede. All rights reserved. <dev@tasianmedia.com>
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
/**
 * @subpackage build
 */
 
$properties = array(
  array(
    'name' => 'channel',
    'desc' => 'The URL Name or Numeric ID of the target Vimeo Channel. [REQUIRED]',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'id',
    'desc' => 'A comma-separated list of Numeric Video IDs to output from target Channel. Use `all` to output all Videos. [REQUIRED]',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'tpl',
    'desc' => 'Name of a chunk serving as a template. [REQUIRED]',
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
    'desc' => 'A placeholder name to sort by. [NOTE: Please see placeholder docs for more details]',
    'type' => 'textfield',
    'options' => '',
    'value' => 'upload_date',
  ),
  array(
    'name' => 'sortdir',
    'desc' => 'Order which to sort by. [OPTIONS: DESC or ASC]',
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
  array(
    'name' => 'limit',
    'desc' => 'Limits the number of Videos returned. Use `0` for unlimited results.',
    'type' => 'textfield',
    'options' => '',
    'value' => '0',
  ),
  array(
    'name' => 'offset',
    'desc' => 'An offset of Videos to skip.',
    'type' => 'textfield',
    'options' => '',
    'value' => '0',
  ),
  array(
    'name' => 'totalVar',
    'desc' => 'Define the key of a placeholder set by getVimeo indicating the total number of Videos that would be returned, NOT considering the LIMIT value.',
    'type' => 'textfield',
    'options' => '',
    'value' => 'total',
  ),
);
return $properties;