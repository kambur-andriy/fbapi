<?php

namespace App\Models\Validation;


class ValidationMessages
{
    /**
     * @var array List of validation messages
     */
    private static $defaultMessages = [
        'required' => 'The %s field is required',
        'string' => 'The %s field must be a string',
        'email' => 'The %s field must contain correct email',
        'max' => 'The %s field is to long',
        'min' => 'The %s field is to short',
        'unique' => 'The %s field must be unique',
        'numeric' => 'The %s field may contain only  digits',
        'integer' => 'The %s field may contain only digits',
        'alpha' => 'The %s field may contain only letters',
        'confirmed' => 'The %s confirmation does not match',
        'required_if' => 'The %s field is required'
    ];

    /**
     * Get list of available currencies
     *
     * @return array
     */
    public static function getList($fields = [], $customMessages = [])
    {
        $validationMessages = [];

        foreach ($fields as $field => $alias) {

            foreach (self::$defaultMessages as $validator => $defaultMessage) {
                $id = $field . '.' . $validator;

                if (isset($customMessages[$id])) {
                    $validationMessages[$id] = $customMessages[$id];

                    continue;
                }

                $validationMessages[$id] = sprintf($defaultMessage, $alias);

            }

        }

        return $validationMessages;
    }

}
