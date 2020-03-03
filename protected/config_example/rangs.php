<?php

return [
    0 => [
        'name' => 'Новичок',
        'commission' => 30,
        'limits' => [   // условия достижения ранга (все условия должны быть соблюдены)
            'answers' => 0, // ответов
            'karma' => 0, // карма
            'testimonials' => 0, // отзывов
            'rating' => 0, // средний балл по отзывам
        ],
    ],
    1 => [
        'name' => 'Местный',
        'commission' => 30,
        'limits' => [   // условия достижения ранга (все условия должны быть соблюдены)
            'answers' => 100, // ответов
            'karma' => 10, // карма
            'testimonials' => 5, // отзывов
            'rating' => 4, // средний балл по отзывам
        ],
    ],
    2 => [
        'name' => 'Опытный',
        'commission' => 26,
        'limits' => [   // условия достижения ранга (все условия должны быть соблюдены)
            'answers' => 1000, // ответов
            'karma' => 100, // карма
            'testimonials' => 50, // отзывов
            'rating' => 4.5, // средний балл по отзывам
        ],
    ],
    3 => [
        'name' => 'Эксперт',
        'commission' => 20,
        'limits' => [   // условия достижения ранга (все условия должны быть соблюдены)
            'answers' => 5000, // ответов
            'karma' => 500, // карма
            'testimonials' => 250, // отзывов
            'rating' => 4.8, // средний балл по отзывам
        ],
    ],
    4 => [
        'name' => 'Партнер',
        'commission' => 15,
        'limits' => [   // условия достижения ранга (все условия должны быть соблюдены)
            'answers' => 15000, // ответов
            'karma' => 1500, // карма
            'testimonials' => 1000, // отзывов
            'rating' => 4.8, // средний балл по отзывам
        ],
    ],
];
