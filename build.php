<?php
chdir(__DIR__);

if (!file_exists('vendor/autoload.php')) {
  echo '[ERROR] It\'s required to run "composer install" before building BddWizard!' . PHP_EOL;
  exit(1);
}

$filename = 'build/bdd-wizard.phar';
if (file_exists($filename)) {
    unlink($filename);
}

$phar = new \Phar($filename, 0, 'bdd-wizard.phar');
$phar->setSignatureAlgorithm(\Phar::SHA1);
$phar->startBuffering();


$files = array_merge(
    rglob('*.php', 0, 'src')
    , rglob('*.php', 0, 'bin')
    , rglob('*.twig', 0, 'app')
    , rglob('*.yml', 0, 'app')
    , rglob('*.php', 0, 'web')
    , rglob('*.js', 0, 'web')
    , rglob('*.css', 0, 'web')
    , rglob('*.png', 0, 'web')
    , rglob('*.php', 0, 'vendor/composer')
    , require 'vendor/composer/autoload_classmap.php'
    , require 'vendor/composer/autoload_files.php'
    , array(
        'vendor/behat/gherkin/i18n.php'
        , 'vendor/twig/twig/lib/Twig/SimpleTest.php'
        , 'vendor/autoload.php'
    )
);
$exclude = '!(.git)|(.svn)!';
foreach($files as $file) {
    $file = str_replace($vendorDir, 'vendor', $file);
    if(preg_match($exclude, $file)) continue;
    $path = str_replace(__DIR__.'/', '', $file);
    $phar->addFromString($path, file_get_contents($file));
}

$phar->setStub(<<<STUB
<?php

/*
* This file is part of the BddWizard
*
* (c) Jean-François Lépine
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

Phar::mapPhar('bdd-wizard.phar');
Phar::mungServer(array('REQUEST_URI'));
Phar::mapPhar('bdd-wizard.phar');
Phar::webPhar();



// CLI
// --------------------------
if('cli' === php_sapi_name()) {
    require_once 'phar://bdd-wizard.phar/bin/bdd-wizard.php';
    exit;
}


// WEB
// --------------------------
\$filename = \$_SERVER['SCRIPT_NAME'];
\$info = new SplFileInfo(\$filename);
\$extension = \$info->getExtension();
switch(\$extension) {
    case 'css':
        header("Content-type: text/css");
        echo file_get_contents('phar://bdd-wizard.phar/web'.\$filename);
        break;
    case 'js':
        header("Content-type: application/javascript");
        echo file_get_contents('phar://bdd-wizard.phar/web'.\$filename);
        break;
    case 'png':
        header('Content-Type: image/png');
        echo file_get_contents('phar://bdd-wizard.phar/web'.\$filename);
        break;
    default:
        require_once 'phar://bdd-wizard.phar/web/index.php';
        break;
}

__HALT_COMPILER();
STUB
);
$phar->stopBuffering();

chmod($filename, 0755);

function rglob($pattern='*', $flags = 0, $path='')
{
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
    return $files;
}