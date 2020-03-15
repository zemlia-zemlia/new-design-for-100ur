<?php

namespace Tests\Unit\Helpers;

use Codeception\Test\Unit;
use App\helpers\UTMHelper;

class UTMHelperTest extends Unit
{
    /**
     * @dataProvider tagsProvider
     *
     * @param $sourceText
     * @param $tags
     * @param $expectedResult
     */
    public function testInsertTags($sourceText, $tags, $expectedResult)
    {
        $taggedText = UTMHelper::insertTags($sourceText, $tags);
        $this->assertEquals($expectedResult, $taggedText);
    }

    public function tagsProvider(): array
    {
        return [
            [
                'sourceText' => "<a href='https://www.100yuristov.com'>Моя ссылка</a>",
                'tags' => [
                    'utm_medium' => 'medium',
                    'utm_source' => 'source',
                    'utm_campaign' => 'campaign',
                    'utm_term' => 'term',
                    'utm_content' => 'content',
                ],
                'expectedResult' => "<a href='https://www.100yuristov.com?utm_medium=medium&utm_source=source&utm_campaign=campaign&utm_term=term&utm_content=content'>Моя ссылка</a>",
            ],
            [
                'sourceText' => 'Текст без ссылок',
                'tags' => [
                    'utm_medium' => 'medium',
                    'utm_source' => 'source',
                    'utm_campaign' => 'campaign',
                    'utm_term' => 'term',
                    'utm_content' => 'content',
                ],
                'expectedResult' => 'Текст без ссылок',
            ],
            [
                'sourceText' => "<a href='https://www.100yuristov.com'>Моя ссылка</a>",
                'tags' => [],
                'expectedResult' => "<a href='https://www.100yuristov.com'>Моя ссылка</a>",
            ],
            [
                'sourceText' => "<a href='https://www.100yuristov.com?param=1'>Моя ссылка</a>",
                'tags' => [
                    'utm_medium' => 'medium',
                    'utm_source' => 'source',
                ],
                'expectedResult' => "<a href='https://www.100yuristov.com?param=1&utm_medium=medium&utm_source=source'>Моя ссылка</a>",
            ],
        ];
    }
}
