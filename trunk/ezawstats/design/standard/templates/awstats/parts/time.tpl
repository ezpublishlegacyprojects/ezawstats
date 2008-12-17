<div class="context-block">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Hours'|i18n( 'awstats/stats' )}</h1>
<div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br">
<div class="box-content">
<div class="context-attributes">

<table class="list chart" cellspacing="0">
<tr>
{def $total = $awstats.data.total}
{foreach $awstats.data.time as $hour => $time_info}
    <td class="cdata images"><img src={'vp.png'|ezimage} alt="{$time_info.Pages} {'pages'|i18n( 'awstats/stats' )}" height="{$time_info.Pages|mul( 400 )|div( $total.Hits )|floor}" width="6" /><img src={'vh.png'|ezimage} alt="{$time_info.Hits} {'hits'|i18n( 'awstats/stats' )}" height="{$time_info.Hits|mul( 400 )|div( $total.Hits )|floor}" width="6" /><img src={'vk.png'|ezimage} alt="{$time_info.Bandwidth|si( 'byte' )}" height="{$time_info.Bandwidth|mul( 400 )|div( $total.Bandwidth )|floor}" width="6" /></td>
{/foreach}
</tr>
<tr>
{foreach $awstats.data.time as $hour => $time_info}
    <td class="cdata hour">{$hour}</th>
{/foreach}
</tr>
</table>

<table class="list double" cellspacing="0">
<tbody>
<tr class="cdata">
    <th>{'Hours'|i18n( 'awstats/stats' )}</th>
    <th>{'Pages'|i18n( 'awstats/stats' )}</th>
    <th>{'Hits'|i18n( 'awstats/stats' )}</th>
    <th>{'Bandwidth'|i18n( 'awstats/stats' )}</th>
    <th>{'Hours'|i18n( 'awstats/stats' )}</th>
    <th>{'Pages'|i18n( 'awstats/stats' )}</th>
    <th>{'Hits'|i18n( 'awstats/stats' )}</th>
    <th>{'Bandwidth'|i18n( 'awstats/stats' )}</th>
</tr>
{def $hour_12=11}
{foreach $awstats.data.time as $hour => $time_info max 12}
<tr>
    <td class="cdata hour">{$hour}</td>
    <td class="cdata">{$time_info.Pages}</td>
    <td class="cdata">{$time_info.Hits}</td>
    <td class="cdata">{$time_info.Bandwidth|si( 'byte' )}</td>
    {set $hour_12 = $hour_12|inc()}
    <td class="cdata hour">{$hour_12}</td>
    <td class="cdata">{$awstats.data.time[$hour_12].Pages}</td>
    <td class="cdata">{$awstats.data.time[$hour_12].Hits}</td>
    <td class="cdata">{$awstats.data.time[$hour_12].Bandwidth|si( 'byte' )}</td>
</tr>
{/foreach}

</tbody>
</table>

<div class="break"> </div>
</div>
</div>
</div></div></div></div></div>
</div>
