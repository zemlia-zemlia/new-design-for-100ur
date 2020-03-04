<?php
/**
 * ImperaviRedactorWidget class file.
 *
 * @property string $assetsPath
 * @property string $assetsUrl
 * @property array  $plugins
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 *
 * @see https://github.com/yiiext/imperavi-redactor-widget
 * @see http://imperavi.com/redactor
 *
 * @license https://github.com/yiiext/imperavi-redactor-widget/blob/master/license.md
 */
class ImperaviRedactorWidget extends CInputWidget
{
    /**
     * Assets package ID.
     */
    const PACKAGE_ID = 'imperavi-redactor';

    /**
     * @var array {@link http://imperavi.com/redactor/docs/ redactor options}.
     */
    public $options = [];

    /**
     * @var string|null Selector pointing to textarea to initialize redactor for.
     *                  Defaults to null meaning that textarea does not exist yet and will be
     *                  rendered by this widget.
     */
    public $selector;

    /**
     * @var array
     */
    public $package = [];

    /**
     * @var array
     */
    private $_plugins = [];

    /**
     * Init widget.
     */
    public function init()
    {
        parent::init();

        if (null === $this->selector) {
            list($this->name, $this->id) = $this->resolveNameID();
            $this->htmlOptions['id'] = $this->getId();
            $this->selector = '#' . $this->getId();

            if ($this->hasModel()) {
                echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
            } else {
                echo CHtml::textArea($this->name, $this->value, $this->htmlOptions);
            }
        }

        $this->registerClientScript();
    }

    /**
     * Register CSS and Script.
     */
    protected function registerClientScript()
    {
        // Prepare script package.
        $this->package = array_merge([
                'baseUrl' => $this->getAssetsUrl(),
                'js' => [
                    YII_DEBUG ? 'redactor.js' : 'redactor.min.js',
                ],
                'css' => [
                    'redactor.css',
                ],
                'depends' => [
                    'jquery',
                ],
            ], $this->package);

        // Append language file to script package.
        if (isset($this->options['lang']) && 'en' !== $this->options['lang']) {
            $this->package['js'][] = 'lang/' . $this->options['lang'] . '.js';
        }

        // Add assets url to relative css.
        if (isset($this->options['css'])) {
            if (!is_array($this->options['css'])) {
                $this->options['css'] = [$this->options['css']];
            }
            foreach ($this->options['css'] as $i => $css) {
                if (false === strpos($css, '/')) {
                    $this->options['css'][$i] = $this->getAssetsUrl() . '/' . $css;
                }
            }
        }

        // Insert plugins in options
        if (!empty($this->_plugins)) {
            $this->options['plugins'] = array_keys($this->_plugins);
        }

        $clientScript = Yii::app()->getClientScript();
        $selector = CJavaScript::encode($this->selector);
        $options = CJavaScript::encode($this->options);

        $clientScript
            ->addPackage(self::PACKAGE_ID, $this->package)
            ->registerPackage(self::PACKAGE_ID)
            ->registerScript(
                $this->id,
                'jQuery(' . $selector . ').redactor(' . $options . ');',
                CClientScript::POS_READY
            );

        foreach ($this->getPlugins() as $id => $plugin) {
            $clientScript
                ->addPackage(self::PACKAGE_ID . '-' . $id, $plugin)
                ->registerPackage(self::PACKAGE_ID . '-' . $id);
        }
    }

    /**
     * Get the assets path.
     *
     * @return string
     */
    public function getAssetsPath()
    {
        return  dirname(__FILE__) . '/assets';
    }

    /**
     * Publish assets and return url.
     *
     * @return string
     */
    public function getAssetsUrl()
    {
        return Yii::app()->getAssetManager()->publish($this->getAssetsPath());
    }

    /**
     * @param array $plugins
     */
    public function setPlugins(array $plugins)
    {
        foreach ($plugins as $id => $plugin) {
            if (!isset($plugin['baseUrl']) && !isset($plugin['basePath'])) {
                $plugin['baseUrl'] = $this->getAssetsUrl() . '/plugins/' . $id;
            }

            $this->_plugins[$id] = $plugin;
        }
    }

    /**
     * @return array
     */
    public function getPlugins()
    {
        return $this->_plugins;
    }
}
