<?php

/**
 * Логика работы с рангами юристов
 * Class YuristRang.
 */
class YuristRang
{
    /** @var array */
    private $rangs;

    /**
     * @return array
     */
    public function getRangs(): array
    {
        return $this->rangs;
    }

    /**
     * YuristRangs constructor.
     *
     * @param array $rangsSettings
     *
     * @throws Exception
     */
    public function __construct($rangsSettings)
    {
        if (!is_array($rangsSettings)) {
            throw new Exception('Invalid rangs settings');
        }
        $this->rangs = $rangsSettings;
    }

    /**
     * Возвращает данные ранга по его id в виде ассоц. массива.
     *
     * @param int $rangId
     *
     * @return array
     *
     * @throws Exception
     */
    public function getRangInfo($rangId)
    {
        if (isset($this->rangs[$rangId])) {
            return $this->rangs[$rangId];
        } else {
            throw new Exception('Invalid rang id');
        }
    }

    /**
     * Определяет, какого ранга достоин пользователь.
     *
     * @param User $user
     *
     * @return int
     */
    public function detectRang(User $user)
    {
        $answersCount = $user->answersCount;
        $rating = $user->getRating();
        $testimonialsCount = $user->commentsCount;
        $karma = $user->karma;
        $detectedRang = 0;

        foreach ($this->rangs as $rangId => $rang) {
            $limits = $rang['limits'];
            if (
                $answersCount >= $limits['answers'] &&
                $rating >= $limits['rating'] &&
                $testimonialsCount >= $limits['testimonials'] &&
                $karma >= $limits['karma']
            ) {
                $detectedRang = $rangId;
            }
        }

        return $detectedRang;
    }
}
