[![Build Status](https://travis-ci.org/Fredde0223/weather_anax.svg?branch=main)](https://travis-ci.org/Fredde0223/weather_anax)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Fredde0223/weather_anax/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/Fredde0223/weather_anax/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/Fredde0223/weather_anax/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/Fredde0223/weather_anax/?branch=main)

Weather module for Anax
=======================

Created for the course Ramverk1 at BTH.

Installation
-----------------------

Run command:

`composer require fredde/weather`

This will install the module from packagist.

Setup
-----------------------

Run commands:

`rsync -av vendor/fredde/weather/config ./`

`rsync -av vendor/fredde/weather/src ./`

`rsync -av vendor/fredde/weather/test ./`

`rsync -av vendor/fredde/weather/view ./`

These commands will place the files in the correct folders.

The next thing you need to do is to generate API-keys.
You will need keys from [ipstack](https://ipstack.com/) and [openweathermap](https://openweathermap.org/).
Place these keys in:

`config/apikeys.php`

```
return array(
    'ipStack' => 'ADD KEY HERE',
    'openWeather' => 'ADD KEY HERE'
);
```

You might want to ignore this file in a potential GitHub repository by adding the filename in your gitignore-file.

Here is an example of how to add a link to the weather service in your header navbar:

```
[
    "text" => "DI (väderservice)",
    "url" => "di",
    "title" => "dependency injection (weather)",
],
```
