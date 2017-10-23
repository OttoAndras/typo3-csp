<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

$EM_CONF[$_EXTKEY] = [
  'title' => 'CSP: Content Security Policy',
  'description' => 'Generates the Content-Security-Policy response header based on the content of the page',
  'category' => 'misc',
  'version' => '0.9.0',
  'state' => 'beta',
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
      'php' => '5.5.0-7.1.99',
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

