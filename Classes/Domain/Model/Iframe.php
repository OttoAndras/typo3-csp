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

namespace AndrasOtto\Csp\Domain\Model;


use AndrasOtto\Csp\Exceptions\InvalidValueException;
use AndrasOtto\Csp\Service\ContentSecurityPolicyHeaderBuilder;
use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Iframe extends AbstractEntity
{

    /**
     * @var string
     */
    protected $src = '';

    /**
     * @var string
     */
    protected $srcHost = '';

    /**
     * @var string
     */
    protected $class = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var int
     */
    protected $width = 0;

    /**
     * @var int
     */
    protected $height = 0;

    /**
     * @var array
     */
    protected $sandbox = [];

    /**
     * @var bool
     */
    protected $allowFullScreen = false;

    /**
     * @var bool
     */
    protected $allowPaymentRequest = false;

    /**
     * @var array
     */
    protected $dataAttributes = [];

    /**
     * Accepted values for sandbox.
     * Source: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/iframe
     *
     * @var array
     */
    protected $acceptedSandboxValues = [
        'allow-forms',
        'allow-modals',
        'allow-orientation-lock',
        'allow-pointer-lock',
        'allow-popups',
        'allow-popups-to-escape-sandbox',
        'allow-presentation',
        'allow-same-origin',
        'allow-scripts',
        'allow-top-navigation',
        'allow-top-navigation-by-user-activation'
    ];

    /**
     * Constructor
     *
     * @param string $src
     * @param string $name
     * @param string $class
     * @param int $width
     * @param int $height
     * @param string $sandbox
     * @param bool $allowPaymentRequest
     * @param bool $allowFullScreen
     * @param string $dataAttributes
     */
    public function __construct($src,
                                $class = '',
                                $name = '',
                                $width = 0,
                                $height = 0,
                                $sandbox = '',
                                $allowPaymentRequest = false,
                                $allowFullScreen = false,
                                $dataAttributes = '')
    {
        $this->ensureSrc($src);
        $this->class = $class;
        $this->name = $name;
        $this->ensureWidth($width);
        $this->ensureHeight($height);
        $this->ensureSandboxValues($sandbox);
        $this->ensureAllowFullScreen($allowFullScreen);
        $this->ensureAllowPaymentRequest($allowPaymentRequest);
        $this->ensureDataAttributes($dataAttributes);
    }

    /**
     * Should be a valid host
     *
     * @param string $src
     * @throws InvalidValueException
     */
    protected function ensureSrc($src) {
        if(!$src) {
            throw new InvalidValueException(
                'Src must be set',
                1505656675);
        }

        $host = parse_url($src, PHP_URL_HOST);

        if(!$host) {
            throw new InvalidValueException(
                sprintf('Host cannot be extracted from the src value "%s"', $src)
                , 1505632671);
        } else {
            $this->src = $src;
            $this->srcHost = $src;
        }
    }

    /**
     * Width should have a positive integer value or 0
     *
     * @param string|int $width
     * @throws InvalidValueException
     */
    protected function ensureWidth($width) {
        if(intval($width) === false  || $width < 0) {
            throw new InvalidValueException(
                sprintf('Width should be a positive integer or zero, "%s" given', $width)
                , 1505632672);
        } else {
            $this->width = (int)$width;
        }
    }

    /**
     * Height should have a positive integer value or 0
     *
     * @param string|int $height
     * @throws InvalidValueException
     */
    protected function ensureHeight($height) {
        if(intval($height) === false || $height < 0) {
            throw new InvalidValueException(
                sprintf('Height should be a positive integer or zero, "%s" given', $height)
                , 1505632672);
        } else {
            $this->height = (int)$height;
        }
    }

    /**
     * Check each sandbox values against the accepted sandbox values array
     *
     * @param string $sandbox
     * @throws InvalidValueException
     */
    protected function ensureSandboxValues($sandbox) {
        //Sandbox input value is a comma separated list
        $values = preg_split('/,/', $sandbox);

        foreach ($values as $value) {
            $value = trim($value);
            if($value) {
                if (!in_array($value, $this->acceptedSandboxValues)) {
                    throw new InvalidValueException(
                        sprintf('Not allowed value "%s" for the attribute sandbox.', $value),
                        1505656673);
                }
                $this->sandbox[] = $value;
            }
        }
    }

    /**
     * If the variable set, and it is not false or 0 it will be set to true.
     *
     * @param $allowFullScreen
     */
    protected function ensureAllowFullScreen($allowFullScreen) {
        if(boolval($allowFullScreen)) {
            $this->allowFullScreen = true;
        }
    }

    /**
     * If the variable set, and it is not false or 0 it will be set to true.
     *
     * @param $allowPaymentRequest
     */
    protected function ensureAllowPaymentRequest($allowPaymentRequest) {
        if(boolval($allowPaymentRequest)) {
            $this->allowPaymentRequest = true;
        }
    }

    /**
     * Converts DataAttributes from a definition.
     *
     * @see DataAttribute::generateAttributesFromString
     * @param $definition
     *
     * @return void
     */
    protected function ensureDataAttributes($definition) {
        if($definition) {
            // If the data attributes cannot be generated because the definition throws an exception
            // we will set dataAttributes to an empty array.
            try {
                $this->dataAttributes = DataAttribute::generateAttributesFromString($definition);
            } catch (InvalidValueException $e) {
                $this->dataAttributes = [];
            }
        }
    }

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param string $src
     * @return void
     */
    public function setSrc($src)
    {
        $this->ensureSrc($src);
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return void
     */
    public function setWidth($width)
    {
        $this->ensureWidth($width);
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->ensureHeight($height);
    }

    /**
     * @return array
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     * @param string $sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->ensureSandboxValues($sandbox);
    }

    /**
     * @return boolean
     */
    public function isAllowFullScreen()
    {
        return $this->allowFullScreen;
    }

    /**
     * @param boolean $allowFullScreen
     */
    public function setAllowFullScreen($allowFullScreen)
    {
        $this->ensureAllowFullScreen(boolval($allowFullScreen));
    }

    /**
     * @return boolean
     */
    public function isAllowPaymentRequest()
    {
        return $this->allowPaymentRequest;
    }

    /**
     * @param boolean $allowPaymentRequest
     */
    public function setAllowPaymentRequest($allowPaymentRequest)
    {
        $this->ensureAllowPaymentRequest(boolval($allowPaymentRequest));
    }

    /**
     * @return array
     */
    public function getDataAttributes()
    {
        return $this->dataAttributes;
    }

    /**
     * @param string $dataAttributes
     */
    public function setDataAttributes($dataAttributes)
    {
        $this->ensureDataAttributes($dataAttributes);
    }

    /**
     * Returns an iframe tag as as string
     *
     * @return string
     * @throws InvalidValueException
     */
    public function generateHtmlTag(){
        $attributes = [];
        if($this->getSrc()) {
            $attributes['src'] = $this->getSrc();

            //Need to add the src host to the content security policy header in the moment as the iframe generated.
            ContentSecurityPolicyManager::getBuilder()->addSourceExpression(
                ContentSecurityPolicyHeaderBuilder::DIRECTIVE_FRAME_SRC, $this->srcHost);
        }

        if($this->getName()) {
            $attributes['name'] = htmlspecialchars($this->getName());
        }

        if($this->getClass()) {
            $attributes['class'] = htmlspecialchars($this->getClass());
        }

        if($this->getWidth()) {
            $attributes['width'] = $this->getWidth();
        }

        if($this->getHeight()) {
            $attributes['height'] = $this->getHeight();
        }

        if(count($this->getSandbox()) > 0) {
            $attributes['sandbox'] = implode(" ", $this->getSandbox());
        }

        if($this->isAllowFullScreen()) {
            $attributes['allowfullscreen'] = 'allowfullscreen';
        }

        if($this->isAllowPaymentRequest()) {
            $attributes['allowpaymentrequest'] = 'allowpaymentrequest';
        }

        if(count($this->getDataAttributes()) > 0) {
            /** @var DataAttribute $dataAttribute */
            foreach ($this->getDataAttributes() as $dataAttribute) {
                $attributes[$dataAttribute->getName()] = $dataAttribute->getValue();
            }
        }

        $iframe = '<iframe ';

        foreach ($attributes as $attributeName => $value) {
            if($value) {
                $iframe .= sprintf('%s="%s" ',  $attributeName,  $value);
            } else {
                $iframe .= $attributeName;
            }

        }

        return rtrim($iframe) . '></iframe>';
    }
}