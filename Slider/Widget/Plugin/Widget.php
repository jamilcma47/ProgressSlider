<?php declare(strict_types=1);

namespace Slider\Widget\Plugin;

use Magento\Framework\DataObject;
use Magento\Framework\Escaper;
use Magento\Widget\Helper\Conditions;

class Widget
{

    /**
     * Widget constructor.
     * @param Escaper $escaper
     * @param Conditions $conditionsHelper
     */

    public function __construct(
        Escaper $escaper,
        Conditions $conditionsHelper
    ) {
        $this->escaper = $escaper;
        $this->conditionsHelper = $conditionsHelper;
    }

    /**
     * @param \Magento\Widget\Model\Widget $subject
     * @param \Closure $proceed
     * @param $type
     * @param array $params
     * @param bool $asIs
     * @return string
     */

    public function aroundGetWidgetDeclaration(
        \Magento\Widget\Model\Widget $subject,
        \Closure $proceed,
        $type,
        $params = [],
        $asIs = true
    ) {

        $widget = $subject->getConfigAsObject($type);

        $params = array_filter($params, function ($value) {
            return $value !== null && $value !== '';
        });


        $directiveParams = '';
        foreach ($params as $name => $value) {
            // Retrieve default option value if pre-configured
            $directiveParams .= $this->getDirectiveParam($widget, $name, $value);
        }

        $directive = sprintf('{{widget type="%s"%s%s}}', $type, $directiveParams, $this->getWidgetPageVarName($params));

        if ($asIs) {
            return $directive;
        }

        return sprintf(
            '<img id="%s" src="%s" title="%s">',
            $this->idEncode($directive),
            $subject->getPlaceholderImageUrl($type),
            $this->escaper->escapeUrl($directive)
        );
    }

    /**
     * @param DataObject $widget
     * @param string $name
     * @param $value
     * @return string
     */

    private function getDirectiveParam(DataObject $widget, string $name, $value): string
    {
        $v = false;

        if ($name === 'conditions') {
            $name = 'conditions_encoded';
            $value = $this->conditionsHelper->encode($value);
        } elseif (is_string($value) && strrpos($value, PHP_EOL) !== false) {
            $value = str_replace('"', "'", $value);
        } elseif (is_array($value) && $name != 'conditions') {
            $value = $this->_prepareArrayValue($value);
        } elseif (is_array($value)) {
            $value = implode(',', $value);
        } elseif (trim($value) === '') {
            $parameters = $widget->getParameters();
            if (isset($parameters[$name]) && is_object($parameters[$name])) {
                $value = $parameters[$name]->getValue();
            }
        } else {
            $value = $this->getPreparedValue($value);
        }

        return sprintf(' %s="%s"', $name, $this->escaper->escapeHtmlAttr($value, false));
    }

    /***
     * @param $data
     * @return false|mixed|string|string[]
     */

    protected function _prepareArrayValue($data)
    {
        $preparedValue = [];
        $asJson = false;
        foreach ($data as $value) {
            if (is_array($value)) {
                $asJson = true;
                if (!empty($value['delete'])) {
                    continue;
                }
                $preparedValue[] = $value;
            }
        }


        return $asJson ? str_replace('"', "'", json_encode($preparedValue)) : $data;
    }

    /**
     * @param array $params
     * @return string
     */

    private function getWidgetPageVarName($params = [])
    {
        $pageVarName = '';
        if (array_key_exists('show_pager', $params) && (bool)$params['show_pager']) {
            $pageVarName = sprintf(
                ' %s="%s"',
                'page_var_name',
                'p' . $this->getMathRandom()->getRandomString(5, \Magento\Framework\Math\Random::CHARS_LOWERS)
            );
        }
        return $pageVarName;
    }

    /**
     * @return mixed
     */

    private function getMathRandom()
    {
        if ($this->mathRandom === null) {
            $this->mathRandom = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\Math\Random::class);
        }
        return $this->mathRandom;
    }

    /***
     * @param $string
     * @return string
     */

    private function idEncode($string)
    {
        return strtr(base64_encode($string), '+/=', ':_-');
    }

    /**
     * @param string $value
     * @return string
     */

    private function getPreparedValue(string $value): string
    {
        $pattern = sprintf('/%s/', implode('|', ['}', '{']));

        return preg_match($pattern, $value) ? rawurlencode($value) : $value;
    }
}
