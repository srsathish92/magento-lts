<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Tag
 */

/**
 * Product Tag API
 *
 * @package    Mage_Tag
 */
class Mage_Tag_Model_Api extends Mage_Catalog_Model_Api_Resource
{
    /**
     * Retrieve list of tags for specified product
     *
     * @param int $productId
     * @param string|int $store
     * @return array
     */
    public function items($productId, $store = null)
    {
        $result = [];
        // fields list to return
        $fieldsForResult = ['tag_id', 'name'];

        /** @var Mage_Catalog_Model_Product $product */
        $product = Mage::getModel('catalog/product')->load($productId);
        if (!$product->getId()) {
            $this->_fault('product_not_exists');
        }

        /** @var Mage_Tag_Model_Resource_Tag_Collection $tags */
        $tags = Mage::getModel('tag/tag')->getCollection()->joinRel()->addProductFilter($productId);
        if ($store) {
            $tags->addStoreFilter($this->_getStoreId($store));
        }

        /** @var Mage_Tag_Model_Tag $tag */
        foreach ($tags as $tag) {
            $result[$tag->getId()] = $tag->toArray($fieldsForResult);
        }

        return $result;
    }

    /**
     * Retrieve tag info as array('name'-> .., 'status' => ..,
     * 'base_popularity' => .., 'products' => array($productId => $popularity, ...))
     *
     * @param int $tagId
     * @param string|int $store
     * @return array
     */
    public function info($tagId, $store)
    {
        $result = [];
        $storeId = $this->_getStoreId($store);
        /** @var Mage_Tag_Model_Tag $tag */
        $tag = Mage::getModel('tag/tag')->setStoreId($storeId)->setAddBasePopularity()->load($tagId);
        if (!$tag->getId()) {
            $this->_fault('tag_not_exists');
        }
        $result['status'] = $tag->getStatus();
        $result['name'] = $tag->getName();
        $result['base_popularity'] = (is_numeric($tag->getBasePopularity())) ? $tag->getBasePopularity() : 0;
        // retrieve array($productId => $popularity, ...)
        $result['products'] = [];
        $relatedProductsCollection = $tag->getEntityCollection()->addTagFilter($tagId)
            ->addStoreFilter($storeId)->addPopularity($tagId);
        /** @var Mage_Catalog_Model_Product $product */
        foreach ($relatedProductsCollection as $product) {
            $result['products'][$product->getId()] = $product->getPopularity();
        }

        return $result;
    }

    /**
     * Add tag(s) to product.
     * Return array of added/updated tags as array($tagName => $tagId, ...)
     *
     * @param array $data
     * @return array
     */
    public function add($data)
    {
        $result = [];
        $data = $this->_prepareDataForAdd($data);
        /** @var Mage_Catalog_Model_Product $product */
        $product = Mage::getModel('catalog/product')->load($data['product_id']);
        if (!$product->getId()) {
            $this->_fault('product_not_exists');
        }
        /** @var Mage_Customer_Model_Customer $customer */
        $customer = Mage::getModel('customer/customer')->load($data['customer_id']);
        if (!$customer->getId()) {
            $this->_fault('customer_not_exists');
        }
        $storeId = $this->_getStoreId($data['store']);

        try {
            /** @var Mage_Tag_Model_Tag $tag */
            $tag = Mage::getModel('tag/tag');
            $tagNamesArr = Mage::helper('tag')->cleanTags(Mage::helper('tag')->extractTags($data['tag']));
            foreach ($tagNamesArr as $tagName) {
                // unset previously added tag data
                $tag->unsetData();
                $tag->loadByName($tagName);
                if (!$tag->getId()) {
                    $tag->setName($tagName)
                        ->setFirstCustomerId($customer->getId())
                        ->setFirstStoreId($storeId)
                        ->setStatus($tag->getPendingStatus())
                        ->save();
                }
                $tag->saveRelation($product->getId(), $customer->getId(), $storeId);
                $result[$tagName] = $tag->getId();
            }
        } catch (Mage_Core_Exception $e) {
            $this->_fault('save_error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Change existing tag information
     *
     * @param int $tagId
     * @param array $data
     * @param string|int $store
     * @return bool
     */
    public function update($tagId, $data, $store)
    {
        $data = $this->_prepareDataForUpdate($data);
        $storeId = $this->_getStoreId($store);
        /** @var Mage_Tag_Model_Tag $tag */
        $tag = Mage::getModel('tag/tag')->setStoreId($storeId)->setAddBasePopularity()->load($tagId);
        if (!$tag->getId()) {
            $this->_fault('tag_not_exists');
        }

        // store should be set for 'base_popularity' to be saved in Mage_Tag_Model_Resource_Tag::_afterSave()
        $tag->setStore($storeId);
        if (isset($data['base_popularity'])) {
            $tag->setBasePopularity($data['base_popularity']);
        }
        if (isset($data['name'])) {
            $tag->setName(trim($data['name']));
        }
        if (isset($data['status'])) {
            // validate tag status
            if (!in_array($data['status'], [
                $tag->getApprovedStatus(), $tag->getPendingStatus(), $tag->getDisabledStatus()])
            ) {
                $this->_fault('invalid_data');
            }
            $tag->setStatus($data['status']);
        }

        try {
            $tag->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('save_error', $e->getMessage());
        }

        return true;
    }

    /**
     * Remove existing tag
     *
     * @param int $tagId
     * @return bool
     */
    public function remove($tagId)
    {
        /** @var Mage_Tag_Model_Tag $tag */
        $tag = Mage::getModel('tag/tag')->load($tagId);
        if (!$tag->getId()) {
            $this->_fault('tag_not_exists');
        }
        try {
            $tag->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('remove_error', $e->getMessage());
        }

        return true;
    }

    /**
     * Check data before add
     *
     * @param array $data
     * @return array
     */
    protected function _prepareDataForAdd($data)
    {
        if (!isset($data['product_id']) || !isset($data['tag'])
            || !isset($data['customer_id']) || !isset($data['store'])
        ) {
            $this->_fault('invalid_data');
        }

        return $data;
    }

    /**
     * Check data before update
     *
     * @param array $data
     * @return array
     */
    protected function _prepareDataForUpdate($data)
    {
        // $data should contain at least one field to change
        if (!(isset($data['name']) || isset($data['status']) || isset($data['base_popularity']))) {
            $this->_fault('invalid_data');
        }

        return $data;
    }
}
