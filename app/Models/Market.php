<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Market extends Model
{
    protected $fillable = [
    'name',
    'description',
    'address',
    'city',
    'state',
    'postal_code',
    'latitude',
    'longitude',
    'operating_days',
    'opening_time',
    'closing_time',
    ];

    public function farmers(): BelongsToMany
    {
        return $this->belongsToMany(FarmerProfile::class, 'market_farmer');
    }

    protected $casts = [
    'operating_days' => 'array',
    'opening_time' => 'datetime:H:i',
    'closing_time' => 'datetime:H:i',
    ];

    public static function weekdayOptions(): array
    {
        return [
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
            'sun' => 'Sunday',
        ];
    }

    public function formattedOperatingDays(): ?string
    {
        if (!$this->operating_days || !is_array($this->operating_days)) {
            return null;
        }

        $labels = [];
        foreach ($this->operating_days as $code) {
            $options = self::weekdayOptions();
            if (isset($options[$code])) {
                $labels[] = $options[$code];
            }
        }

        return $labels ? implode(', ', $labels) : null;
    }

    public function formattedOpeningTime(): ?string
    {
        return $this->opening_time ? $this->opening_time->format('H:i') : null;
    }

    public function formattedClosingTime(): ?string
    {
        return $this->closing_time ? $this->closing_time->format('H:i') : null;
    }
}
