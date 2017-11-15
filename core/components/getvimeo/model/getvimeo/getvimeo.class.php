<?php

/**
 * @package getvimeo
 *
 * Copyright (C) 2017 David Pede. All rights reserved. <dev@tasianmedia.com>
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
**/

class getVimeo {
  public $modx;
  public $config = array();
  function __construct(modX &$modx,array $config = array()) {
    $this->modx =& $modx;
    $basePath = $this->modx->getOption('getvimeo.core_path',$config,$this->modx->getOption('core_path').'components/getvimeo/');
    $assetsUrl = $this->modx->getOption('getvimeo.assets_url',$config,$this->modx->getOption('assets_url').'components/getvimeo/');
    $this->config = array_merge(array(
      'basePath' => $basePath,
      'corePath' => $basePath,
      'modelPath' => $basePath.'model/',
      'processorsPath' => $basePath.'processors/',
      'templatesPath' => $basePath.'templates/',
      'chunksPath' => $basePath.'elements/chunks/',
      'jsUrl' => $assetsUrl.'mgr/js/',
      'cssUrl' => $assetsUrl.'mgr/css/',
      'assetsUrl' => $assetsUrl,
      'connectorUrl' => $assetsUrl.'connector.php',
    ),$config);
    $this->modx->addPackage('getvimeo', $this->config['modelPath']);
  }

  /**
   * CURL request and return data.
   *
   * @param string $url The url to fetch.
   * @return array $data The data returned.
   */
  public function file_get_contents_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }
}
?>