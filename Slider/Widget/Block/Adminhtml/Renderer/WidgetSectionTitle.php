<?php declare(strict_types=1);

namespace Slider\Widget\Block\Adminhtml\Renderer;

use Slider\Widget\Block\WidgetConfigs\WidgetTitleSection;
use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;

class WidgetSectionTitle extends Template
{

    public function prepareElementHtml(AbstractElement $element)
    {
        $fieldRenderer = $this->getLayout()->createBlock(WidgetTitleSection::class);
        $element->setRenderer($fieldRenderer);
    }
}
