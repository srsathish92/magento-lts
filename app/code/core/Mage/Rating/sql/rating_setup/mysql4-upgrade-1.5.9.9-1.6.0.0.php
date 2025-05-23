<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Rating
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

/**
 * Drop foreign keys
 */
$installer->getConnection()->dropForeignKey(
    $installer->getTable('rating/rating'),
    'FK_RATING_ENTITY_KEY',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('rating/rating_option'),
    'FK_RATING_OPTION_RATING',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('rating/rating_option_vote'),
    'FK_RATING_OPTION_REVIEW_ID',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('rating/rating_option_vote'),
    'FK_RATING_OPTION_VALUE_OPTION',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('rating/rating_vote_aggregated'),
    'FK_RATING_OPTION_VALUE_AGGREGATE',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('rating/rating_vote_aggregated'),
    'FK_RATING_OPTION_VOTE_AGGREGATED_STORE',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('rating/rating_store'),
    'FK_RATING_STORE_RATING',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('rating/rating_store'),
    'FK_RATING_STORE_STORE',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('rating/rating_title'),
    'FK_RATING_TITLE',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('rating/rating_title'),
    'FK_RATING_TITLE_STORE',
);

/**
 * Drop indexes
 */
$installer->getConnection()->dropIndex(
    $installer->getTable('rating/rating'),
    'IDX_CODE',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('rating/rating'),
    'FK_RATING_ENTITY',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('rating/rating_entity'),
    'IDX_CODE',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('rating/rating_option'),
    'FK_RATING_OPTION_RATING',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('rating/rating_option_vote'),
    'FK_RATING_OPTION_VALUE_OPTION',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('rating/rating_option_vote'),
    'FK_RATING_OPTION_REVIEW_ID',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('rating/rating_vote_aggregated'),
    'FK_RATING_OPTION_VALUE_AGGREGATE',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('rating/rating_vote_aggregated'),
    'FK_RATING_OPTION_VOTE_AGGREGATED_STORE',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('rating/rating_store'),
    'FK_RATING_STORE_STORE',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('rating/rating_title'),
    'FK_RATING_TITLE_STORE',
);

/**
 * Change columns
 */
$tables = [
    $installer->getTable('rating/rating') => [
        'columns' => [
            'rating_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Rating Id',
            ],
            'entity_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id',
            ],
            'rating_code' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 64,
                'nullable'  => false,
                'comment'   => 'Rating Code',
            ],
            'position' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Rating Position On Frontend',
            ],
        ],
        'comment' => 'Ratings',
    ],
    $installer->getTable('rating/rating_store') => [
        'columns' => [
            'rating_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'default'   => '0',
                'comment'   => 'Rating id',
            ],
            'store_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'default'   => '0',
                'comment'   => 'Store id',
            ],
        ],
        'comment' => 'Rating Store',
    ],
    $installer->getTable('rating/rating_title') => [
        'columns' => [
            'rating_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'default'   => '0',
                'comment'   => 'Rating Id',
            ],
            'store_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'default'   => '0',
                'comment'   => 'Store Id',
            ],
            'value' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 255,
                'nullable'  => false,
                'comment'   => 'Rating Label',
            ],
        ],
        'comment' => 'Rating Title',
    ],
    $installer->getTable('rating/rating_entity') => [
        'columns' => [
            'entity_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Entity Id',
            ],
            'entity_code' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 64,
                'nullable'  => false,
                'comment'   => 'Entity Code',
            ],
        ],
        'comment' => 'Rating entities',
    ],
    $installer->getTable('rating/rating_option') => [
        'columns' => [
            'option_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Rating Option Id',
            ],
            'rating_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Rating Id',
            ],
            'code' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 32,
                'nullable'  => false,
                'comment'   => 'Rating Option Code',
            ],
            'value' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Rating Option Value',
            ],
            'position' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Ration option position on frontend',
            ],
        ],
        'comment' => 'Rating options',
    ],
    $installer->getTable('rating/rating_option_vote') => [
        'columns' => [
            'vote_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_BIGINT,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Vote id',
            ],
            'option_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Vote option id',
            ],
            'remote_ip' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 16,
                'nullable'  => false,
                'comment'   => 'Customer IP',
            ],
            'remote_ip_long' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_BIGINT,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Customer IP converted to long integer format',
            ],
            'customer_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'default'   => '0',
                'comment'   => 'Customer Id',
            ],
            'entity_pk_value' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_BIGINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Product id',
            ],
            'rating_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Rating id',
            ],
            'review_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_BIGINT,
                'unsigned'  => true,
                'comment'   => 'Review id',
            ],
            'percent' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Percent amount',
            ],
            'value' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Vote option value',
            ],
        ],
        'comment' => 'Rating option values',
    ],
    $installer->getTable('rating/rating_vote_aggregated') => [
        'columns' => [
            'primary_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Vote aggregation id',
            ],
            'rating_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Rating id',
            ],
            'entity_pk_value' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_BIGINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Product id',
            ],
            'vote_count' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Vote dty',
            ],
            'vote_value_sum' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'General vote sum',
            ],
            'percent' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Vote percent',
            ],
            'percent_approved' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'default'   => '0',
                'comment'   => 'Vote percent approved by admin',
            ],
            'store_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Store Id',
            ],
        ],
        'comment' => 'Rating vote aggregated',
    ],
];

