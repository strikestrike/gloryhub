<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Level Range Settings
    |--------------------------------------------------------------------------
    */
    'min_level' => 46,
    'max_level' => 50,

    'building_levels' => [46, 47, 48, 49, 50],
    'buildings' => [
        'castle'   => 'Castle',
        'range'    => 'Archery Range',
        'stables'  => 'Stables',
        'barracks' => 'Barracks',
    ],

    /*
    |--------------------------------------------------------------------------
    | Award Types
    |--------------------------------------------------------------------------
    */
    'award_types' => [
        'crown',
        'conqueror',
        'defender',
        'support',
    ],

    /*
    |--------------------------------------------------------------------------
    | Kingdom Levels
    |--------------------------------------------------------------------------
    */
    'kingdom_levels' => [
        'min' => 1,
        'max' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Kingdom Award Distribution Per Level
    |--------------------------------------------------------------------------
    |
    | Defines the number of awards distributed per level.
    |
    */
    'kingdom_awards' => [
        1 => [
            'crown' => 1,
            'conqueror' => 1,
            'defender' => 12,
            'support' => 50,
        ],
        2 => [
            'crown' => 1,
            'conqueror' => 2,
            'defender' => 24,
            'support' => 100,
        ],
        3 => [
            'crown' => 1,
            'conqueror' => 3,
            'defender' => 36,
            'support' => 150,
        ],
    ],

];
