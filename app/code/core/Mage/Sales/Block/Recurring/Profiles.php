<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Sales
 */

/**
 * Recurring profiles listing
 *
 * @package    Mage_Sales
 *
 * @method $this setBackUrl(string $value)
 * @method $this setGridColumns(Varien_Object[] $profiles)
 * @method $this setGridElements(Varien_Object[] $profiles)
 */
class Mage_Sales_Block_Recurring_Profiles extends Mage_Core_Block_Template
{
    /**
     * Profiles collection
     *
     * @var Mage_Sales_Model_Resource_Recurring_Profile_Collection
     */
    protected $_profiles = null;

    /**
     * Prepare profiles collection and render it as grid information
     */
    public function prepareProfilesGrid()
    {
        $this->_prepareProfiles(['reference_id', 'state', 'created_at', 'updated_at', 'method_code']);

        $pager = $this->getLayout()->createBlock('page/html_pager')
            ->setCollection($this->_profiles)->setIsOutputRequired(false);
        $this->setChild('pager', $pager);

        /** @var Mage_Sales_Model_Recurring_Profile $profile */
        $profile = Mage::getModel('sales/recurring_profile');

        $this->setGridColumns([
            new Varien_Object([
                'index' => 'reference_id',
                'title' => $profile->getFieldLabel('reference_id'),
                'is_nobr' => true,
                'width' => 1,
            ]),
            new Varien_Object([
                'index' => 'state',
                'title' => $profile->getFieldLabel('state'),
            ]),
            new Varien_Object([
                'index' => 'created_at',
                'title' => $profile->getFieldLabel('created_at'),
                'is_nobr' => true,
                'width' => 1,
                'is_amount' => true,
            ]),
            new Varien_Object([
                'index' => 'updated_at',
                'title' => $profile->getFieldLabel('updated_at'),
                'is_nobr' => true,
                'width' => 1,
            ]),
            new Varien_Object([
                'index' => 'method_code',
                'title' => $profile->getFieldLabel('method_code'),
                'is_nobr' => true,
                'width' => 1,
            ]),
        ]);

        $profiles = [];
        $store = Mage::app()->getStore();
        $locale = Mage::app()->getLocale();
        foreach ($this->_profiles as $profile) {
            $profile->setStore($store)->setLocale($locale);
            $profiles[] = new Varien_Object([
                'reference_id' => $profile->getReferenceId(),
                'reference_id_link_url' => $this->getUrl('sales/recurring_profile/view/', ['profile' => $profile->getId()]),
                'state'       => $profile->renderData('state'),
                'created_at'  => $this->formatDate($profile->getData('created_at'), 'medium', true),
                'updated_at'  => $profile->getData('updated_at') ? $this->formatDate($profile->getData('updated_at'), 'short', true) : '',
                'method_code' => $profile->renderData('method_code'),
            ]);
        }
        if ($profiles) {
            $this->setGridElements($profiles);
        }
        $orders = [];
    }

    /**
     * Instantiate profiles collection
     *
     * @param string|array $fields
     */
    protected function _prepareProfiles($fields = '*')
    {
        $this->_profiles = Mage::getModel('sales/recurring_profile')->getCollection()
            ->addFieldToFilter('customer_id', Mage::registry('current_customer')->getId())
            ->addFieldToSelect($fields)
            ->setOrder('profile_id', 'desc')
        ;
    }

    /**
     * Set back Url
     *
     * @inheritDoc
     */
    protected function _beforeToHtml()
    {
        $this->setBackUrl($this->getUrl('customer/account/'));
        return parent::_beforeToHtml();
    }
}
