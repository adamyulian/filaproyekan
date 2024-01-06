<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Radius implements ValidationRule
{

    protected $latitude;
    protected $longitude;
    protected $radius;

    public function __construct($latitude, $longitude, $radius)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->radius = $radius;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Validate whether the given coordinates are within the specified radius
        // You need to implement the Haversine formula here
        // See: https://en.wikipedia.org/wiki/Haversine_formula
        // You may also consider using a dedicated package for geo calculations

        // Example Haversine formula implementation (distance in meters)
        $earthRadius = 6371000; // Earth radius in meters
        $lat1 = deg2rad($this->latitude);
        $lat2 = deg2rad($value['latitude']);
        $lon1 = deg2rad($this->longitude);
        $lon2 = deg2rad($value['longitude']);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance <= $this->radius;
    }
}
