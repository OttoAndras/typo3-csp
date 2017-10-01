<?php

$EM_CONF[$_EXTKEY] = array (
  'title' => 'CSP: Content Security Policy',
  'description' => 'Generates the Content-Security-Policy Response Header based on the content of the current page',
  'category' => 'frontend, security',
  'version' => '0.0.1',
  'state' => 'alpha',
  'createDirs' => '',
  'clearcacheonload' => true,
  'author' => 'András Ottó',
  'author_email' => 'ottoandras@gmail.com',
  'author_company' => '',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '7.6.19 - 8.7.99',
      'php' => '7.0.0-7.1.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
'autoload' =>
    array(
        'psr-4' => array(
            'Phpcsp\\Security\\' => 'Resources/Private/php-csp/src/Phpcsp/Security',
            'AndrasOtto\\Csp\\' => 'Classes'
        )
    ),
  '_md5_values_when_last_written' => '',
);

