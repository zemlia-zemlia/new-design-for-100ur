<?php
/**
 * Класс для работы с URL категорий вопросов.
 *
 * Класс устарел и не используется!
 */
class QuestionCategoryRule extends CBaseUrlRule
{
    protected $_prefix = 'cat/';

    /**
     * Метод создания URL из параметров.
     *
     * @param type $manager
     * @param type $route
     * @param type $params
     * @param type $ampersand
     *
     * @return bool
     */
    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ('questionCategory/alias' === $route) {
            if (isset($params['name'], $params['level2'], $params['level3'])) {
                return $this->_prefix . $params['level2'] . '/' . $params['level3'] . '/' . $params['name'] . Yii::app()->urlManager->urlSuffix;
            } elseif (isset($params['name'], $params['level2'])) {
                return $this->_prefix . $params['level2'] . '/' . $params['name'] . Yii::app()->urlManager->urlSuffix;
            } elseif (isset($params['name'])) {
                return $this->_prefix . $params['name'] . Yii::app()->urlManager->urlSuffix;
            }
        }

        return false;  // не применяем данное правило
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        $urlPattern = '/^cat(\/[a-z0-9-]+)(\/[a-z0-9-]+)?(\/[a-z0-9-]+)?$/i';

        if (preg_match($urlPattern, $pathInfo, $matches)) {
            $matches = array_reverse($matches);
            array_pop($matches);

            if ($matches[0]) {
                $_GET['name'] = str_replace('/', '', $matches[0]);
            }
            if ($matches[1]) {
                $_GET['level2'] = str_replace('/', '', $matches[1]);
            }
            if ($matches[2]) {
                $_GET['level3'] = str_replace('/', '', $matches[2]);
            }

            return 'questionCategory/alias';
        }

        return false;  // не применяем данное правило
    }
}
