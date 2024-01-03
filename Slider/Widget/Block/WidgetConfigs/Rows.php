<?php declare(strict_types=1);

namespace Slider\Widget\Block\WidgetConfigs;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Slider\Widget\Block\Adminhtml\Renderer\Renderer;

class Rows extends Template
{


    protected $_rows = [];

    /***
     * @param AbstractElement $element
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function prepareElementHtml(AbstractElement $element)
    {
        /** @var Renderer $fieldRenderer */
        $fieldRenderer = $this->getLayout()->createBlock(Renderer::class);
        $fieldRenderer->setRows($this->_rows);
        $element->setRenderer($fieldRenderer);
    }
}
