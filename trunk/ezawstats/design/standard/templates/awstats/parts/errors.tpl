<div class="context-block">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'HTTP Status codes'|i18n( 'awstats/stats' )}</h1>
<div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br">
<div class="box-content">
<div class="context-attributes">
<table class="list double" cellspacing="0">
<tbody>
<tr class="cdata">
    <th colspan="2">{'HTTP Status codes'|i18n( 'awstats/stats' )}*</th>
    <th>{'Hits'|i18n( 'awstats/stats' )}</th>
    <th>{'Percent'|i18n( 'awstats/stats' )}</th>
    <th>{'Bandwidth'|i18n( 'awstats/stats' )}</th>
</tr>
{def $url_404 = concat( 'awstats/404/', $awstats.year, '/', $awstats.month, '/', $awstats.site )}
{foreach $awstats.data.errors as $code sequence array( 'bglight', 'bgdark' ) as $css}
<tr class="{$css}">
    {if $code.Code|eq( '404' )}
    <td class="cdata"><a href={$url_404|ezurl()}>{$code.Code|wash()}</a></td>
    <td><a href={$url_404|ezurl()}>{$code.Name|wash()}</a></td>
    {else}
    <td class="cdata">{$code.Code|wash()}</td>
    <td>{$code.Name|wash()}</td>
    {/if}
    <td class="cdata">{$code.Hits}</td>
    <td class="cdata">{$code.HitsPercent}%</td>
    <td class="cdata">{$code.Bandwidth|si( 'byte' )}</td>
</tr>
{/foreach}
{undef $url_404}
</tbody>
</table>
<p>* Codes shown here gave hits or traffic "not viewed" by visitors, so they are not included in other charts.</p>
</div>
</div>
</div></div></div></div></div>
</div>
