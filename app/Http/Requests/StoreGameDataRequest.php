<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameDataRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Add proper gate if needed
    }

    public function rules()
    {
        $min = config('game.min_level');
        $max = config('game.max_level');

        return [
            'castle_name' => "required|string|min:1",
            'castle_level'   => "required|integer|between:$min,$max",
            'range_level'    => "required|integer|between:$min,$max",
            'stables_level'  => "required|integer|between:$min,$max",
            'barracks_level' => "required|integer|between:$min,$max",
            'duke_badges'    => "required|integer|min:0|max:10000",
            'target_level'   => 'required|integer|between:46,50',
            'target_building' => 'required|string|in:' . implode(',', array_merge(array_keys(config('game.buildings')), ['overall'])),
        ];
    }

    public function messages()
    {
        return [
            'between' => 'Level must be between :min and :max.',
            'duke_badges.max' => 'Duke badges exceed maximum allowed value',
            'alliance.required' => 'Alliance name is required.',
            'alliance.min' => 'Alliance name must not be empty.',
        ];
    }
}
