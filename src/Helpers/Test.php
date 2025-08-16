<?php

use Illuminate\Database\Eloquent\Model;

if (! function_exists('randomOrCreate')) {
    /**
     * Get random model or create model using factory.
     *
     * @param  string|Model  $classNameOrModel
     *
     * @throws Exception
     */
    function randomOrCreate($classNameOrModel): Model
    {
        $className = null;

        if (is_string($classNameOrModel)) {
            $className = $classNameOrModel;
        } elseif ($classNameOrModel instanceof Model) {
            $className = get_class($classNameOrModel);
        }

        if ($className === null) {
            throw new Exception('Cannot find class for '.$classNameOrModel);
        }

        if ($className::count() > 0) {
            return $className::all()->random();
        }

        return $className::factory()->create();
    }
}
