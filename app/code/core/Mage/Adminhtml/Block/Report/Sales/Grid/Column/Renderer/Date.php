<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Adminhtml
 */

/**
 * Adminhtml grid item renderer date
 *
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Report_Sales_Grid_Column_Renderer_Date extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Date
{
    /**
     * Retrieve date format
     *
     * @return string
     */
    protected function _getFormat()
    {
        $format = $this->getColumn()->getFormat();
        if (!$format) {
            if (is_null(self::$_format)) {
                try {
                    $localeCode = Mage::app()->getLocale()->getLocaleCode();
                    $localeData = new Zend_Locale_Data();
                    switch ($this->getColumn()->getPeriodType()) {
                        case 'month':
                            self::$_format = $localeData::getContent($localeCode, 'dateitem', 'yM');
                            break;

                        case 'year':
                            self::$_format = $localeData::getContent($localeCode, 'dateitem', 'y');
                            break;

                        default:
                            self::$_format = Mage::app()->getLocale()->getDateFormat(
                                Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM,
                            );
                            break;
                    }
                } catch (Exception $e) {
                }
            }
            $format = self::$_format;
        }
        return $format;
    }

    /**
     * Renders grid column
     *
     * @return string
     */
    public function render(Varien_Object $row)
    {
        if ($data = $row->getData($this->getColumn()->getIndex())) {
            switch ($this->getColumn()->getPeriodType()) {
                case 'month':
                    $dateFormat = 'yyyy-MM';
                    break;
                case 'year':
                    $dateFormat = 'yyyy';
                    break;
                default:
                    $dateFormat = Varien_Date::DATE_INTERNAL_FORMAT;
                    break;
            }

            $format = $this->_getFormat();
            try {
                $data = ($this->getColumn()->getGmtoffset())
                    ? Mage::app()->getLocale()->date($data, $dateFormat)->toString($format)
                    : Mage::getSingleton('core/locale')->date($data, $dateFormat, null, false)->toString($format);
            } catch (Exception $e) {
                $data = ($this->getColumn()->getTimezone())
                    ? Mage::app()->getLocale()->date($data, $dateFormat)->toString($format)
                    : Mage::getSingleton('core/locale')->date($data, $dateFormat, null, false)->toString($format);
            }
            return $data;
        }
        return $this->getColumn()->getDefault();
    }
}
