<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Newsletter
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

/**
 * Drop foreign keys
 */
$installer->getConnection()->dropForeignKey(
    $installer->getTable('newsletter/problem'),
    'FK_PROBLEM_QUEUE',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('newsletter/problem'),
    'FK_PROBLEM_SUBSCRIBER',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('newsletter/queue'),
    'FK_QUEUE_TEMPLATE',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('newsletter/queue_link'),
    'FK_QUEUE_LINK_SUBSCRIBER',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('newsletter/subscriber'),
    'FK_NEWSLETTER_SUBSCRIBER_STORE',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('newsletter/queue_store_link'),
    'FK_LINK_QUEUE',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('newsletter/queue_store_link'),
    'FK_NEWSLETTER_QUEUE_STORE_LINK_STORE',
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('newsletter/queue_link'),
    'FK_QUEUE_LINK_QUEUE',
);

/**
 * Drop indexes
 */
$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/problem'),
    'FK_PROBLEM_SUBSCRIBER',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/problem'),
    'FK_PROBLEM_QUEUE',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/queue'),
    'FK_QUEUE_TEMPLATE',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/queue_link'),
    'FK_QUEUE_LINK_SUBSCRIBER',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/queue_link'),
    'FK_QUEUE_LINK_QUEUE',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/queue_link'),
    'IDX_NEWSLETTER_QUEUE_LINK_SEND_AT',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/queue_store_link'),
    'FK_NEWSLETTER_QUEUE_STORE_LINK_STORE',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/subscriber'),
    'FK_SUBSCRIBER_CUSTOMER',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/subscriber'),
    'FK_NEWSLETTER_SUBSCRIBER_STORE',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/template'),
    'TEMPLATE_ACTUAL',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/template'),
    'ADDED_AT',
);

$installer->getConnection()->dropIndex(
    $installer->getTable('newsletter/template'),
    'MODIFIED_AT',
);

/**
 * Change columns
 */
$tables = [
    $installer->getTable('newsletter/subscriber') => [
        'columns' => [
            'subscriber_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Subscriber Id',
            ],
            'store_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'default'   => '0',
                'comment'   => 'Store Id',
            ],
            'change_status_at' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'comment'   => 'Change Status At',
            ],
            'customer_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Customer Id',
            ],
            'subscriber_email' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 150,
                'nullable'  => false,
                'comment'   => 'Subscriber Email',
            ],
            'subscriber_status' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Subscriber Status',
            ],
            'subscriber_confirm_code' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 32,
                'default'   => 'NULL',
                'comment'   => 'Subscriber Confirm Code',
            ],
        ],
        'comment' => 'Newsletter Subscriber',
    ],
    $installer->getTable('newsletter/queue') => [
        'columns' => [
            'queue_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Queue Id',
            ],
            'template_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Template Id',
            ],
            'newsletter_type' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'comment'   => 'Newsletter Type',
            ],
            'newsletter_text' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => '64K',
                'comment'   => 'Newsletter Text',
            ],
            'newsletter_styles' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => '64K',
                'comment'   => 'Newsletter Styles',
            ],
            'newsletter_subject' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 200,
                'comment'   => 'Newsletter Subject',
            ],
            'newsletter_sender_name' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 200,
                'comment'   => 'Newsletter Sender Name',
            ],
            'newsletter_sender_email' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 200,
                'comment'   => 'Newsletter Sender Email',
            ],
            'queue_status' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Queue Status',
            ],
            'queue_start_at' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'comment'   => 'Queue Start At',
            ],
            'queue_finish_at' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'comment'   => 'Queue Finish At',
            ],
        ],
        'comment' => 'Newsletter Queue',
    ],
    $installer->getTable('newsletter_queue_link') => [
        'columns' => [
            'queue_link_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Queue Link Id',
            ],
            'queue_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Queue Id',
            ],
            'subscriber_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Subscriber Id',
            ],
            'letter_sent_at' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'comment'   => 'Letter Sent At',
            ],
        ],
        'comment' => 'Newsletter Queue Link',
    ],
    $installer->getTable('newsletter_queue_store_link') => [
        'columns' => [
            'queue_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'default'   => '0',
                'comment'   => 'Queue Id',
            ],
            'store_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'default'   => '0',
                'comment'   => 'Store Id',
            ],
        ],
        'comment' => 'Newsletter Queue Store Link',
    ],
    $installer->getTable('newsletter/template') => [
        'columns' => [
            'template_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Template Id',
            ],
            'template_code' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 150,
                'comment'   => 'Template Code',
            ],
            'template_text' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => '64K',
                'comment'   => 'Template Text',
            ],
            'template_text_preprocessed' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => '64K',
                'comment'   => 'Template Text Preprocessed',
            ],
            'template_styles' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => '64K',
                'comment'   => 'Template Styles',
            ],
            'template_type' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'comment'   => 'Template Type',
            ],
            'template_subject' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 200,
                'comment'   => 'Template Subject',
            ],
            'template_sender_name' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 200,
                'comment'   => 'Template Sender Name',
            ],
            'template_sender_email' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 200,
                'comment'   => 'Template Sender Email',
            ],
            'template_actual' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'default'   => '1',
                'comment'   => 'Template Actual',
            ],
            'added_at' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'comment'   => 'Added At',
            ],
            'modified_at' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'comment'   => 'Modified At',
            ],
        ],
        'comment' => 'Newsletter Template',
    ],
    $installer->getTable('newsletter/problem') => [
        'columns' => [
            'problem_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Problem Id',
            ],
            'subscriber_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'comment'   => 'Subscriber Id',
            ],
            'queue_id' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Queue Id',
            ],
            'problem_error_code' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'default'   => '0',
                'comment'   => 'Problem Error Code',
            ],
            'problem_error_text' => [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 200,
                'comment'   => 'Problem Error Text',
            ],
        ],
        'comment' => 'Newsletter Problems',
    ],
];

