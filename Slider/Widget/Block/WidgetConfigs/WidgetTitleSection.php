<?php declare(strict_types=1);

namespace Slider\Widget\Block\WidgetConfigs;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface as FormElementRenderer;

class WidgetTitleSection extends Template implements FormElementRenderer
{

    /**
     * @var AbstractElement
     */
    private $element;


    /**
     * @var string
     */
    protected $_template = 'Slider_Widget::widget/widget_title_section.phtml';

    /***
     * WidgetTitleSection constructor.
     * @param Template\Context $context
     * @param array $data
     */

    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $this->element = $element;
        return $this->toHtml();
    }

    /**
     * @return AbstractElement
     */
    public function getElement()
    {
        return $this->element;
    }

    /***
     * @return array|mixed|null
     */

    public function getElData()
    {
        return $this->getData('section_name');
    }

    /***
     * @return array|mixed
     */

    public function getValues()
    {
        $values = $this->getElement()->getValue();
        return json_decode(urldecode($values), true) ?: [];
    }
}
