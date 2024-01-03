<?php declare(strict_types=1);

namespace Slider\Widget\Block\Adminhtml;

use Slider\Widget\Block\WidgetConfigs\Rows;

class SliderMultiField extends Rows
{
    protected $_rows = [
        'desktop_thumbnail' => [
            'label' => 'Desktop Image',
            'type' => 'image'
        ],
        'mobile_thumbnail' => [
            'label' => 'Mobile Image',
            'type' => 'image'
        ],
        'first_title' => [
            'label' => 'First Line Title',
            'type' => 'text'
        ],
        'second_title' => [
            'label' => 'Second Line Title',
            'type' => 'text'
        ],
        'description' => [
            'label' => 'Description',
            'type' => 'textarea'
        ],
        'button' => [
            'label' => 'Button text',
            'type' => 'text'
        ],
        'button_url' => [
            'label' => 'Button url',
            'type' => 'text'
        ]
    ];
}
