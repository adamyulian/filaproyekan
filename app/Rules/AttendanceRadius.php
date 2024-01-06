<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use SebastianBergmann\Type\VoidType;

class AttendanceRadius implements ValidationRule
{
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    /**
     * The latitude of the allowed location.
     *
     * @var float
     */
    protected $allowedLatitude;

    /**
     * The longitude of the allowed location.
     *
     * @var float
     */
    protected $allowedLongitude;

    /**
     * The radius in meters.
     *
     * @var int
     */
    protected $radius;

    /**
     * Create a new rule instance.
     *
     * @param float $allowedLatitude
     * @param float $allowedLongitude
     * @param int   $radius
     */
    public function __construct($allowedLatitude, $allowedLongitude, $radius)
    {
        $this->allowedLatitude = $allowedLatitude;
        $this->allowedLongitude = $allowedLongitude;
        $this->radius = $radius;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Example Haversine formula implementation
        $earthRadius = 6371000; // Earth radius in meters
        $lat1 = deg2rad($this->allowedLatitude); // Reference latitude
        $lat2 = deg2rad($value['lat']);    // User's location latitude
        $lon1 = deg2rad($this->allowedLongitude); // Reference longitude
        $lon2 = deg2rad($value['lng']);    // User's location longitude

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;
        // dd([$distance, $this->radius]);
        if ($distance >= $this->radius) {
            $fail("Your location is not within the allowed radius.");
        }

       

    }
}
