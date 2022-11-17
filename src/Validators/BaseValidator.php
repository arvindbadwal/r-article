<?php

namespace Cactus\Article\Validators;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseValidator
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @throws ValidationException
     */
    public function validate($data, $ruleset): array
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        $rules = $this->rules[$ruleset];

        $messages = $this->messages[$ruleset];

        return Validator::make($data, $rules, $messages)->validate();
    }
}