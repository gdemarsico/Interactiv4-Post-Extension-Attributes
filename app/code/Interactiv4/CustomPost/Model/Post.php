<?php
/**
 * @author Interactiv4 Team
 * @copyright Copyright © Interactiv4 (https://www.interactiv4.com)
 */

namespace Interactiv4\CustomPost\Model;

use Interactiv4\CustomPost\Api\Data\PostExtensionInterface;
use Interactiv4\CustomPost\Api\Data\PostInterface;
use Interactiv4\CustomPost\Model\ResourceModel\Post as ResourceModelPost;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Post
 */
class Post extends AbstractExtensibleModel implements PostInterface
{
    /**
     * @inheritdoc
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(ResourceModelPost::class);
    }

    /**
     * @inheritdoc
     */
    public function getPostId()
    {
        return $this->getData(self::FIELD_POST_ID);
    }

    /**
     * @inheritdoc
     */
    public function setPostId($postId)
    {
        return $this->setData(self::FIELD_POST_ID, $postId);
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->getData(self::FIELD_SHORT_DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    public function setShortDescription($shortDescription)
    {
        return $this->setData(self::FIELD_SHORT_DESCRIPTION, $shortDescription);
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(PostExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
