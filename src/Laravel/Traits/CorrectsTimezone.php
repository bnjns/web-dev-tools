<?php

namespace bnjns\WebDevTools\Laravel\Traits;

use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

trait CorrectsTimezone
{
    /**
     * Override the default retrieval from the database to correct any date timezones.
     *
     * @param array $attributes
     * @param       $connection
     *
     * @return static
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $model = parent::newFromBuilder($attributes, $connection);

        // Check for attributes to correct
        if (isset($model->correct_tz) && is_array($model->correct_tz) && !empty($model->correct_tz)) {
            $model->correctDateTimeFromDatabase($model->correct_tz);
        }

        return $model;
    }

    /**
     * Correct for the different timezones of the user and server.
     *
     * @param Carbon  $date
     * @param Request $request
     *
     * @return Carbon
     */
    public function correctTimezone(Carbon $date, Request $request)
    {
        return $this->correctTzForStorage($date, $request);
    }

    /**
     * Correct the timezone and store the timezone offset.
     *
     * @param Carbon  $date
     * @param Request $request
     *
     * @return Carbon
     */
    public function correctTzForStorage(Carbon $date, Request $request)
    {
        $this->storeTzCorrection($request);

        return $date->addMinutes($request->header('TZ-OFFSET') ?: ($request->get('TZ-OFFSET') ?: 0));
    }

    /**
     * Correct a date for display back to the user.
     *
     * @param Carbon  $date
     * @param Request $request
     *
     * @return Carbon
     */
    public function correctTzForDisplay(Carbon $date, Request $request = null)
    {
        $tz_correction = !is_null($request) && $request->header('TZ-OFFSET') ?: $this->getTzCorrection();

        return $date->subMinutes($tz_correction);
    }

    /**
     * Correct the values of any date attributes when the model is retrieved from the database.
     *
     * @param array $attributeNames
     *
     * @return void
     */
    public function correctDateTimeFromDatabase(array $attributeNames)
    {
        if ($this->exists) {
            // Update the attributes
            foreach ($attributeNames as $name) {
                $this->attributes[$name] = $this->correctTzForDisplay($this->asDateTime($this->attributes[$name]))
                                                ->format($this->getDateFormat());
            }

            // Sync the originals so it doesn't look like they've changed.
            foreach ($attributeNames as $name) {
                $this->syncOriginalAttribute($name);
            }
        }
    }

    /**
     * Store the timezone offset value in a cookie for use by PHP later.
     *
     * @param Request $request
     *
     * @return void
     */
    private function storeTzCorrection(Request $request)
    {
        session([
            'tz_correction' => $request->header('TZ-OFFSET'),
        ]);
    }

    /**
     * Get / guess the user's timezone offset.
     *
     * @return float
     */
    private function getTzCorrection()
    {
        // Check if the offset has been stored
        if (session()->has('tz_correction')) {
            return session()->get('tz_correction');
        }

        // Default to assuming user is in Europe/London
        $tz     = new DateTimeZone('Europe/London');
        $date   = new DateTime('now', $tz);
        $offset = round($date->getOffset() / 60, 2);

        return $offset * -1;
    }
}
