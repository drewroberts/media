<?php

function randomOrCreate($className)
{
    if ($className::count() > 0) {
        return $className::all()->random();
    }

    return $className::factory()->create();
}
