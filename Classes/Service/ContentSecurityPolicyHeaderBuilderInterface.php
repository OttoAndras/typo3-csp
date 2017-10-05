<?php
/**
 * Created by PhpStorm.
 * User: ottoa
 * Date: 04/10/2017
 * Time: 20:43
 */

namespace AndrasOtto\Csp\Service;


interface ContentSecurityPolicyHeaderBuilderInterface
{
    /**
     * @param string $directive
     * @param string $expression
     */
    public function addSourceExpression($directive, $expression);

    /**
     * @param string $includeLegacy
     * @return array
     */
    public function getHeaders($includeLegacy);

    /**
     * Add a hash value to the script-src.
     *
     * @param string $type
     * @param string $hash
     */
    public function addHash($type, $hash);
}