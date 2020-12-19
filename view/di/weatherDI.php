<?php

namespace Anax\View;

?><h1>Väderservice</h1>

<p><a href="diapi">Väderservice (API)</a></p>

<p>Sök via IP-adress:</p>
<form method="post">
    IPv4/IPv6: <input type="text" name="ipstring">
    <input type="submit" name="ipcheck" value="Sök">
</form>
<p>Sök via koordinater:</p>
<form method="post">
    Latitud: <input type="text" name="latstring">
    Longitud: <input type="text" name="lonstring">
    <input type="submit" name="coordcheck" value="Sök">
</form>

<?php if (!$err && !$history && !$forecast) : ?>
    <p>Sök för att hitta en position.</p>
<?php endif; ?>

<?php if ($err) : ?>
    <p>Ett fel uppstod vid sökningen:</p>
    <p><?= $err ?></p>
<?php endif; ?>

<?php if ($history && $forecast && $timezoneVals) : ?>
    <h3>Väderprognos (3 dagar framåt):</h3>
    <table style="text-align: center;width: 750px;">
        <tr>
            <th>Datum</th>
            <th>Temperatur</th>
            <th>Väder</th>
            <th>Vindstyrka</th>
        </tr>
        <?php for ($i = $timezoneVals[0]; $i < $timezoneVals[1]; $i++) : ?>
            <tr>
                <td><?= date("Y-m-d", $forecast[0]["daily"][$i]["dt"]) ?></td>
                <td><?= $forecast[0]["daily"][$i]["temp"]["day"]; ?> °C</td>
                <td><?= $forecast[0]["daily"][$i]["weather"][0]["description"]; ?></td>
                <td><?= $forecast[0]["daily"][$i]["wind_speed"]; ?> m/s</td>
            </tr>
        <?php endfor; ?>
    </table>
    <h3>Väderhistorik (5 dagar bakåt):</h3>
    <table style="text-align: center;width: 750px;">
        <tr>
            <th>Datum</th>
            <th>Temperatur</th>
            <th>Väder</th>
            <th>Vindstyrka</th>
        </tr>
        <?php for ($i = 0; $i < 5; $i++) : ?>
            <tr>
                <td><?= date("Y-m-d", $history[$i]["hourly"][17]["dt"]) ?></td>
                <td><?= $history[$i]["hourly"][17]["temp"]; ?> °C</td>
                <td><?= $history[$i]["hourly"][17]["weather"][0]["description"]; ?></td>
                <td><?= $history[$i]["hourly"][17]["wind_speed"]; ?> m/s</td>
            </tr>
        <?php endfor; ?>
    </table>
    <h3>Platsinformation (för mer info sök via IP):</h3>
    <?php if ($locationInfo) : ?>
        <p>Stad: <?= $locationInfo[2]; ?></p>
        <p>Land: <?= $locationInfo[3]; ?></p>
        <p>Postkod: <?= $locationInfo[4]; ?></p>
    <?php endif; ?>
    <p>Latitud: <?= $forecast[0]["lat"]; ?>°</p>
    <p>Longitud: <?= $forecast[0]["lon"]; ?>°</p>
    <p>Tidszon: <?= $forecast[0]["timezone"]; ?></p>
    <?= $map ?>
<?php endif; ?>
