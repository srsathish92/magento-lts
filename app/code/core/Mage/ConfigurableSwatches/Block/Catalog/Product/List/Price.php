<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_ConfigurableSwatches
 */

/**
 * Class Mage_ConfigurableSwatches_Block_Catalog_Product_List_Price
 *
 * @package    Mage_ConfigurableSwatches
 *
 * @method Mage_Eav_Model_Entity_Collection_Abstract getProductCollection()
 */
class Mage_ConfigurableSwatches_Block_Catalog_Product_List_Price extends Mage_Core_Block_Template
{
    /**
     * @var string
     */
    protected $_template = 'configurableswatches/catalog/product/list/price/js.phtml';

    /**
     * Get target product IDs from product collection
     * which was set on block
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function getProducts()
    {
        return $this->getProductCollection();
    }

    /**
     * Get configuration for configurable swatches price change
     *
     * @return string
     */
    public function getJsonConfig()
    {
        /** @var Mage_Catalog_Helper_Product_Type_Composite $compositeProductHelper */
        $compositeProductHelper = $this->helper('catalog/product_type_composite');

        $config = [
            'generalConfig' => $compositeProductHelper->prepareJsonGeneralConfig(),
        ];
        foreach ($this->getProducts() as $product) {
            /** @var Mage_Catalog_Model_Product $product */
            if (!$product->getSwatchPrices()) {
                continue;
            }

            $config['products'][$product->getId()] = $compositeProductHelper->prepareJsonProductConfig($product);
            $config['products'][$product->getId()]['swatchPrices'] = $product->getSwatchPrices();

            $responseObject = new Varien_Object();
            Mage::dispatchEvent('catalog_product_view_config', [
                'response_object' => $responseObject,
                'product' => $product,
            ]);
            if (is_array($responseObject->getAdditionalOptions())) {
                foreach ($responseObject->getAdditionalOptions() as $option => $value) {
                    $config['products'][$product->getId()][$option] = $value;
                }
            }
        }

        /** @var Mage_Core_Helper_Data $helper */
        $helper = $this->helper('core');
        return $helper->jsonEncode($config);
    }

    /**
     * Disable output if all preconditions doesn't meet
     *
     * @return string
     */
    protected function _toHtml()
    {
        /** @var Mage_ConfigurableSwatches_Helper_List_Price $helper */
        $helper = $this->helper('configurableswatches/list_price');
        if (!$helper->isEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }
}
