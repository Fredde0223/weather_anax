<?php

namespace Fredde\DI;

/**
 * geographical check for IP-addresses
 */
class DIWeather
{
    public function __construct(string $ipkey, string $owkey)
    {
        $this->ipkey = $ipkey;
        $this->owkey = $owkey;
    }

    /**
     * check location for ipstring
     *
     * @var string $ipstring   string input to check
     *
     * @return array to output location info
     */
    public function getLocation($inp) : array
    {
        $apikey = $this->ipkey;
        $curl = curl_init('http://api.ipstack.com/'.$inp.'?access_key='.$apikey.'');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $jsonRes = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($jsonRes, true);

        $lat = $res['latitude'] ?? null;
        $lon = $res['longitude'] ?? null;
        $city = $res['city'] ?? null;
        $country = $res['country_name'] ?? null;
        $zip = $res['zip'] ?? null;

        return [$lat, $lon, $city, $country, $zip];
    }

    /**
     * check if succesful search
     *
     * @var string $lat   latitude value
     * @var string $lon   longitude value
     *
     * @return bool
     */
    public function checkCoords($lat, $lon) : bool
    {
        if ($lat == null || $lon == null) {
            return false;
        }

        if (is_numeric($lat) && is_numeric($lon)) {
            $latVal = floatval($lat);
            $lonVal = floatval($lon);

            if ($latVal >= -90 && $latVal <= 90) {
                if ($lonVal >= -180 && $lonVal <= 180) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * error messages if IP or coordinates are not valid
     *
     * @var string $err   to check which error
     *
     * @return string to output weather data
     */
    public function errMsg($err) : string
    {
        if ($err == "ipcheck") {
            return "Ogiltig IP-adress, kan ej hitta position.";
        } elseif ($err == "coordcheck") {
            return "Kan ej hitta position för koordinaterna. Latitudvärdet för en position kan vara -90 till 90 och longitudvärdet kan vara -180 till 180.";
        } elseif ($err == "urlcheck") {
            return "Något blev fel, förmodligen är dina queryvärden inte korrekta.";
        }
        return "Error";
    }

    /**
     * getting historic weather data from the api
     *
     * @var array $urlArray   urls array
     *
     * @return array to output weather data
     */
    public function getWeather($urlArray) : array
    {
        $resArray = [];
        $curlOptions = [
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true
        ];

        $multiHandle = curl_multi_init();
        $curlHandleArray = [];

        foreach ($urlArray as $key => $url) {
            $curlHandle = curl_init($url);
            curl_setopt_array($curlHandle, $curlOptions);
            $curlHandleArray[$key] = $curlHandle;
            curl_multi_add_handle($multiHandle, $curlHandle);
        }

        $active = null;

        do {
            $mrc = curl_multi_exec($multiHandle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            while (curl_multi_exec($multiHandle, $active) == CURLM_CALL_MULTI_PERFORM);
        }

        foreach ($curlHandleArray as $curlHandle) {
            curl_multi_remove_handle($multiHandle, $curlHandle);
        }

        curl_multi_close($multiHandle);

        foreach ($curlHandleArray as $key => $curlHandle) {
            $curlHandleData = curl_multi_getcontent($curlHandle);
            $resJson = json_decode($curlHandleData, JSON_UNESCAPED_UNICODE);
            $resArray[$key] = $resJson;
        }

        return $resArray;
    }

    /**
     * getting urls for weather data api
     *
     * @var string $lat   latitude value
     * @var string $lon   longitude value
     *
     * @return array urls
     */
    public function getHistoryUrls($lat, $lon) : array
    {
        $urlArray = [];
        $dt = time();

        for ($i = 0; $i < 5; $i++) {
            $dt -= 60*60*24;
            $url = "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=" . $lat . "&lon=" . $lon . "&dt=" . $dt .  "&appid=" . $this->owkey . "&units=metric&lang=se";

            array_push($urlArray, $url);
        }

        return $urlArray;
    }

    /**
     * getting urls for weather data api
     *
     * @var string $lat   latitude value
     * @var string $lon   longitude value
     *
     * @return array urls
     */
    public function getForecastUrls($lat, $lon) : array
    {
        $urlArray = [];

        $url = "https://api.openweathermap.org/data/2.5/onecall?lat=" . $lat . "&lon=" . $lon . "&exclude=minutely,hourly,current&appid=" . $this->owkey . "&units=metric&lang=se";

        array_push($urlArray, $url);

        return $urlArray;
    }

    /**
     * getting loopvalues to show correct forecast, api shows forecast one day wrong if not same date as UTC
     *
     * @var array $forecast   forecast array
     *
     * @return array loopvalues for viewpage
     */
    public function checkTimezone($forecast) : array
    {
        if (date("Y-m-d", $forecast[0]["daily"][1]["dt"]) == date("Y-m-d")) {
            return [2, 5];
        } elseif (date("Y-m-d", $forecast[0]["daily"][0]["dt"]) == date("Y-m-d")) {
            return [1, 4];
        }
        return [0, 3];
    }

    /**
     * getting urls for weather data api
     *
     * @var string $lat   latitude value
     * @var string $lon   longitude value
     *
     * @return string code to print map
     */
    public function getOSM($lat, $lon) : string
    {
        $mapcode = '<iframe width="750" height="500" frameborder="0" scrolling="no" src="https://www.openstreetmap.org/export/embed.html?bbox='. ($lon - 2) . '%2C' . ($lat - 2) . '%2C' . ($lon + 2) . '%2C' . ($lat + 2) . '&amp;layer=mapnik&amp;marker=' . $lat . "%2C" . $lon . '" style="border: 1px solid black"></iframe>';
        return $mapcode;
    }
}
