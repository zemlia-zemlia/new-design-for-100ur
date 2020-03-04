<?php

    /* кастомный класс для очистки HTML кода от ненужных тегов
     * расширяет класс CHtmlPurifier
     */

class Purifier extends CHtmlPurifier
{
    public $options = [
        'HTML.Allowed' => 'div,p,a[href], a[title],b,br,i,img[src|alt|title],span[style],strong,ul,ol,li,sup,sub,h1,h2,h3,h4,h5,h6',
    ];
}
