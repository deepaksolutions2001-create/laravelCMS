<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $table = "form_submissions";
    protected $casts = ['data' => 'array'];
    protected $fillable = [
        'form_type',
        'name',
        'data',
        'source',

    ]; // required for create() mass assignment [web:120]
}
