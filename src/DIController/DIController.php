<?php

namespace Fredde\DIController;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Weather service
 */
class DIController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * get method for DI weather page
     *
     * @return object render DI weather page
     */
    public function indexActionGet() : object
    {
        $title = "Väderservice";

        $page = $this->di->get("page");
        $session = $this->di->get("session");

        $weatherHistory = $session->get("weatherHistory") ?? null;
        $weatherForecast = $session->get("weatherForecast") ?? null;
        $timezoneValues = $session->get("timezoneValues") ?? null;
        $locationInfo = $session->get("locationInfo") ?? null;
        $map = $session->get("map") ?? null;
        $err = $session->get("err") ?? null;

        $session->set("weatherHistory", null);
        $session->set("weatherForecast", null);
        $session->set("timezoneValues", null);
        $session->set("locationInfo", null);
        $session->set("map", null);
        $session->set("err", null);

        $data = [
            "history" => $weatherHistory,
            "forecast" => $weatherForecast,
            "timezoneVals" => $timezoneValues,
            "locationInfo" => $locationInfo,
            "map" => $map,
            "err" => $err
        ];

        $page->add("di/weatherDI", $data);

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * post method for DI weather page
     *
     * @return object redirect DI weather page
     */
    public function indexActionPost() : object
    {
        $request = $this->di->get("request");
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $weatherCheck = $this->di->get("weather");

        $errCheck = null;
        $latString = null;
        $lonString = null;

        $ipCheck = $request->getPost("ipcheck") ?? null;
        $coordCheck = $request->getPost("coordcheck") ?? null;

        if ($ipCheck) {
            $ipString = $request->getPost("ipstring");
            $location = $weatherCheck->getLocation($ipString);
            $latString = $location[0];
            $lonString = $location[1];

            $errCheck = "ipcheck";

            $session->set("locationInfo", $location);
        } elseif ($coordCheck) {
            $latString = $request->getPost("latstring");
            $lonString = $request->getPost("lonstring");

            $errCheck = "coordcheck";
        }

        if ($weatherCheck->checkCoords($latString, $lonString)) {
            $histUrls = $weatherCheck->getHistoryUrls($latString, $lonString);
            $foreUrls = $weatherCheck->getForecastUrls($latString, $lonString);

            $histData = $weatherCheck->getWeather($histUrls);
            $foreData = $weatherCheck->getWeather($foreUrls);

            $timezoneVals = $weatherCheck->checkTimezone($foreData);

            $map = $weatherCheck->getOSM($latString, $lonString);

            $session->set("weatherHistory", $histData);
            $session->set("weatherForecast", $foreData);
            $session->set("timezoneValues", $timezoneVals);
            $session->set("map", $map);
        } else {
            $err = $weatherCheck->errMsg($errCheck);

            $session->set("err", $err);
        }

        return $response->redirect("di");
    }
}
