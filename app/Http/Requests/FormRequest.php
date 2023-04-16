<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as IlluminateFormRequest;

class FormRequest extends IlluminateFormRequest
{
    protected $stopOnFirstFailure = true;
}
