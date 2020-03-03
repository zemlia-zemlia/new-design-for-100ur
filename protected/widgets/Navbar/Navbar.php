<?php

class Navbar extends CWidget
{
    public $brand;

    public $icon;

    public $brandUrl = '#';

    public $htmlOptions = [];

    public $items = [];

    public function init()
    {
        if (!isset($this->brand)) {
            $this->brand = CHtml::encode(Yii::app()->name);
        }
    }

    public function run()
    {
        $this->navbarContent();
    }

    public function navbarContent()
    {
        echo CHtml::openTag('header', ['class' => 'main-header']);

        $logo = CHtml::tag('span', ['class' => 'logo-lg'], $this->brand, true);
        $logo .= CHtml::tag('span', ['class' => 'logo-mini'], $this->icon, true);

        echo CHtml::link($logo, $this->brandUrl, ['class' => 'logo']);

        echo CHtml::openTag('nav', ['class' => 'navbar navbar-static-top', 'role' => 'navigation']);

        echo '<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
            </a>';

        if (!empty($this->items)) {
            echo CHtml::openTag('div', $this->htmlOptions);

            echo CHtml::openTag('ul', ['class' => 'nav navbar-nav']);

            foreach ($this->items as $item) {
                if (is_string($item)) {
                    echo $item;
                } else {
                    if (isset($item['class'])) {
                        $className = $item['class'];
                        unset($item['class']);

                        $this->controller->widget($className, $item);
                    } else {
                        echo CHtml::openTag('li', isset($item['htmlOptions']) ? $item['htmlOptions'] : []);

                        if (is_array($item['url'])) {
                            echo CHtml::link($item['label'], implode('', $item['url']), isset($item['linkOptions']) ? $item['linkOptions'] : []);
                        } else {
                            echo CHtml::link($item['label'], $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : []);
                        }

                        echo CHtml::closeTag('li');
                    }
                }
            }

            echo CHtml::closeTag('ul');

            echo CHtml::closeTag('div');
        }

        echo CHtml::closeTag('nav');

        echo CHtml::closeTag('header');
    }
}
