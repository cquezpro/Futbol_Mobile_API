<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;

class IfExistsHashId implements Rule
{
    /**
     * @var null
     */
    private $table;
    /**
     * @var null
     */
    private $field;

    /**
     * Create a new rule instance.
     *
     * @param null $table
     * @param null $field
     */
    public function __construct($table = null, $field = 'id')
    {
        //
        $this->table = $table;
        $this->field = $field;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->table == null)
            abort('500', 'Validation rule exists hash_id requires at least 1 parameters.');

        $id = Hashids::decode($value);

        if(count($id) <= 0)
            return false;

        $model = str_singular($this->table);
        $model = ucfirst($model);

        $model = app("\App\\$model");

        $val = $model->find($id);

        if(!$val)
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.hash_id');
    }
}
