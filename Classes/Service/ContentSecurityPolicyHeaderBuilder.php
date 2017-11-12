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

namespace AndrasOtto\Csp\Service;


use AndrasOtto\Csp\Constants\Directives;
use AndrasOtto\Csp\Constants\HashTypes;
use AndrasOtto\Csp\Constants\SourceKeywords;
use AndrasOtto\Csp\Exceptions\InvalidDirectiveException;

class ContentSecurityPolicyHeaderBuilder implements ContentSecurityPolicyHeaderBuilderInterface
{

    /**
     * @var string
     */
    protected $headerName = 'Content-Security-Policy';

    /**
     * A list of allowed CSP directives.
     *
     * @var array
     */
    protected $allowedDirectives = [
        Directives::BASE_URI,
        Directives::CHILD_SRC,
        Directives::CONNECT_SRC,
        Directives::DEFAULT_SRC,
        Directives::FONT_SRC,
        Directives::FORM_ACTION,
        Directives::FRAME_ANCESTORS,
        Directives::FRAME_SRC,
        Directives::IMG_SRC,
        Directives::MEDIA_SRC,
        Directives::OBJECT_SRC,
        Directives::SCRIPT_SRC,
        Directives::STYLE_SRC
    ];

    /**
     * Supported hash algorithms.
     *
     * @var array
     */
    protected $allowedHashAlgorithmValues = [
        HashTypes::SHA_256,
        HashTypes::SHA_384,
        HashTypes::SHA_512
    ];

    /**
     * Supported hash algorithms.
     *
     * @var array
     */
    protected $sourceKeywords = [
        SourceKeywords::NONE,
        SourceKeywords::SELF,
        SourceKeywords::UNSAFE_EVAL,
        SourceKeywords::UNSAFE_INLINE,
    ];

    /**
     * @var string
     */
    protected $directiveSeparator = '; ';

    /**
     * Contains all defined directives.
     *
     * @var array
     */
    protected $directives = [];

    /**
     * Adds a new block to the given directive.
     * Possible values: expressions, hashes, nonces
     *
     * @param $blockName
     * @param $directive
     * @param $value
     */
    private function addDirectiveBlock($blockName, $directive, $value) {

        if (!(isset($this->directives[$directive]) && is_array($this->directives[$directive]))) {
            $this->directives[$directive] = [];
        }

        if (!(isset($this->directives[$directive][$blockName]) && is_array($this->directives[$directive][$blockName]))) {
            $this->directives[$directive][$blockName] = [];
        }

        $this->directives[$directive][$blockName][] = $value;

    }

    /**
     * @param string $directive
     * @param string $nonce
     * @throws InvalidDirectiveException
     */
    public function addNonce($directive, $nonce)
    {
        $this->checkDirective($directive);
        $this->addDirectiveBlock('nonces', $directive, $nonce);
    }

    /**
     * @param string $directive
     * @param string $expression
     * @throws InvalidDirectiveException
     */
    public function addSourceExpression($directive, $expression)
    {
        $this->checkDirective($directive);
        $this->addDirectiveBlock('expressions', $directive, $expression);

    }

    /**
     * Add a hash value to the script-src.
     *
     * @param string $type
     * @param string $hash
     * @throws InvalidDirectiveException
     */
    public function addHash($type, $hash)
    {
        $directive = Directives::SCRIPT_SRC;
        if (!(isset($this->directives[$directive]) && is_array($this->directives[$directive]))) {
            $this->directives[$directive] = [];
        }

        if (!(isset($this->directives[$directive]['hashes']) && is_array($this->directives[$directive]['hashes']))) {
            $this->directives[$directive]['hashes'] = [];
        }

        $this->directives[$directive]['hashes'][$type][] = $hash;
    }

    /**
     * Returns the CSP header
     *
     * @return array
     */
    public function getHeader()
    {
        $value = $this->getValue();

        if (!is_null($value)) {
            return [
                'name' => $this->headerName,
                'value' => $value
            ];
        }

        return [];
    }

    /**
     * Returns the value for the CSP header based on the loaded configuration.
     *
     * @return string|null
     */
    protected function getValue()
    {
        $directives = [];
        foreach ($this->directives as $name => $value) {
            $directives[] = sprintf('%s %s', $name, $this->parseDirectiveValue($value));
        }

        // If there is nothing registered yet
        if (count($directives) < 1) {
            return null;
        }

        return trim(sprintf('%s%s', implode($this->directiveSeparator, $directives), $this->directiveSeparator));
    }


    /**
     * @param array $directive
     * @return null|string
     */
    protected function parseDirectiveValue($directive)
    {
        $expressions = [];

        if (!(isset($directive) && is_array($directive))) {
            return null;
        }

        // Parse the source expressions
        if (isset($directive['expressions']) && is_array($directive['expressions'])) {
            $expressions = $directive['expressions'];
        }
        
        // Parse the nonces
        if (isset($directive['nonces']) && is_array($directive['nonces'])) {
            foreach ($directive['nonces'] as $nonce) {
                $expressions[] = sprintf("'nonce-%s'", $nonce);
            }
        }

        // Parse the hashes
        if (isset($directive['hashes']) && is_array($directive['hashes'])) {
            foreach ($directive['hashes'] as $type => $hashes) {
                foreach ($hashes as $hash) {
                    $expressions[] = sprintf("'%s-%s'", $type, base64_encode($hash));
                }
            }
        }

        return trim(implode(' ', array_map(function ($value) {
            return $this->encodeDirectiveValue($value);
        }, $expressions)));
    }

    /**
     * Checks if a directive is in the allowed directives array
     *
     * @param $directive
     * @throws InvalidDirectiveException
     */
    private function checkDirective($directive) {
        if (!in_array($directive, $this->allowedDirectives)) {
            throw new InvalidDirectiveException(
                'Tried to add a source set for an CSP invalid directive.'
            );
        }
    }

    /**
     * @param string $value
     * @return string
     */
    public function encodeDirectiveValue($value)
    {
        $value = str_replace([';', ','], ['%3B', '%2C'], $value);

        if (in_array($value, $this->sourceKeywords)) {
            $value = sprintf("'%s'", $value);
        }

        return trim($value);
    }

    /**
     * Deletes the entries of the given directive
     *
     * @param string $directive
     */
    public function resetDirective($directive){
        $this->checkDirective($directive);

        unset($this->directives[$directive]);
    }
}