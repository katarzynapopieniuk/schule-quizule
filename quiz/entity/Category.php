<?php

class Category {
    const POLSKI= "polski";
    const MATEMATYKA = "matma";
    const PRZYRODA = "przyroda";
    const ANGIELSKI = "angielski";

    static function getCategories() {
        return array(self::POLSKI, self::MATEMATYKA, self::PRZYRODA, self::ANGIELSKI);
    }
}