<?php declare(strict_types=1);

namespace Slider\Widget\Block\Widgets;

use Magento\Framework\UrlInterface;

class AbstractColumns extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{

    protected $_preparedColumns;

    /**
     * @param string $fieldName
     * @return array
     */

    public function getColumns($fieldName = 'columns')
    {
        if (!isset($this->_preparedColumns)) {
            $this->_preparedColumns = $this->_getRepeatedContent($fieldName);
        }
        return $this->_preparedColumns;
    }

    public function getImageUrlByPath($path)
    {
        return $this->_storeManager
                ->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $path;
    }

    /**
     * @param $field
     * @return array
     */

    protected function _getRepeatedContent($field)
    {
        $preparedContent = [];
        $content = $this->getData($field);
        $content = str_replace(['&quot;', "'"], '"', $content);
        $content = json_decode($content, true);
        if (is_array($content)) {
            foreach ($content as $data) {
                $preparedContent[] = new \Magento\Framework\DataObject($data);
            }
        }
        return $preparedContent;
    }

    /**
     * @return string
     */

    public function getImageUrl($imageField = 'image')
    {
        $url = $this->getData($imageField);
        if (!$url) {
            return '';
        }

        if (strpos($url, $this->getMediaUrl()) !== 0) {
            return $this->getMediaUrl() . '/' . $url;
        }

        return $url;
    }

    /**
     * @return string
     */

    public function getMediaUrl()
    {
        return $this->_storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @param $urlLink
     * @return string
     */

    public function getPreparedUrl($urlLink)
    {
        $preparedLink = $urlLink;
        if (strpos($urlLink, 'http') === false) {
            $urlLink = explode('?', $urlLink);
            $preparedLink = $this->getUrl(trim($urlLink[0], '/'));
            if (!empty($urlLink[1])) {
                $preparedLink = rtrim($preparedLink, '/') . '?' . $urlLink[1];
            }
        }
        return $preparedLink;
    }

    /**
     * @param string $descriptionField
     * @return string|string[]
     */

    public function getPreparedDescription($descriptionField = 'description')
    {
        return str_replace('\EOL', '<br />', $this->getData($descriptionField));
    }

}
