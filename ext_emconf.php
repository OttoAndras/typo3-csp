<?php

$EM_CONF[$_EXTKEY] = [
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
  [
    'depends' => 
    [
      'typo3' => '7.6.19 - 8.7.99',
      'php' => '7.0.0-7.1.99',
    ],
    'conflicts' => 
    [
    ],
    'suggests' => 
    [
    ],
  ],
'autoload' =>
    [
        'psr-4' => [
            'Phpcsp\\Security\\' => 'Resources/Private/php-csp/src/Phpcsp/Security',
            'AndrasOtto\\Csp\\' => 'Classes'
        ]
    ],
  '_md5_values_when_last_written' => '',
];

