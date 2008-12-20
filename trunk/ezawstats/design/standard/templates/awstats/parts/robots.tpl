<div class="context-block">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Robots/Spiders visitors (Top 10)'|i18n( 'awstats/stats' )}</h1>
<div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br">
<div class="box-content">
<div class="context-attributes">
<table class="list" cellspacing="0">
<tbody>
<tr class="cdata">
    <th>{'Name'|i18n( 'awstats/stats' )} (<a href={concat( 'awtstats/robots/', $awstats.year, '/', $awstats.month, '/', $awstats.site )|ezurl()}>{'Full list'|i18n( 'awtstats/stats' )}</a>)</th>
    <th>{'Hits + robots.txt hits'|i18n( 'awstats/stats' )}</th>
    <th>{'Bandwidth'|i18n( 'awstats/stats' )}</th>
    <th>{'Last visit'|i18n( 'awstats/stats' )}</th>
</tr>
{foreach $awstats.data.robot as $robot sequence array( 'bglight', 'bgdark' ) as $css}
<tr class="{$css}">
    <td>{$robot.Name}</td>
    <td class="cdata">{$robot.Hits}+{$robot.RobotsTxtHits}</td>
    <td class="cdata">{$robot.Bandwidth|si( 'byte' )}</td>
    <td class="cdata">{$robot.LastVisitDate.timestamp|l10n( 'shortdatetime' )}</td>
</tr>
{/foreach}
</tbody>
</table>
</div>
</div>
</div></div></div></div></div>
</div>
