<?php
/**
 * Copyright (c) 2024 Attila Sagi
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 */

declare(strict_types=1);

namespace Space\BlogGraphQl\Model\Resolver\DataProvider;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Space\Blog\Api\PostRepositoryInterface;
use Space\Blog\Api\Data\PostInterface;
use Magento\Store\Model\Store;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Post
{
    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var PostRepositoryInterface
     */
    private PostRepositoryInterface $postRepository;

    /**
     * Constructor
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        PostRepositoryInterface $postRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->postRepository = $postRepository;
    }

    /**
     * Get post by ID
     *
     * @param int $postId
     * @param int $storeId
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getPostById(int $postId, int $storeId): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(PostInterface::POST_ID, $postId)
            ->addFilter(Store::STORE_ID, [$storeId, Store::DEFAULT_STORE_ID], 'in')
            ->addFilter(PostInterface::IS_ACTIVE, true)->create();

        $postResults = $this->postRepository->getList($searchCriteria)->getItems();

        if (empty($postResults)) {
            throw new NoSuchEntityException(
                __('The post with the "%1" ID doesn\'t exist.', $postId)
            );
        }

        $post = current($postResults);
        return [
            PostInterface::POST_ID => $post->getId(),
            PostInterface::TITLE => $post->getTitle(),
            PostInterface::CONTENT => $post->getContent(),
            PostInterface::AUTHOR => $post->getAuthor(),
            PostInterface::CREATION_TIME => $post->getCreationTime(),
            PostInterface::UPDATE_TIME => $post->getUpdateTime(),
            PostInterface::IS_ACTIVE => $post->isActive()
        ];
    }
}
