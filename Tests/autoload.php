<?php
/**
 * Created by PhpStorm.
 * User: ottoa
 * Date: 04/10/2017
 * Time: 10:19
 */

$pathSite = getenv('TYPO3_PATH_WEB');

/** @var Composer\Autoload\ClassLoader $autoloader */
$autoloader = require $pathSite . '/typo3_src/vendor/autoload.php';
$autoloader->addPsr4('FluidTYPO3\\Vhs\\Tests\\Fixtures\\', __DIR__ . '/Fixtures/');
$autoloader->addPsr4('Phpcsp\\Security\\', __DIR__ . '../Resources/Private/php-csp/src/Phpcsp/Security');
$autoloader->addPsr4('\'AndrasOtto\\Csp\\', __DIR__ . '../Classes');