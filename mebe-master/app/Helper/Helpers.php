<?php

namespace App\Helper;

class Helpers
{
    public function getAttributeSamples()
    {
        $arrayAttributes = [
            'color' => ['red', 'black', 'blue', 'purple', 'yellow', 'green'],
            'material' => ['silver', 'cotton', 'leather', 'nylon', 'polyester', 'silk', 'wool'],
            'size' => ['S', 'M', 'L', 'XL', 'XS', 'XXL', '36', '37', '38', '39', '40', '41', '42', '43', '44'],
            'brand' => ['frisolac', 'nestle', 'alphagrow', 'enfamil'],
            'collection' => ['zero-six-months', 'six-twelve-months', 'twelve-thirdty-six-months']
        ];
        return $arrayAttributes;
    }


}