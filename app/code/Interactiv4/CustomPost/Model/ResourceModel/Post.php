<?php
/**
 * @author Interactiv4 Team
 * @copyright Copyright © Interactiv4 (https://www.interactiv4.com)
 */

namespace Interactiv4\CustomPost\Model\ResourceModel;

use Interactiv4\CustomPost\Api\Data\PostInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Post
 */
class Post extends AbstractDb
{

    /**
     * @inheritdoc
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(PostInterface::SCHEMA_TABLE, PostInterface::FIELD_ID);
    }
}
