<?php


namespace App\dto;

/**
 * Запись
 * Class QuestionRssItemDto
 * @package App\dto
 */
class QuestionRssItemDto
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $title;
    /** @var string */
    protected $createDate;
    /** @var string */
    protected $publishDate;
    /** @var string */
    protected $questionText;
    /** @var int */
    protected $answersCount;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return QuestionRssItemDto
     */
    public function setId(int $id): QuestionRssItemDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return QuestionRssItemDto
     */
    public function setTitle(string $title): QuestionRssItemDto
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreateDate(): string
    {
        return $this->createDate;
    }

    /**
     * @param string $createDate
     * @return QuestionRssItemDto
     */
    public function setCreateDate(string $createDate): QuestionRssItemDto
    {
        $this->createDate = $createDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublishDate(): string
    {
        return $this->publishDate;
    }

    /**
     * @param string $publishDate
     * @return QuestionRssItemDto
     */
    public function setPublishDate(string $publishDate): QuestionRssItemDto
    {
        $this->publishDate = $publishDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuestionText(): string
    {
        return $this->questionText;
    }

    /**
     * @param string $questionText
     * @return QuestionRssItemDto
     */
    public function setQuestionText(string $questionText): QuestionRssItemDto
    {
        $this->questionText = $questionText;
        return $this;
    }

    /**
     * @return int
     */
    public function getAnswersCount(): int
    {
        return $this->answersCount;
    }

    /**
     * @param int $answersCount
     * @return QuestionRssItemDto
     */
    public function setAnswersCount(int $answersCount): QuestionRssItemDto
    {
        $this->answersCount = $answersCount;
        return $this;
    }
}
