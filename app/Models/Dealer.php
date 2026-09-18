<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_no', 'name', 'address', 'city', 'area', 'brand',
        'point_person_1', 'contact_1', 'point_person_2', 'contact_2',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function requests()
    {
        return $this->hasMany(PropertyRequest::class);
    }

    public function getBranchAttribute(): string
    {
        return $this->city ?: $this->name;
    }

    public function getDealerBrandAttribute(): string
    {
        return $this->brand ?: $this->name;
    }

    public function sameCityDealers()
    {
        $city = $this->city;
        if (! $city) {
            return collect([$this]);
        }

        return static::query()
            ->where('city', $city)
            ->orderBy('source_no')
            ->get();
    }
}
