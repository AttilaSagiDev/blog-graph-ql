<?php
/**
 * Copyright (c) 2024 Attila Sagi
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 */

declare(strict_types=1);

namespace Space\BlogGraphQl\Model\Resolver\DataProvider;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Space\Blog\Api\PostRepositoryInterface;
use Magento\Store\Model\Store;
use Space\Blog\Api\Data\PostInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class PostList
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
     * Get post list
     *
     * @param int $pageSize
     * @param int $currentPage
     * @param int $storeId
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getPostList(int $pageSize, int $currentPage, int $storeId): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(Store::STORE_ID, [$storeId, Store::DEFAULT_STORE_ID], 'in')
            ->addFilter(PostInterface::IS_ACTIVE, true)
            ->setPageSize($pageSize)
            ->setCurrentPage($currentPage)->create();

        $postListResults = $this->postRepository->getList($searchCriteria);

        if (!$postListResults->getTotalCount()) {
            throw new NoSuchEntityException(__('The post list is empty.'));
        }

        $listSize = $postListResults->getTotalCount();
        $totalPages = 0;
        if ($listSize > 0 && $pageSize > 0) {
            $totalPages = ceil($listSize / $pageSize);
        }

        $postList = [];
        foreach ($postListResults->getItems() as $post) {
            $postList['items'][] = [
                PostInterface::POST_ID => $post->getId(),
                PostInterface::TITLE => $post->getTitle(),
                PostInterface::CONTENT => $post->getContent(),
                PostInterface::AUTHOR => $post->getAuthor(),
                PostInterface::CREATION_TIME => $post->getCreationTime(),
                PostInterface::UPDATE_TIME => $post->getUpdateTime(),
                PostInterface::IS_ACTIVE => $post->isActive()
            ];
        }

        $postList['page_info'] = [
            'total_pages' => $totalPages,
            'page_size' => $pageSize,
            'current_page' => $currentPage,
        ];
        $postList['total_count'] = $listSize;

        return $postList;
    }
}
