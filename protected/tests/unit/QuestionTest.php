<?php
/**
 * Набор unit тестов для тестирования функционала вопросов
 */

class QuestionTest extends CDbTestCase
{
    // определение фикстур
    public $fixtures=array(
        'posts'=>'Post',
    );
    
    /**
     * Тестирование создания вопроса и сохранения его в базе
     */
    public function testCreate()
    {
        $question = new Question;
        
        $question->setAttributes(array(
                'questionText'  =>  'Привет мир',
            ), false);
        $this->assertEquals('Привет мир', $question->questionText);
        $this->assertTrue($question->save(false));
        
        $question = Question::model()->findByPk($question->id);
        $this->assertNotEquals('', $question->title);
        $this->assertEquals('Привет мир', $question->title);
    }
}