$installer->getConnection()->modifyTables($tables);

/**
 * Add indexes
 */
$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/problem'),
    $installer->getIdxName('newsletter/problem', ['subscriber_id']),
    ['subscriber_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/problem'),
    $installer->getIdxName('newsletter/problem', ['queue_id']),
    ['queue_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/queue'),
    $installer->getIdxName('newsletter/queue', ['template_id']),
    ['template_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/queue_link'),
    $installer->getIdxName('newsletter/queue_link', ['subscriber_id']),
    ['subscriber_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/queue_link'),
    $installer->getIdxName('newsletter/queue_link', ['queue_id']),
    ['queue_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/queue_link'),
    $installer->getIdxName('newsletter/queue_link', ['queue_id', 'letter_sent_at']),
    ['queue_id', 'letter_sent_at'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/queue_store_link'),
    $installer->getIdxName('newsletter/queue_store_link', ['store_id']),
    ['store_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/subscriber'),
    $installer->getIdxName('newsletter/subscriber', ['customer_id']),
    ['customer_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/subscriber'),
    $installer->getIdxName('newsletter/subscriber', ['store_id']),
    ['store_id'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/template'),
    $installer->getIdxName('newsletter/template', ['template_actual']),
    ['template_actual'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/template'),
    $installer->getIdxName('newsletter/template', ['added_at']),
    ['added_at'],
);

$installer->getConnection()->addIndex(
    $installer->getTable('newsletter/template'),
    $installer->getIdxName('newsletter/template', ['modified_at']),
    ['modified_at'],
);

/**
 * Add foreign keys
 */
$installer->getConnection()->addForeignKey(
    $installer->getFkName('newsletter/subscriber', 'store_id', 'core/store', 'store_id'),
    $installer->getTable('newsletter/subscriber'),
    'store_id',
    $installer->getTable('core/store'),
    'store_id',
    Varien_Db_Ddl_Table::ACTION_SET_NULL,
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('newsletter/problem', 'queue_id', 'newsletter/queue', 'queue_id'),
    $installer->getTable('newsletter/problem'),
    'queue_id',
    $installer->getTable('newsletter/queue'),
    'queue_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('newsletter/problem', 'subscriber_id', 'newsletter/subscriber', 'subscriber_id'),
    $installer->getTable('newsletter/problem'),
    'subscriber_id',
    $installer->getTable('newsletter/subscriber'),
    'subscriber_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('newsletter/queue', 'template_id', 'newsletter/template', 'template_id'),
    $installer->getTable('newsletter/queue'),
    'template_id',
    $installer->getTable('newsletter/template'),
    'template_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('newsletter/queue_link', 'queue_id', 'newsletter/queue', 'queue_id'),
    $installer->getTable('newsletter/queue_link'),
    'queue_id',
    $installer->getTable('newsletter/queue'),
    'queue_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('newsletter/queue_link', 'subscriber_id', 'newsletter/subscriber', 'subscriber_id'),
    $installer->getTable('newsletter/queue_link'),
    'subscriber_id',
    $installer->getTable('newsletter/subscriber'),
    'subscriber_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('newsletter/queue_store_link', 'queue_id', 'newsletter/queue', 'queue_id'),
    $installer->getTable('newsletter/queue_store_link'),
    'queue_id',
    $installer->getTable('newsletter/queue'),
    'queue_id',
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('newsletter/queue_store_link', 'store_id', 'core/store', 'store_id'),
    $installer->getTable('newsletter/queue_store_link'),
    'store_id',
    $installer->getTable('core/store'),
    'store_id',
);

$installer->endSetup();
