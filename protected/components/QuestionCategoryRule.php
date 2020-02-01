<?php
/**
 * Класс для работы с URL категорий вопросов
 */
class QuestionCategoryRule extends CBaseUrlRule
{
    protected $_prefix = 'cat/';

    /*
     *      Возможные виды URL категорий:
     *      /cat/алиас - категории верхнего уровня
     *      /cat/алиас_корневой_категории/алиас_категории - категории нижних уровней
     */

    /**
     * Метод создания URL из параметров
     * @param type $manager
     * @param type $route
     * @param type $params
     * @param type $ampersand
     * @return boolean
     */
    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ($route === 'questionCategory/alias') {
            if (isset($params['root'], $params['name'])) {
                return $this->_prefix . $params['root'] . '/' . $params['name'] . Yii::app()->urlManager->urlSuffix;
            } elseif (isset($params['name'])) {
                return $this->_prefix . $params['name'] . Yii::app()->urlManager->urlSuffix;
            }
        }
        return false;  // не применяем данное правило
    }

    /**
     * Парсинг URL категории
     * @param CUrlManager $manager
     * @param CHttpRequest $request
     * @param string $pathInfo
     * @param string $rawPathInfo
     * @return bool|mixed|string
     */
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        $urlPattern = '/^cat(\/[a-z0-9-]+)(\/[a-z0-9-]+)?$/i';
        
        if (preg_match($urlPattern, $pathInfo, $matches)) {
            $matches = array_reverse($matches);
            array_pop($matches);

            if ($matches[0]) {
                $_GET['name'] = str_replace('/', '', $matches[0]);
            }

            return 'questionCategory/alias';
        }
        return false;  // не применяем данное правило
    }
}
