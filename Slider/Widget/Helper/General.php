<?php declare(strict_types=1);

namespace Slider\Widget\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Swatches\Helper\Data;
use Magento\Catalog\Helper\Image;


class General extends AbstractHelper
{

    /**
     * General constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManagerInterface
     * @param \Magento\Review\Model\ReviewFactory $reviewFactory
     * @param ProductRepositoryInterface $productRepository
     * @param Image $imageHelper
     * @param Data $swatchHelper
     * @param Registry $registry
     */

    public function __construct(

        Context $context,
        StoreManagerInterface $storeManagerInterface,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        ProductRepositoryInterface $productRepository,
        Image $imageHelper,
        Data $swatchHelper,
        Registry $registry
    ) {
        $this->storeManagerInterface = $storeManagerInterface;
        $this->reviewFactory = $reviewFactory;
        $this->reviewFactory = $productRepository;
        $this->swatchHelper = $swatchHelper;
        $this->imageHelper = $imageHelper;
        $this->registry = $registry;
        parent::__construct($context);
    }

    /**
     * @param $product
     */

    public function getReviewsSummary($product)
    {
        $storeId = $this->storeManagerInterface->getStore()->getId();
        $this->reviewFactory->create()->getEntitySummary($product, $storeId);
    }

    /**
     * @param $product
     * @return mixed
     */

    public function getRatingSummary($product)
    {
        return $product->getRatingSummary()->getRatingSummary();
    }


    /* Getting number from percentage, Magento 2 is
    returning values in percentages so we
    have 40%/100% * the number of stars */

    /**
     * @param $percentage
     * @return float|int
     */

    public function getReviewsNumber($percentage)
    {
        return $percentage / 100 * 5;
    }

    /**
     * @return mixed
     */

    public function getCurrentProduct()
    {
        $product = $this->registry->registry('current_product');
        return $product;
    }

    /**
     * @param $optionid
     * @return mixed
     */

    public function getAtributeSwatchHashcode($optionid)
    {
        $hashcodeData = $this->swatchHelper->getSwatchesByOptionsId([$optionid]);
        if ($optionid) {
            return $hashcodeData[$optionid]['value'];
        }
    }


    /**
     * @param $color
     * @param false $opacity
     * @return string
     */

    function hex2rgba($color, $opacity = false)
    {
        $default = 'rgb(0,0,0)';
        //Return default if no color provided
        if (empty($color)) {
            return $default;
        }

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1) {
                $opacity = 1.0;
            }
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }


    /**
     * @param $productID
     * @return mixed
     */

    public function getMenuImage($productID)
    {
        $product = $this->reviewFactory->getById($productID);
        return $this->imageHelper->init($product, 'product_base_image')->setImageFile($product->getImage())->getUrl();
    }
}

