<?php
/**
 * @author Interactiv4 Team
 * @copyright  Copyright © Interactiv4 (https://www.interactiv4.com)
 */

namespace Interactiv4\CustomPost\Model;

use Exception;
use Interactiv4\CustomPost\Api\Data\PostInterface;
use Interactiv4\CustomPost\Api\Data\PostSearchResultsInterface;
use Interactiv4\CustomPost\Api\Data\PostSearchResultsInterfaceFactory;
use Interactiv4\CustomPost\Api\PostRepositoryInterface;
use Interactiv4\CustomPost\Model\PostFactory;
use Interactiv4\CustomPost\Model\ResourceModel\Post as ResourceCustomPost;
use Interactiv4\CustomPost\Model\ResourceModel\Post\Collection;
use Interactiv4\CustomPost\Model\ResourceModel\Post\CollectionFactory;
use Interactiv4\Post\Api\Data\EntityInterface;
use Interactiv4\Post\Api\EntityRepositoryInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteria;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;

/**
 * Class PostRepository
 */
class PostRepository implements PostRepositoryInterface
{
    /**
     * @var ResourceCustomPost $resourceCustomPost
     */
    private $resourceCustomPost;

    /**
     * @var PostFactory
     */
    private $customPostFactory;

    /**
     * @var CollectionFactory
     */
    private $customPostCollectionFactory;

    /**
     * @var PostSearchResultsInterfaceFactory
     */
    private $customPostSearchResultInterfaceFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var EntityRepositoryInterface
     */
    private $entityRepository;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrder
     */
    private $sortOrder;

    /**
     * PostRepository constructor.
     *
     * @param ResourceCustomPost $resourceCustomPost
     * @param PostFactory $customPostFactory
     * @param CollectionFactory $customPostCollectionFactory
     * @param PostSearchResultsInterfaceFactory $customPostSearchResultInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param EntityRepositoryInterface $entityRepository
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrder $sortOrder
     */
    public function __construct(
        ResourceCustomPost $resourceCustomPost,
        PostFactory $customPostFactory,
        CollectionFactory $customPostCollectionFactory,
        PostSearchResultsInterfaceFactory $customPostSearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        EntityRepositoryInterface $entityRepository,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrder $sortOrder
    ) {
        $this->resourceCustomPost = $resourceCustomPost;
        $this->customPostFactory = $customPostFactory;
        $this->customPostCollectionFactory = $customPostCollectionFactory;
        $this->customPostSearchResultInterfaceFactory = $customPostSearchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->entityRepository = $entityRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrder = $sortOrder;
    }

    /**
     * @inheritdoc
     */
    public function save(PostInterface $customPost)
    {
        $this->resourceCustomPost->save($customPost);

        return $customPost;
    }

    /**
     * @inheritdoc
     */
    public function getById($customPostId)
    {
        return $this->get($customPostId);
    }

    /**
     * @inheritdoc
     */
    public function get($value, $attributeCode = null)
    {
        /** @var Post $customPost */
        $customPost = $this->customPostFactory->create()->load($value, $attributeCode);

        if (!$customPost->getId()) {
            throw new NoSuchEntityException(__('Unable to find custom post'));
        }

        return $customPost;
    }

    /**
     * @inheritdoc
     */
    public function delete(PostInterface $customPost)
    {
        $customPostId = $customPost->getId();
        try {
            $this->resourceCustomPost->delete($customPost);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new StateException(
                __('Unable to remove custom post %1', $customPostId)
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($customPostId)
    {
        $customPost = $this->getById($customPostId);

        return $this->delete($customPost);
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->customPostCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process($collection, PostInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var PostSearchResultsInterface $searchResults */
        $searchResults = $this->customPostSearchResultInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function getListPostByShortDescription($shortDescription)
    {

        $filter = $this->filterBuilder
            ->setField(PostInterface::FIELD_SHORT_DESCRIPTION)
            ->setValue($shortDescription)
            ->setConditionType('LIKE')
            ->create();

        /** @var SearchCriteria $searchCriteria */
        $searchCriteria = $this->searchCriteriaBuilder->addFilter($filter);

        $sortOrder = $this->sortOrder->setField(EntityInterface::NAME)->setDirection('asc');
        $searchCriteria->setSortOrders([$sortOrder]);
        $entitySearchResults = $this->entityRepository->getList($searchCriteria);

        return $entitySearchResults;
    }
}
