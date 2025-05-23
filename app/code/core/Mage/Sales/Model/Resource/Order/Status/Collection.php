<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Sales
 */

/**
 * Flat sales order status history collection
 *
 * @package    Mage_Sales
 *
 * @method Mage_Sales_Model_Order_Status getItemById(int $value)
 * @method Mage_Sales_Model_Order_Status[] getItems()
 */
class Mage_Sales_Model_Resource_Order_Status_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Internal constructor
     */
    protected function _construct()
    {
        $this->_init('sales/order_status');
    }

    /**
     * Get collection data as options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('status', 'label');
    }

    /**
     * Get collection data as options hash
     *
     * @return array
     */
    public function toOptionHash()
    {
        return $this->_toOptionHash('status', 'label');
    }

    /**
     * Join order states table
     */
    public function joinStates()
    {
        if (!$this->getFlag('states_joined')) {
            $this->_idFieldName = 'status_state';
            $this->getSelect()->joinLeft(
                ['state_table' => $this->getTable('sales/order_status_state')],
                'main_table.status=state_table.status',
                ['state', 'is_default'],
            );
            $this->setFlag('states_joined', true);
        }
        return $this;
    }

    /**
     * add state code filter to collection
     *
     * @param string $state
     * @return $this
     */
    public function addStateFilter($state)
    {
        $this->joinStates();
        $this->getSelect()->where('state_table.state=?', $state);
        return $this;
    }

    /**
     * add status code filter to collection
     *
     * @param string $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        $this->joinStates();
        $this->getSelect()->where('state_table.status=?', $status);
        return $this;
    }

    /**
     * Define label order
     *
     * @param string $dir
     * @return $this
     */
    public function orderByLabel($dir = 'ASC')
    {
        $this->getSelect()->order('main_table.label ' . $dir);
        return $this;
    }
}
