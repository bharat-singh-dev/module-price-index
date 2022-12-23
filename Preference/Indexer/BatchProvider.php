<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SixtySeven\PriceIndex\Preference\Indexer;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;

/**
 * Generator of consecutive entity ID ranges that must be handled as a batch.
 *
 * Some ranges may contain non existent entity IDs.
 * So the code that uses the generator must check if any entities were loaded during batch load.
 */
class BatchProvider extends \Magento\Framework\Indexer\BatchProvider
{
    /**
     * @inheritdoc
     */
    public function getBatches(AdapterInterface $adapter, $tableName, $linkField, $batchSize, $typeId=null)
    {
        $sql = $adapter->select()->from(
                ['entity' => $tableName],
                [
                    'max_value' => new \Zend_Db_Expr('COUNT(*)')
                ]
            );
        if(!empty($typeId)){
           $sql = $adapter->select()->from(
                ['entity' => $tableName],
                [
                    'max_value' => new \Zend_Db_Expr('COUNT(*)')
                ]
            )->where('type_id = ?', $typeId); 
        }

        $maxLinkFieldValue = $adapter->fetchOne($sql);

        $fullBatchCount = ceil($maxLinkFieldValue / $batchSize);

        for ($batchIndex = 0; $batchIndex < $fullBatchCount; $batchIndex ++) {
            yield ['limit' => $batchSize, 'offset' => $batchIndex * $batchSize];
        }
    }

    /**
     * @inheritdoc
     */
    public function getBatchIds(
        AdapterInterface $connection,
        Select $select,
        array $batch
    ) {
        $ids = $connection->fetchCol($select->order('entity_id')->limit($batch['limit'], $batch['offset']));
        return array_map('intval', $ids);
    }
}
