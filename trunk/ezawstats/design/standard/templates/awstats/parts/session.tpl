<div class="context-block">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Visits duration'|i18n( 'awstats/stats' )}</h1>
<div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br">
<div class="box-content">
<div class="context-attributes">
<table class="list simple" cellspacing="0">
<tbody>
<tr class="cdata">
    <th>{'Number of visits'|i18n( 'awstats/stats' )} : {$awstats.data.general.TotalVisits}</th>
    <th>{'Number of visits'|i18n( 'awstats/stats' )}</th>
    <th>{'Percent'|i18n( 'awstats/stats' )}</th>
</tr>
{foreach $awstats.data.session as $session sequence array( 'bglight', 'bgdark' ) as $css}
<tr class="{$css}">
    <td>{$session.Name|wash()}</td>
    <td class="tight cdata">{$session.Visits}</td>
    <td class="tight cdata">{$session.Percent}%</td>
</tr>
{/foreach}
</tbody>
</table>
</div>
</div>
</div></div></div></div></div>
</div>
