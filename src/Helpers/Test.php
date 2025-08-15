<?php

use Illuminate\Database\Eloquent\Model;

if (! function_exists('randomOrCreate')) {
    /**
     * Get random model or create model using factory.
     *
     * @param string|Model $classNameOrModel
     * @return Model
     * @throws Exception
     */
    function randomOrCreate($classNameOrModel): Model
    {
        if (is_string($classNameOrModel)) {
            $className = $classNameOrModel;
        }

        if ($classNameOrModel instanceof Model) {
            $className = get_class($classNameOrModel);
        }

        if (! isset($className)) {
            throw new Exception('Cannot find class for ' . $classNameOrModel);
        }

        if ($className::count() > 0) {
            return $className::all()->random();
        }

        return $className::factory()->create();
    }
}