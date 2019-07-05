<?php
/**
 * @author Interactiv4 Team
 * @copyright Copyright © Interactiv4 (https://www.interactiv4.com)
 */

namespace Interactiv4\CustomPost\Model\ResourceModel\Post;

use Interactiv4\CustomPost\Model\ResourceModel\Post as ResourceModelPost;
use Interactiv4\CustomPost\Model\Post as ModelPost;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(ModelPost::class, ResourceModelPost::class);
    }
}
