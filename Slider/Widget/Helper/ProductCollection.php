<?php declare(strict_types=1);

namespace Slider\Widget\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\CategoryRepository;

class ProductCollection extends AbstractHelper
{

    /**
     * ProductCollection constructor.
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param CategoryRepository $categoryRepository
     */

    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        CategoryRepository $categoryRepository
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context);
    }

    /**
     * @param $id
     * @return mixed
     */

    public function getProductCollection($id)
    {
        $category = $this->categoryRepository->get($id);
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoryFilter($category);
        $collection->setOrder('position', 'asc');
        $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('status',
            \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        return $collection;
    }

}
