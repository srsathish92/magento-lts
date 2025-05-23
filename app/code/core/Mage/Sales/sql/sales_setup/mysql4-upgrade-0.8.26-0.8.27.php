<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Sales
 */

/** @var Mage_Sales_Model_Entity_Setup $installer */
$installer = $this;

/** @var Varien_Db_Adapter_Pdo_Mysql $conn */
$conn = $installer->getConnection();

$conn->addColumn($installer->getTable('sales_quote'), 'customer_prefix', 'varchar(40) after customer_email');
$conn->addColumn($installer->getTable('sales_quote'), 'customer_middlename', 'varchar(40) after customer_firstname');
$conn->addColumn($installer->getTable('sales_quote'), 'customer_suffix', 'varchar(40) after customer_lastname');

$conn->addColumn($installer->getTable('sales_quote_address'), 'prefix', 'varchar(40) after email');
$conn->addColumn($installer->getTable('sales_quote_address'), 'middlename', 'varchar(40) after firstname');
$conn->addColumn($installer->getTable('sales_quote_address'), 'suffix', 'varchar(40) after lastname');

$installer->addAttribute('order', 'customer_prefix', ['type' => 'varchar', 'visible' => false]);
$installer->addAttribute('order', 'customer_middlename', ['type' => 'varchar', 'visible' => false]);
$installer->addAttribute('order', 'customer_suffix', ['type' => 'varchar', 'visible' => false]);

$installer->addAttribute('order_address', 'prefix', []);
$installer->addAttribute('order_address', 'middlename', []);
$installer->addAttribute('order_address', 'suffix', []);
