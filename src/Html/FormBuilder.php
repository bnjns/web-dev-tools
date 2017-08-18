<?php

namespace bnjns\WebDevTools\Html;

use App\User;
use Carbon\Carbon;

class FormBuilder extends \Collective\Html\FormBuilder
{
    /**
     * Determine if the value is selected.
     *
     * @param  string $value
     * @param  string $selected
     *
     * @return null|string
     */
    protected function getSelectedValue($value, $selected)
    {
        if (is_array($selected)) {
            return in_array($value, $selected, false) ? 'selected' : null;
        } else if ($selected instanceof Collection) {
            return $selected->contains($value) ? 'selected' : null;
        }

        return ((string)$value == (string)$selected) ? 'selected' : null;
    }

    /**
     * Create a dropdown group for hour and minute.
     *
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectTime($name, $selected = null, array $options = [])
    {
        $hours = $minutes = [];
        foreach (range(0, 23) as $hour) {
            $hours[$hour] = sprintf('%02d', $hour);
        }
        foreach (range(0, 59) as $minute) {
            $minutes[$minute] = sprintf('%02d', $minute);
        }

        return sprintf('%s : %s',
            $this->select($name . '_hour', $hours, $this->getValueAttribute($name . '_hour', Carbon::now()->hour), $options),
            $this->select($name . '_minute', $minutes, $this->getValueAttribute($name . '_minute', Carbon::now()->minute), $options));
    }

    /**
     * Create a dropdown group for day, month and year.
     *
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectDate($name, $selected = null, array $options = [])
    {
        $days = [];
        foreach (range(1, 31) as $day) {
            $days[$day] = sprintf('%02d', $day);
        }

        return sprintf('%s / %s / %s',
            $this->select($name . '_day', $days, $this->getValueAttribute($name . '_day', Carbon::now()->day), $options),
            $this->selectMonth($name . '_month', $this->getValueAttribute($name . '_month', Carbon::now()->month), $options),
            $this->selectYear($name . '_year', date('Y') - 1, date('Y') + 1, $this->getValueAttribute($name . '_year', Carbon::now()->year), $options));
    }

    /**
     * Create a dropdown for the active users.
     *
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function userList($name, $selected = null, array $options = [])
    {
        // Get the list of users
        $users = User::active()->nameOrder()->getSelect();

        // Check if a blank entry is allowed
        if (isset($options['include_blank']) && $options['include_blank']) {
            // Define the blank text
            if (isset($options['blank_text'])) {
                $blank_text = $options['blank_text'];
                unset($options['blank_text']);
            } else {
                $blank_text = '-- Select --';
            }

            $users = [null => $blank_text] + $users;
            unset($options['include_blank']);
        }

        // Enable the use of the select2 plugin
        if (isset($options['select2']) && $options['select2']) {
            $options['select2'] = 'Select user';
        }

        return $this->select($name, $users, $selected, $options);
    }

    /**
     * Create a dropdown for the active members.
     *
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function memberList($name, $selected = null, array $options = [])
    {
        // Get a list of members
        $members = User::active()->member()->nameOrder()->getSelect();

        // Check if a blank entry is allowed
        if (isset($options['include_blank']) && $options['include_blank']) {
            // Define the blank text
            if (isset($options['blank_text'])) {
                $blank_text = $options['blank_text'];
                unset($options['blank_text']);
            } else {
                $blank_text = '-- Select --';
            }

            $members = [null => $blank_text] + $members;
            unset($options['include_blank']);
        }

        // Enable the use of the select2 plugin
        if (isset($options['select2']) && $options['select2']) {
            $options['select2'] = 'Select member';
        }

        return $this->select($name, $members, $selected, $options);
    }

    /**
     * Create a dropdown group for hour, minute, day, month and year.
     *
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectDateTime($name, $selected = null, array $options = [])
    {
        return sprintf('%s&nbsp;&nbsp;%s',
            $this->selectTime($name, $selected, $options),
            $this->selectDate($name, $selected, $options));
    }

    /**
     * Override the default 'date' type to enable the date/time picker.
     *
     * @param string $name
     * @param null   $value
     * @param array  $options
     *
     * @return string
     */
    public function date($name, $value = null, $options = [])
    {
        $options = array_merge([
            'data-input-type'  => 'datetimepicker',
            'data-date-format' => 'YYYY-MM-DD',
            'placeholder'      => @$options['data-date-format'] ?: 'YYYY-MM-DD',
        ], $options);

        return $this->text($name, $value, $options);
    }

    /**
     * Override the default 'time' type to enable the date/time picker.
     *
     * @param string $name
     * @param null   $value
     * @param array  $options
     *
     * @return string
     */
    public function time($name, $value = null, $options = [])
    {
        $options = array_merge([
            'data-input-type'  => 'datetimepicker',
            'data-date-format' => 'HH:mm:ss',
            'placeholder'      => @$options['data-date-format'] ?: 'HH:mm:ss',
        ], $options);

        return $this->text($name, $value, $options);
    }

    /**
     * Override the default 'datetime' type to enable the date/time picker.
     *
     * @param string $name
     * @param null   $value
     * @param array  $options
     *
     * @return string
     */
    public function datetime($name, $value = null, $options = [])
    {
        $options = array_merge([
            'data-input-type'  => 'datetimepicker',
            'data-date-format' => 'YYYY-MM-DD HH:mm:ss',
            'placeholder'      => @$options['data-date-format'] ?: 'YYYY-MM-DD HH:mm:ss',
        ], $options);

        return $this->text($name, $value, $options);
    }

    /**
     * Easily create a group of radio buttons.
     *
     * @param       $name
     * @param array $list
     * @param null  $selected
     * @param array $options
     *
     * @return array
     */
    public function radioGroup($name, $list = [], $selected = null, $options = [])
    {
        $selected      = $this->getValueAttribute($name, $selected);
        $options['id'] = $this->getIdAttribute($name, $options);
        if (!isset($options['name'])) {
            $options['name'] = $name;
        }

        $class = 'radio';
        if (isset($options['class'])) {
            $class .= ' ' . $options['class'];
            unset($options['class']);
        }

        $html = [];
        foreach ($list as $value => $text) {
            $html[] = "<div class=\"{$class}\"><label>" . $this->radio($name, $value, $selected == $value, $options) . $text . '</label></div>';
        }

        $list = implode('', $html);

        return $list;
    }
}
