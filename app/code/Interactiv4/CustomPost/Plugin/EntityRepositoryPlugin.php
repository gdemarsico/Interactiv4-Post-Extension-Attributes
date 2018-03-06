<?php
/**
 * @author Interactiv4 Team
 * @copyright  Copyright © Interactiv4 (https://www.interactiv4.com)
 */

namespace Interactiv4\CustomPost\Plugin;

use Interactiv4\CustomPost\Api\Data\PostInterface;
use Interactiv4\CustomPost\Api\Data\PostInterfaceFactory;
use Interactiv4\CustomPost\Api\PostRepositoryInterface;
use Interactiv4\Post\Api\Data\EntityExtensionFactory;
use Interactiv4\Post\Api\Data\EntityExtensionInterface;
use Interactiv4\Post\Api\Data\EntityInterface;
use Interactiv4\Post\Api\Data\EntitySearchResultsInterface;
use Interactiv4\Post\Api\EntityRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class EntityRepositoryPlugin
 */
class EntityRepositoryPlugin
{
    /**
     * @var EntityExtensionFactory
     */
    private $entityExtensionFactory;

    /**
     * @var PostInterfaceFactory
     */
    private $customPostFactory;

    /**
     * @var PostRepositoryInterface
     */
    private $customPostRepository;

    /**
     * PostGet constructor.
     *
     * @param EntityExtensionFactory $entityExtensionFactory
     * @param PostInterfaceFactory $customPostFactory
     * @param PostRepositoryInterface $customPostRepository
     */
    public function __construct(
        EntityExtensionFactory $entityExtensionFactory,
        PostInterfaceFactory $customPostFactory,
        PostRepositoryInterface $customPostRepository
    ) {
        $this->entityExtensionFactory = $entityExtensionFactory;
        $this->customPostFactory = $customPostFactory;
        $this->customPostRepository = $customPostRepository;
    }

    /**
     * @param EntityRepositoryInterface $subject
     * @param EntityInterface $result
     * @return EntityInterface
     */
    public function afterGet(
        EntityRepositoryInterface $subject,
        EntityInterface $result
    ) {
        /** @var EntityExtensionInterface $extensionAttributes */
        $extensionAttributes = $result->getExtensionAttributes() ?: $this->entityExtensionFactory->create();

        try {
            /** @var PostInterface $customPost */
            $customPost = $this->customPostRepository->get($result->getId(), PostInterface::FIELD_POST_ID);
        } catch (NoSuchEntityException $e) {
            $result->setExtensionAttributes($extensionAttributes);

            return $result;
        }
        $extensionAttributes->setShortDescription($customPost->getShortDescription());

        $result->setExtensionAttributes($extensionAttributes);

        return $result;
    }

    /**
     * @param EntityRepositoryInterface $subject
     * @param EntitySearchResultsInterface $entities
     * @return EntitySearchResultsInterface
     */
    public function afterGetList(
        EntityRepositoryInterface $subject,
        EntitySearchResultsInterface $entities
    ) {
        foreach ($entities->getItems() as $entity) {
            $this->afterGet($subject, $entity);
        }

        return $entities;
    }

    /**
     * @param EntityRepositoryInterface $subject
     * @param EntityInterface $result
     * @return EntityInterface
     * @throws CouldNotSaveException
     */
    public function afterSave(
        EntityRepositoryInterface $subject,
        EntityInterface $result
    ) {
        $extensionAttributes = $result->getExtensionAttributes() ?: $this->entityExtensionFactory->create();
        if ($extensionAttributes !== null &&
            $extensionAttributes->getShortDescription() !== null
        ) {
            /** @var PostInterface $customPost */
            try {
                $customPost = $this->customPostRepository->get($result->getId(), PostInterface::FIELD_POST_ID);
            } catch (NoSuchEntityException $e) {
                $customPost = $this->customPostFactory->create();
            }
            $customPost->setPostId($result->getId());
            $customPost->setShortDescription($extensionAttributes->getShortDescription());
            $this->customPostRepository->save($customPost);
        }

        return $result;
    }
}
