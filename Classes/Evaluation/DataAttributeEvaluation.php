<?php
/**
 * Created by PhpStorm.
 * User: ottoa
 * Date: 07/10/2017
 * Time: 12:47
 */

namespace AndrasOtto\Csp\Evaluation;


use AndrasOtto\Csp\Domain\Model\DataAttribute;
use FluidTYPO3\Flux\Outlet\Pipe\Exception;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class DataAttributeEvaluation
{
    /**
     * JavaScript code for client side validation/evaluation
     *
     * @return string JavaScript code for client side validation/evaluation
     */
    public function returnFieldJS() {
        return "return value;";
    }

    /**
     * Server-side validation/evaluation on saving the record
     *
     * @param string $value The field value to be evaluated
     * @param string $is_in The "is_in" value of the field configuration from TCA
     * @param bool $set Boolean defining if the value is written to the database or not. Must be passed by reference and changed if needed.
     * @return string Evaluated field value
     */
    public function evaluateFieldValue($value, $is_in, &$set) {

        if(trim($value)) {
            try {
                $dataAttributes = DataAttribute::generateAttributesFromString($value);
                //If there is only some empty attributes, we are not allow to save the value
                if(count($dataAttributes) == 0) {
                    throw new Exception('No dataAttribute was generated.');
                }
            } catch (\Exception $e) {
                $set = false;
            }
        }

        return $value;
    }
}