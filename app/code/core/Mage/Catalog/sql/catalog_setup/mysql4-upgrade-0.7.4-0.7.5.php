<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Catalog
 */

/** @var Mage_Catalog_Model_Resource_Setup  $installer */
$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('catalog_product_website')};
CREATE TABLE {$this->getTable('catalog_product_website')} (
  `product_id` INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `website_id` SMALLINT(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`, `website_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_WEBSITE_WEBSITE` FOREIGN KEY `FK_CATALOG_PRODUCT_WEBSITE_WEBSITE` (`website_id`)
    REFERENCES `{$this->getTable('core_website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_WEBSITE_PRODUCT_PRODUCT` FOREIGN KEY `FK_CATALOG_WEBSITE_PRODUCT_PRODUCT` (`product_id`)
    REFERENCES `{$this->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE
);

DROP TABLE IF EXISTS {$this->getTable('catalog_product_status')};
DROP TABLE IF EXISTS {$this->getTable('catalog_product_visibility')};
DROP TABLE IF EXISTS {$this->getTable('catalog_product_type')};
");
$installer->endSetup();
$productTable = $this->getTable('catalog_product_entity');
$installer->getConnection()->dropColumn($productTable, 'parent_id');
$installer->getConnection()->dropColumn($productTable, 'store_id');
$installer->getConnection()->dropColumn($productTable, 'is_active');

try {
    $installer->run("
    INSERT INTO {$this->getTable('catalog_product_website')}
        SELECT DISTINCT ps.product_id, cs.website_id
        FROM {$this->getTable('catalog_product_store')} ps, {$this->getTable('core_store')} cs
        WHERE cs.store_id=ps.store_id AND ps.store_id>0;
    DROP TABLE IF EXISTS {$this->getTable('catalog_product_store')};
    ");
} catch (Exception $e) {
}

$categoryTable = $this->getTable('catalog/category');
$installer->getConnection()->dropForeignKey($categoryTable, 'FK_CATALOG_CATEGORY_ENTITY_TREE_NODE');

try {
    $this->run("ALTER TABLE `{$this->getTable('catalog/category')}` ADD `path` VARCHAR( 255 ) NOT NULL, ADD `position` INT NOT NULL;");
} catch (Exception $e) {
}
try {
    $this->run("DROP TABLE IF EXISTS `{$this->getTable('catalog/category_tree')}`;");
} catch (Exception $e) {
}

$installer->getConnection()->dropKey($categoryTable, 'FK_catalog_category_ENTITY_ENTITY_TYPE');
$installer->getConnection()->dropKey($categoryTable, 'FK_catalog_category_ENTITY_STORE');
$installer->getConnection()->dropColumn($categoryTable, 'store_id');

$tierPriceTable = $this->getTable('catalog_product_entity_tier_price');
$installer->getConnection()->dropColumn($tierPriceTable, 'entity_type_id');
$installer->getConnection()->dropColumn($tierPriceTable, 'attribute_id');
$installer->getConnection()->dropForeignKey($tierPriceTable, 'FK_CATALOG_PRODUCT_ENTITY_TIER_PRICE_ATTRIBUTE');
$installer->getConnection()->dropKey($tierPriceTable, 'FK_CATALOG_PRODUCT_ENTITY_TIER_PRICE_ATTRIBUTE');

$installer->startSetup();
$installer->installEntities();
$installer->endSetup();

$this->convertOldTreeToNew();
