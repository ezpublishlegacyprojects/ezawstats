<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h4>{'Stats Options'|i18n( 'awstats/stats' )}</h4>
</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
<form action={'awstats/stats'|ezurl()} method="post">
    <p>
        <label for="Site">{'Site'|i18n( 'awstats/stats' )}</label>
        <select name="Site" id="Site">
            {foreach $awstats_sites as $site}
            <option value="{$site|wash()}"{cond( $site|eq( $awstats.site ), ' selected="selected"', '' )}>{$site|wash()}</option>

            {/foreach}
        </select>
    </p>
    <p>
        <label for="Date">{'Date'|i18n( 'awstats/stats' )}</label>
        <select name="Date" id="Date">
        {foreach $awstats_dates as $year => $months}
            {foreach $months as $month}
            <option value="{$year}-{$month}"{cond( and( $awstats.month|eq( $month ),
                                                        $awstats.year|eq( $year ) ), ' selected="selected"', '' )}>{$year}/{$month}</option>

            {/foreach}
        {/foreach}
        </select>
    </p>
    <p><input type="submit" class="button" name="RedirectAWStats" value="{'Display'|i18n( 'awstats/stats' )}" />
</form>
</div></div></div></div></div></div>
