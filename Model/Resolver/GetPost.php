<?php
/**
 * Copyright (c) 2024 Attila Sagi
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 */

declare(strict_types=1);

namespace Space\BlogGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Space\BlogGraphQl\Model\Resolver\DataProvider\Post as PostDataProvider;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;

class GetPost implements ResolverInterface
{
    /**
     * @var PostDataProvider
     */
    private PostDataProvider $postDataProvider;

    /**
     * Constructor
     *
     * @param PostDataProvider $postDataProvider
     */
    public function __construct(
        PostDataProvider $postDataProvider
    ) {
        $this->postDataProvider = $postDataProvider;
    }

    /**
     * Resolver
     *
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws GraphQlInputException
     * @throws GraphQlNoSuchEntityException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array {
        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        if (!isset($args['post_id']) || $args['post_id'] < 1) {
            throw new GraphQlInputException(__('Post ID is required and value must be greater than 0.'));
        }

        try {
            $postData = $this->postDataProvider->getPostById($args['post_id'], $storeId);
        } catch (NoSuchEntityException|LocalizedException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()));
        }

        return $postData;
    }
}
