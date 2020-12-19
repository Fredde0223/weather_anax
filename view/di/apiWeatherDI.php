<?php

namespace Anax\View;

?><h1>Väderservice (API)</h1>

<p>Detta är en väderservice för olika platser med hjälp av ett API. Det första sättet att jobba med API:et är att genom query-strängar ange en IP-adress eller koordinater (latitud och longitud). Detta ger en respons i json-format.</p>

<p><a href="diapi/weather?ip=2.2.2.2">Exempel 1: /weather?ip=2.2.2.2</a></p>
<p><a href="diapi/weather?ip=thisisnoip">Exempel 2: /weather?ip=thisisnoip</a></p>
<p><a href="diapi/weather?lat=52.5&lon=13.4">Exempel 3: /weather?lat=52.5&lon=13.4</a></p>
<p><a href="diapi/weather?lat=999&lon=999">Exempel 4: /weather?lat=999&lon=999</a></p>
<p><a href="diapi/weather?thisquery=doesnotexist">Exempel 5: /weather?thisquery=doesnotexist</a></p>

<p>Det andra sättet att jobba med API:et är att antingen posta en IP-adress eller koordinater i formulären nedan. Detta kommer att ge dig en json-respons precis som innan.</p>

<p>Sök via IP-adress:</p>
<form action="diapi/weather" method="post">
    IPv4/IPv6: <input type="text" name="ipstring">
    <input type="submit" name="ipcheck" value="Sök">
</form>
<p>Sök via koordinater:</p>
<form action="diapi/weather" method="post">
    Latitud: <input type="text" name="latstring">
    Longitud: <input type="text" name="lonstring">
    <input type="submit" name="coordcheck" value="Sök">
</form>
