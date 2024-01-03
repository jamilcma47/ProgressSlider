<?php declare(strict_types=1);

namespace Slider\Widget\Block\Adminhtml\Renderer;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface as FormElementRenderer;
use Magento\Framework\UrlInterface;

class Renderer extends Template implements FormElementRenderer
{
    /**
     * @var AbstractElement
     */
    private $element;

    /**
     * @var string
     */
    protected $_template = 'Slider_Widget::widget/field/rows/renderer.phtml';

    public function __construct(
        Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        $this->request = $request;
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

    public function getAddButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'label' => __('Add Row'),
                'onclick' => 'return ' . $this->getElement()->getHtmlId() . 'RowsControl.addItem()',
                'class' => 'add'
            ]
        );
        $button->setName('add_row_item_button');
        return $button->toHtml();
    }

    public function getUploadButtonOnClickActionUrl()
    {
        return $this->getUrl(
            'cms/wysiwyg_images/index',
            ['target_element_id' => '__target_element_id__', 'type' => 'file']
        );
    }

    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }


    public function getElement()
    {
        return $this->element;
    }

    public function getValues()
    {
        if (is_array($this->getElement()->getValue())) {
            $values = json_encode($this->getElement()->getValue());
            return json_decode($values, true) ?: [];
        } else {
            $values = str_replace("'", '"', $this->getElement()->getValue());
            return json_decode(str_replace("&#039;", '"', $values), true) ?: [];
        }

    }
}
