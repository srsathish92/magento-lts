<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Centinel
 */

/**
 * Abstract Validation State Model for Mastercard
 *
 * @package    Mage_Centinel
 */
class Mage_Centinel_Model_State_Mastercard extends Mage_Centinel_Model_StateAbstract
{
    /**
     * Analyse lookup`s results. If it has require params for authenticate, return true
     *
     * @return bool
     */
    public function isAuthenticateAllowed()
    {
        return $this->_isLookupStrictSuccessful() && is_null($this->getAuthenticateEciFlag());
    }

    /**
     * Analyse authenticate`s results. If authenticate is successful return true and false if it failure
     * Result depends from flag self::getIsModeStrict()
     *
     * @return bool
     */
    public function isAuthenticateSuccessful()
    {
        $paResStatus = $this->getAuthenticatePaResStatus();
        $eciFlag = $this->getAuthenticateEciFlag();
        $xid = $this->getAuthenticateXid();
        $cavv = $this->getAuthenticateCavv();
        $errorNo = $this->getAuthenticateErrorNo();
        $signatureVerification = $this->getAuthenticateSignatureVerification();

        //Test cases 1-4, 10
        if ($this->_isLookupStrictSuccessful()) {
            if ($paResStatus == 'Y' && $eciFlag == '02' && $xid != '' && $cavv != '' && $errorNo == '0') {
                //Test case 1
                if ($signatureVerification == 'Y') {
                    return true;
                }
                //Test case 2
                if ($signatureVerification == 'N') {
                    return false;
                }
            }

            //Test case 3
            if ($paResStatus == 'N' && $signatureVerification == 'Y' &&  $eciFlag == '01' &&
                $xid != '' && $cavv == '' && $errorNo == '0'
            ) {
                return false;
            }

            //Test case 4
            if ($paResStatus == 'U' && $signatureVerification == 'Y' && $eciFlag == '01' &&
                $xid != '' && $cavv == '' && $errorNo == '0'
            ) {
                if ($this->getIsModeStrict()) {
                    return false;
                } else {
                    return true;
                }
            }

            //Test case 10
            if ($paResStatus == '' && $signatureVerification == '' && $eciFlag == '01' &&
                $xid == '' && $cavv == '' && $errorNo == '1050'
            ) {
                return false;
            }
        }

        //Test cases 5-9
        if (!$this->getIsModeStrict() && $this->_isLookupSoftSuccessful()) {
            if ($paResStatus == '' && $signatureVerification == '' && $eciFlag == '' &&
                $xid == '' && $cavv == '' && $errorNo == '0'
            ) {
                return true;
            } elseif ($paResStatus == false && $signatureVerification == false && $eciFlag == false &&
                $xid == false && $cavv == false && $errorNo == false
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Analyse lookup`s results. If lookup is strict successful return true
     *
     * @return bool
     */
    protected function _isLookupStrictSuccessful()
    {
        //Test cases 1-4, 10
        if ($this->getLookupEnrolled() == 'Y' &&
            $this->getLookupAcsUrl() != '' &&
            $this->getLookupPayload() != '' &&
            $this->getLookupErrorNo() == '0'
        ) {
            return true;
        }
        return false;
    }

    /**
     * Analyse lookup`s results. If lookup is soft successful return true
     *
     * @return bool
     */
    protected function _isLookupSoftSuccessful()
    {
        $acsUrl = $this->getLookupAcsUrl();
        $payload = $this->getLookupPayload();
        $errorNo = $this->getLookupErrorNo();
        $enrolled = $this->getLookupEnrolled();

        //Test cases 6,7
        if ($acsUrl == '' && $payload == '' && $errorNo == '0' && ($enrolled == 'N' || $enrolled == 'U')) {
            return true;
        }

        //Test case 5
        if ($enrolled == '' && $acsUrl == '' && $payload == '' && $errorNo == 'Timeout number') {
            return true;
        }

        //Test cases 8,9
        if ($enrolled == 'U' && $acsUrl == '' && $payload == '' && $errorNo == '1001') {
            return true;
        }

        return false;
    }
}
