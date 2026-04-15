<?php

use Illuminate\Support\Facades\Route;

Route::get('/veganize', function () {

    $dish = request()->input('dish');

    $replacements = [
        "Döner" => [
            "vegan_name" => "Seitan Döner",
            "replacements" => [
                ["from" => "Lammfleisch", "to" => "Seitan"],
                ["from" => "Joghurt", "to" => "Soja-Joghurt"]
            ]
        ],
        "Burger" => [
            "vegan_name" => "Veggie Burger",
            "replacements" => [
                ["from" => "Rindfleisch", "to" => "Beyond Meat"],
                ["from" => "Käse", "to" => "Veganer Käse"]
            ]
        ]
    ];

    if (!isset($replacements[$dish])) {
        return response()->json([
            "error" => "Gericht nicht gefunden"
        ], 404);
    }

    return response()->json([
        "original_name" => $dish,
        "vegan_name" => $replacements[$dish]["vegan_name"],
        "replacements" => $replacements[$dish]["replacements"]
    ]);
});
