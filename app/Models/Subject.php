<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'section_id',
        'code',
        'title',
        'units',
        'schedule',
        'days',
        'time_from',
        'time_to',
        'room'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function timeFromForInput(): string
    {
        return $this->normalizeTimeForInput($this->time_from);
    }

    public function timeToForInput(): string
    {
        return $this->normalizeTimeForInput($this->time_to);
    }

    public function timeFromForDisplay(): string
    {
        return $this->formatTimeForDisplay($this->time_from);
    }

    public function timeToForDisplay(): string
    {
        return $this->formatTimeForDisplay($this->time_to);
    }

    public function scheduleForDisplay(): string
    {
        if (! $this->days && ! $this->time_from && ! $this->time_to && ! $this->room) {
            return '—';
        }

        return trim(sprintf(
            '%s %s-%s %s',
            $this->days ?: '—',
            $this->timeFromForDisplay(),
            $this->timeToForDisplay(),
            $this->room ?: '—'
        ));
    }

    private function normalizeTimeForInput(?string $time): string
    {
        if (! $time) {
            return '';
        }

        $time = trim($time);

        if (preg_match('/^(\d{1,2}):(\d{2})([ap])$/i', $time, $matches)) {
            $hour = (int) $matches[1];
            $minute = $matches[2];
            $period = strtolower($matches[3]);

            if ($period === 'p' && $hour !== 12) {
                $hour += 12;
            }

            if ($period === 'a' && $hour === 12) {
                $hour = 0;
            }

            return sprintf('%02d:%s', $hour, $minute);
        }

        if (preg_match('/^(\d{1,2}):(\d{2})(?::\d{2})?$/', $time, $matches)) {
            return sprintf('%02d:%s', (int) $matches[1], $matches[2]);
        }

        return '';
    }

    private function formatTimeForDisplay(?string $time): string
    {
        $normalizedTime = $this->normalizeTimeForInput($time);

        if ($normalizedTime === '') {
            return '—';
        }

        [$hour, $minute] = array_map('intval', explode(':', $normalizedTime));
        $period = $hour >= 12 ? 'PM' : 'AM';
        $displayHour = $hour % 12;

        if ($displayHour === 0) {
            $displayHour = 12;
        }

        return sprintf('%d:%02d %s', $displayHour, $minute, $period);
    }
}
