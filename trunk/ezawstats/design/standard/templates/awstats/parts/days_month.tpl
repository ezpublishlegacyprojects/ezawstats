<div class="context-block">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Days of month'|i18n( 'awstats/stats' )}</h1>
<div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br">
<div class="box-content">
<div class="context-attributes">

<table class="list chart" cellspacing="0">
<tbody>
<tr class="cdata day_line">
{foreach $awstats.data.day as $day_info}
    <td class="cdata images"><img src={'vv.png'|ezimage} alt="{'Numbers of visits:'|i18n( 'awstats/stats' )} {$day_info.Visits}" height="{$day_info.Visits|mul( 100 )|div( $awstats.data.general.TotalVisits )}" width="4" /><img src={'vp.png'|ezimage} alt="{'Pages:'|i18n( 'awstats/stats' )} {$day_info.Pages}" height="{$day_info.Pages|mul( 100 )|div( $awstats.data.total.Pages|sum( $awstats.data.total.NotViewedPages ) )}" width="4" /><img src={'vh.png'|ezimage} alt="{'Hits:'|i18n( 'awstats/stats' )} {$day_info.Hits}" height="{$day_info.Hits|mul( 100 )|div( $awstats.data.total.Hits|sum( $awstats.data.total.NotViewedHits ) )}" width="4" /><img src={'vk.png'|ezimage} alt="{'Bandwidth:'|i18n( 'awstats/stats' )} {$day_info.Bandwidth|si( 'byte' )}" height="{$day_info.Bandwidth|mul( 100 )|div( $awstats.data.total.Bandwidth|sum( $awstats.data.total.NotViewedBandwidth ) )}" width="4" /></td>
{/foreach}
</tr>
<tr class="day_line">
{foreach $awstats.data.day as $day_info}
    <td class="{cond( $day_info.Date|date_eq( $date ), 'selected', '' )} {cond( $day_info.Date|is_week_end, 'week-end', '' )}">{$day_info.Date.day}</td>
{/foreach}
</tr>
</tbody>
</table>


<table class="list simple" cellspacing="0">
<tbody>
<tr class="cdata">
    <th style="width:20%">{'Day'|i18n( 'awstats/stats' )}</th>
    <th style="width:20%">{'Number of visits'|i18n( 'awstats/stats' )}</th>
    <th style="width:20%">{'Pages'|i18n( 'awstats/stats' )}</th>
    <th style="width:20%">{'Hits'|i18n( 'awstats/stats' )}</th>
    <th style="width:20%">{'Bandwidth'|i18n( 'awstats/stats' )}</th>
</tr>
{foreach $awstats.data.day as $day_info}
<tr{cond( $day_info.Date|is_week_end, ' class="week-end"', ' class="bglight"' )}>
    <td class="cdata{cond( $day_info.Date|date_eq( $date ), ' selected', '' )}">{$day_info.Date.timestamp|datetime( 'awstats_day_month' )} </td>
    <td class="cdata">{$day_info.Visits}</td>
    <td class="cdata">{$day_info.Pages}</td>
    <td class="cdata">{$day_info.Hits}</td>
    <td class="cdata">{$day_info.Bandwidth|si( 'byte' )}</td>
</tr>
{/foreach}

</tbody>
</table>

<div class="break"> </div>
</div>
</div>
</div></div></div></div></div>
</div>
