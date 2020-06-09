<?php
/**
 * Класс для работы с набором турбо-страниц в формате XML
 * Class TurboPack.
 */
class TurboPack
{
    /** @var TurboItem[] */
    private $turboItems;

    /**
     * @return TurboItem[]
     */
    public function getTurboItems()
    {
        return $this->turboItems;
    }

    /**
     * @param TurboItem[] $turboItems
     */
    public function setTurboItems($turboItems)
    {
        $this->turboItems = $turboItems;
    }

    /**
     * Добавление страницы в набор
     */
    public function addItem(TurboItem $item)
    {
        $this->turboItems[] = $item;
    }

    /**
     * Шапка RSS.
     *
     * @return string
     */
    private function getHeader()
    {
        return '<rss xmlns:yandex="http://news.yandex.ru"
    xmlns:media="http://search.yahoo.com/mrss/"
    xmlns:turbo="http://turbo.yandex.ru"
    version="2.0"><channel> 
            <title>100 Юристов</title>
            <link>' . Yii::app()->urlManager->baseUrl . '</link>
            <language>ru</language>
            <description>Юридическая консультация и услуги юристов онлайн</description>';
    }

    /**
     * Футер RSS.
     *
     * @return string
     */
    private function getFooter()
    {
        return '</channel></rss>';
    }

    /**
     * Возвращает массив XML документов.
     *
     * @param int $taskSize Максимальное количество элементов в задаче
     *
     * @return array
     */
    public function getTasks($taskSize = 5)
    {
        $tasks = [];
        $counter = 0;
        $currentTaskXML = '';

        foreach ($this->turboItems as $itemNumber => $item) {
            if (0 == $counter % $taskSize) {
                $currentTaskXML = $this->getHeader();
            }
            $currentTaskXML .= $item->getXml();

            if (0 == ($counter + 1) % $taskSize) {
                $currentTaskXML .= $this->getFooter();
                $tasks[] = $currentTaskXML;
            }
            ++$counter;
        }

        if (0 != $counter % $taskSize) {
            $currentTaskXML .= $this->getFooter();
            $tasks[] = $currentTaskXML;
        }

        return $tasks;
    }
}
