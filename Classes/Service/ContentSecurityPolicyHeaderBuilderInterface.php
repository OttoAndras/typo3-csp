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
     * @param string $directive
     * @param string $expression
     */
    public function addNonce($directive, $expression);

    /**
     * Add a hash value to the script-src.
     *
     * @param string $type
     * @param string $hash
     */
    public function addHash($type, $hash);


    /**
     * Add a hash value to the script-src.
     *
     * @return void
     */
    public function useReportingMode();

    /**
     * Deletes the entries of the given directive
     *
     * @param string $directive
     */
    public function resetDirective($directive);

    /**
     * @return array
     */
    public function getHeader();


}