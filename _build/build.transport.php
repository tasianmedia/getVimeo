<?php
/*
 * @package getvimeo
 * @subpackage build
 */

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define package names */
define('PKG_NAME','getVimeo');
define('PKG_NAME_LOWER','getvimeo');
define('PKG_VERSION','1.1.0');
define('PKG_RELEASE','pl');

/* define sources */
$root = dirname(dirname(__FILE__)).'/';
$sources = array(
  'root' => $root,
  'build' => $root . '_build/',
  'data' => $root . '_build/data/', //Files used to fetch Snippets, Chunks etc from elements folder 
  'resolvers' => $root . '_build/resolvers/',
  'chunks' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/chunks/',
  'snippets' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/snippets/',
  'lexicon' => $root . 'core/components/'.PKG_NAME_LOWER.'/lexicon/',
  'docs' => $root.'core/components/'.PKG_NAME_LOWER.'/docs/',
  'elements' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/',
  'source_assets' => $root.'assets/components/'.PKG_NAME_LOWER,
  'source_core' => $root.'core/components/'.PKG_NAME_LOWER,
);

unset($root);

/* instantiate MODx */
require_once $sources['build'].'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; //used for nice formatting of log messages
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

/* load builder */
$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER,false,true,'{core_path}components/'.PKG_NAME_LOWER.'/');

/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);

/* add snippets */
$snippets = include $sources['data'].'transport.snippets.php';
if (!is_array($snippets)) {
  $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in snippets.');
} else {
  $category->addMany($snippets);
  $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($snippets).' snippets.');
}

/* add chunks */
$chunks = include $sources['data'].'transport.chunks.php';
if (!is_array($chunks)) {
  $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in chunks.');
} else {
  $category->addMany($chunks);
  $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($chunks).' chunks.');
}

/* create category vehicle */
$attr = array(
  xPDOTransport::UNIQUE_KEY => 'category',
  xPDOTransport::PRESERVE_KEYS => false,
  xPDOTransport::UPDATE_OBJECT => true,
  xPDOTransport::RELATED_OBJECTS => true,
  xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
    'Snippets' => array(
      xPDOTransport::PRESERVE_KEYS => false,
      xPDOTransport::UPDATE_OBJECT => true,
      xPDOTransport::UNIQUE_KEY => 'name',
    ),
    'Chunks' => array(
      xPDOTransport::PRESERVE_KEYS => false,
      xPDOTransport::UPDATE_OBJECT => true,
      xPDOTransport::UNIQUE_KEY => 'name',
    ),
  ),
);
$vehicle = $builder->createVehicle($category,$attr);

/* add the file resolvers */
$modx->log(modX::LOG_LEVEL_INFO,'Adding file resolvers to category...');
$vehicle->resolve('file',array(
  'source' => $sources['source_core'],
  'target' => "return MODX_CORE_PATH . 'components/';",
));
/*$vehicle->resolve('file',array(
  'source' => $sources['source_assets'],
  'target' => "return MODX_ASSETS_PATH . 'components/';",
));*/

$builder->putVehicle($vehicle);

/* pack in the license file, readme and changelog */
$builder->setPackageAttributes(array(
  'license' => file_get_contents($sources['docs'] . 'license.txt'),
  'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
  'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
));

/* zip up package */
$modx->log(modX::LOG_LEVEL_INFO,'Packing up transport package zip...');
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

session_write_close();
exit();