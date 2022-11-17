<?php

use App\Utils\enums\Timezone;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;

const DEFAULT_TIMESTAMP_FORMAT = array(
    'y' => ' y',
    'm' => ' m',
    'w' => ' w',
    'd' => ' d',
    'h' => ' h',
    'i' => ' m',
    's' => ' s'
);

function clearCacheAll()
{
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
}

function isValidEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function getDateString($date, $format = 'd-m-Y h:i a', $timezone = Timezone::PHNOM_PENH)
{
    if (empty($date)) {
        return null;
    }
    if (is_string($date)) {
        return Carbon::parse($date)->setTimezone(Timezone::PHNOM_PENH)->format($format);
    }
    return $date->setTimezone($timezone)->format($format);
}

function getBool($value)
{
    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

function getInt($value)
{
    return filter_var($value, FILTER_VALIDATE_INT);
}

function sortNested($data, $sort, $descending = false, $nestedKey, $options = SORT_REGULAR)
{

    if (is_array($data)) {
        $data = collect($data);
    }

    $data = sortData($data, $sort, $descending, $options);

    foreach ($data as $key => $item) {
        if (is_array($item[$nestedKey]) || $item[$nestedKey] instanceof Collection) {
            $item[$nestedKey] = sortNested($item[$nestedKey], $sort, $descending, $nestedKey, $options);
            $data[$key] = $item;
        }
    }

    return $data;
}

function sortData($data, $sort, $descending = false, $options = SORT_REGULAR)
{
    if (is_array($data)) {
        $data = collect($data);
    }

    return $data->sortBy(function ($page) use ($sort) {
        return $page[$sort];
    }, $options, $descending)->values();
}


function customPluck($value, $key)
{
    if (is_array($value)) {
        return Arr::pluck($value, $key);
    }
    if ($value instanceof Collection) {
        return $value->pluck($key);
    }

    throw new Exception("Incorrect value provided, Array or Collection required!");
}



### ========== Related to Date ==========

function getTimeZone()
{
    $timezone = request()->header('timezone');
    if ($timezone && strlen($timezone) <= 3) {
        $timezone = config('app.my_tz');
    } else {
        $timezone = config('app.my_tz');
    }
    return $timezone;
}

function getDateFromString($value, $tz = NULL)
{
    if (empty($value)) {
        return null;
    }
    return Carbon::parse($value, $tz);
}

function getLifeTimestamp($date, $timezone = NULL, $format = NULL)
{
    if (!$date) {
        return $date;
    }
    if ($timezone && strlen($timezone) <= 3) {
        $timezone = config('app.my_tz');
    } else {
        $timezone = config('app.my_tz');
    }
    $date = (is_string($date) ? getDateFromString($date, $timezone) : $date)->setTimezone(new DateTimeZone($timezone));
    $format = $format ? array_merge(DEFAULT_TIMESTAMP_FORMAT, $format) : DEFAULT_TIMESTAMP_FORMAT;
    $diff = Carbon::now()->diff($date);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
        'y' => 'year',
        'fw' => 'full week',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second'
    );

    ## remove if format not included fullweek needed.
    if (!$format['fw']) {
        unset($string['fw']);
    } else {
        $diff->fw = floor($diff->days / 7);
    }
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . $format[$k];
        } else {
            unset($string[$k]);
        }
    }
    $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) : 'just now';
}

function applyLifeTimeStamp($records, $key, $tz = NULL, $format = NULL)
{
    if (is_array($records) || $records instanceof Collection) {
        foreach ($records as &$record) {
            $record['lifeTimeStamp'] = getLifeTimestamp($record[$key], $tz, $format);
        }
    } else {
        $records->lifeTimeStamp = getLifeTimestamp($records->$key, $tz, $format);
    }

    return $records;
}

function getLifeTimestampLongFormat($date, $timezone = NULL)
{
    if ($timezone && strlen($timezone) <= 3) {
        $timezone = config('app.my_tz');
    } else {
        $timezone = config('app.my_tz');
    }
    return (is_string($date) ? getDateFromString($date, $timezone) : $date)->setTimezone(new DateTimeZone($timezone))->diffForHumans();
}

function applyLifeTimestampLongFormat($records, $key, $tz = NULL)
{
    if (is_array($records) || $records instanceof Collection) {
        foreach ($records as &$record) {
            $record['lifeTimeStamp'] = getLifeTimestampLongFormat($record[$key], $tz);
        }
    } else {
        $records->lifeTimeStamp = getLifeTimestampLongFormat($records[$key], $tz);
    }

    return $records;
}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'MI' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'M' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at https://www.geodatasource.com                         :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: https://www.geodatasource.com                       :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2022                  :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
function distance($lat1, $lon1, $lat2, $lon2, $unit)
{
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
    } else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        switch ($unit) {
            case 'M':
                $result = ($miles * 1609);
                break;
            case 'K':
                $result = ($miles * 1.609344);
                break;
            case 'N':
                $result = ($miles * 0.8684);
                break;
            default:
                $result = $miles;
        }
        return $result;
    }
}
