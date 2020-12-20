<?php

namespace Fredde\DIController;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * API validator for IP-addresses
 */
class DIApiController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * get method for diapi page
     *
     * @return object render diapi-page
     */
    public function indexActionGet() : object
    {
        $title = "Väderservice (API)";

        $page = $this->di->get("page");

        $page->add("di/apiWeatherDI");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * get method for diapi page
     *
     * @return object json data
     */
    public function weatherActionGet() : array
    {
        $weatherCheck = $this->di->get("weather");
        $request = $this->di->get("request");
        $ipString = $request->getGet("ip", "") ?? null;
        $latString = $request->getGet("lat", "") ?? null;
        $lonString = $request->getGet("lon", "") ?? null;

        if ($ipString) {
            $location = $weatherCheck->getLocation($ipString);
            $latString = $location[0];
            $lonString = $location[1];

            $errCheck = "ipcheck";
        } elseif ($latString && $lonString) {
            $errCheck = "coordcheck";
        } else {
            $errCheck = "urlcheck";
        }

        if ($weatherCheck->checkCoords($latString, $lonString)) {
            $histUrls = $weatherCheck->getHistoryUrls($latString, $lonString);
            $foreUrls = $weatherCheck->getForecastUrls($latString, $lonString);

            $histData = $weatherCheck->getWeather($histUrls);
            $foreData = $weatherCheck->getWeather($foreUrls);

            $obj = [
                'forecast' => $foreData,
                'history' => $histData
            ];
        } else {
            $err = $weatherCheck->errMsg($errCheck);

            $obj = [
                'error' => $err
            ];
        }

        return [$obj];
    }

    /**
     * post method for diapi page
     *
     * @return object json data
     */
    public function weatherActionPost() : array
    {
        $weatherCheck = $this->di->get("weather");
        $request = $this->di->get("request");
        $ipString = $request->getPost("ipstring", "") ?? null;
        $latString = $request->getPost("latstring", "") ?? null;
        $lonString = $request->getPost("lonstring", "") ?? null;

        if ($ipString) {
            $location = $weatherCheck->getLocation($ipString);
            $latString = $location[0];
            $lonString = $location[1];

            $errCheck = "ipcheck";
        } elseif ($latString && $lonString) {
            $errCheck = "coordcheck";
        } else {
            $errCheck = "urlcheck";
        }

        if ($weatherCheck->checkCoords($latString, $lonString)) {
            $histUrls = $weatherCheck->getHistoryUrls($latString, $lonString);
            $foreUrls = $weatherCheck->getForecastUrls($latString, $lonString);

            $histData = $weatherCheck->getWeather($histUrls);
            $foreData = $weatherCheck->getWeather($foreUrls);

            $obj = [
                'forecast' => $foreData,
                'history' => $histData
            ];
        } else {
            $err = $weatherCheck->errMsg($errCheck);

            $obj = [
                'error' => $err
            ];
        }

        return [$obj];
    }
}
