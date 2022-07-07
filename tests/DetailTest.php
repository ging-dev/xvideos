<?php

use PHPUnit\Framework\TestCase;
use GingDev\Xvideos\Detail;

test('get video', function () {
    $title = (new Detail())->get('70714107')['title'];
    
   /** @var TestCase $this */
    $this->assertSame('friendly sex', $title);
});
