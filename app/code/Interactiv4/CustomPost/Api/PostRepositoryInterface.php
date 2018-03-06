<?php
/**
 * @author Interactiv4 Team
 * @copyright  Copyright © Interactiv4 (https://www.interactiv4.com)
 */

namespace Interactiv4\CustomPost\Api;

/**
 * Interface PostRepositoryInterface
 */
interface PostRepositoryInterface
{
    /**
     * Save custom post.
     *
     * @param \Interactiv4\CustomPost\Api\Data\PostInterface $customPost
     * @return \Interactiv4\CustomPost\Api\Data\PostInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Interactiv4\CustomPost\Api\Data\PostInterface $customPost);

    /**
     * Retrieve custom post by id.
     *
     * @param int $customPostId
     * @return \Interactiv4\CustomPost\Api\Data\PostInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($customPostId);

    /**
     * Retrieve custom post by attribute.
     *
     * @param $value
     * @param string|null $attributeCode
     * @return \Interactiv4\CustomPost\Api\Data\PostInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($value, $attributeCode = null);

    /**
     * Delete custom post.
     *
     * @param \Interactiv4\CustomPost\Api\Data\PostInterface $customPost
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Interactiv4\CustomPost\Api\Data\PostInterface $customPost);

    /**
     * Delete custom post by ID.
     *
     * @param $customPostId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($customPostId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Interactiv4\CustomPost\Api\Data\PostSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);


    /**
     * @param string $shortDescription
     * @return \Interactiv4\CustomPost\Api\Data\PostSearchResultsInterface
     */
    public function getListPostByShortDescription($shortDescription);
}
