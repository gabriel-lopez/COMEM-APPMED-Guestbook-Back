<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Signature extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    public static $rules = [
        'title' => 'required|string|max:255',
        'message' => 'required|string|max:255',
        'user_id' => 'required|int|',
    ];

    protected $hidden = [
        'user_id',
        'updated_at',
        'deleted_at',
        'reported_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'reported_at'
    ];

    public static function getValidation(Array $inputs)
    {
        $validator = Validator::make($inputs, self::$rules);
        $validator->after(function ($validator) use ($inputs)
        {
            // contraintes supplémentaires
        });
        return $validator;
    }

    public static function createOne(array $values)
    {
        $new = new self();
        $new->title = $values['title'];
        $new->message = $values['message'];
        $new->user_id = $values['user_id'];
        $new->save();
        return $new;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}