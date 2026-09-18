<?php

namespace App\Support;

class DealerData
{
    public const HEADERS = [
        'source_no' => 'Dealer No.',
        'name' => 'Dealer Name',
        'address' => 'Address',
        'city' => 'Branch (City)',
        'area' => 'Area',
        'brand' => 'Brand',
        'point_person_1' => 'Point Person 1',
        'contact_1' => 'Contact 1',
        'point_person_2' => 'Point Person 2',
        'contact_2' => 'Contact 2',
    ];

    public static function rules(): array
    {
        return [
            'source_no' => ['required', 'integer', 'min:1', 'max:4294967295'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:10000'],
            'city' => ['nullable', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'point_person_1' => ['nullable', 'string', 'max:255'],
            'contact_1' => ['nullable', 'string', 'max:50'],
            'point_person_2' => ['nullable', 'string', 'max:255'],
            'contact_2' => ['nullable', 'string', 'max:50'],
        ];
    }
}
