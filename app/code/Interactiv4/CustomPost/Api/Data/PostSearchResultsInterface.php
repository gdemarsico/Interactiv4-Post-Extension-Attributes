<?php
/**
 * @author Interactiv4 Team
 * @copyright  Copyright © Interactiv4 (https://www.interactiv4.com)
 */

namespace Interactiv4\CustomPost\Api\Data;

/**
 * Interface PostSearchResultsInterface
 */
interface PostSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Interactiv4\CustomPost\Api\Data\PostInterface[]
     */
    public function getItems();

    /**
     * @param \Interactiv4\CustomPost\Api\Data\PostInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
