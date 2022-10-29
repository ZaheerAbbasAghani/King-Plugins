<?php

function show_cases_by_country(){
return "<select class='cv19'><option selected='selected' disabled='disabled'>Choose Country</option></select>
<ul class='details'>
<li><label>Confirmed</label><div id='confirmed'>0</div></li>
<li><label>Deaths</label><div id='deaths'>0</div></li>
<li><label>Recovered</label><div id='recovered'>0</div></li>
<ul>";
}
add_shortcode("cv19","show_cases_by_country");