$installer->getConnection()->modifyTables($tables);

/**
 * Add indexes
 */
$installer->getConnection()->addIndex(
    $installer->getTable('rating/rating'),
    $installer->getIdxName(
        'rating/rating',
        ['rating_code'],
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE,
    ),
    ['rating_code'],
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE,
);

$installer->getConnection()->addIndex(
    $installer->getTable('rating/rating'),
    $installer->getIdxName('rating/rating', ['entity_id']),
    ['entity_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('rating/rating_entity'),
    $installer->getIdxName(
        'rating/rating_entity',
        ['entity_code'],
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE,
    ),
    ['entity_code'],
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE,
);

$installer->getConnection()->addIndex(
    $installer->getTable('rating/rating_option'),
    $installer->getIdxName('rating/rating_option', ['rating_id']),
    ['rating_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('rating/rating_option_vote'),
    $installer->getIdxName('rating/rating_option_vote', ['option_id']),
    ['option_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('rating/rating_vote_aggregated'),
    $installer->getIdxName('rating/rating_vote_aggregated', ['rating_id']),
    ['rating_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('rating/rating_vote_aggregated'),
    $installer->getIdxName('rating/rating_vote_aggregated', ['store_id']),
    ['store_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('rating/rating_store'),
    $installer->getIdxName('rating/rating_store', ['store_id']),
    ['store_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('rating/rating_title'),
    $installer->getIdxName('rating/rating_title', ['store_id']),
    ['store_id'],
);

/**
 * Add foreign keys
 */
$installer->getConnection()->addForeignKey(
    $installer->getFkName('rating/rating', 'entity_id', 'rating/rating_entity', 'entity_id'),
    $installer->getTable('rating/rating'),
    'entity_id',
    $installer->getTable('rating/rating_entity'),
    'entity_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('rating/rating_option', 'rating_id', 'rating/rating', 'rating_id'),
    $installer->getTable('rating/rating_option'),
    'rating_id',
    $installer->getTable('rating/rating'),
    'rating_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('rating/rating_option_vote', 'review_id', 'review/review', 'review_id'),
    $installer->getTable('rating/rating_option_vote'),
    'review_id',
    $installer->getTable('review/review'),
    'review_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('rating/rating_option_vote', 'option_id', 'rating/rating_option', 'option_id'),
    $installer->getTable('rating/rating_option_vote'),
    'option_id',
    $installer->getTable('rating/rating_option'),
    'option_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('rating/rating_vote_aggregated', 'rating_id', 'rating/rating', 'rating_id'),
    $installer->getTable('rating/rating_vote_aggregated'),
    'rating_id',
    $installer->getTable('rating/rating'),
    'rating_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('rating/rating_vote_aggregated', 'store_id', 'core/store', 'store_id'),
    $installer->getTable('rating/rating_vote_aggregated'),
    'store_id',
    $installer->getTable('core/store'),
    'store_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('rating/rating_store', 'store_id', 'core/store', 'store_id'),
    $installer->getTable('rating/rating_store'),
    'store_id',
    $installer->getTable('core/store'),
    'store_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('rating/rating_store', 'rating_id', 'rating/rating', 'rating_id'),
    $installer->getTable('rating/rating_store'),
    'rating_id',
    $installer->getTable('rating/rating'),
    'rating_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE,
    Varien_Db_Ddl_Table::ACTION_NO_ACTION,
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('rating/rating_title', 'rating_id', 'rating/rating', 'rating_id'),
    $installer->getTable('rating/rating_title'),
    'rating_id',
    $installer->getTable('rating/rating'),
    'rating_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('rating/rating_title', 'store_id', 'core/store', 'store_id'),
    $installer->getTable('rating/rating_title'),
    'store_id',
    $installer->getTable('core/store'),
    'store_id',
);

$installer->endSetup();
