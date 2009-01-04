<div class="context-block">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Required but not found URLs (HTTP code 404)'|i18n( 'awstats/stats' )}</h1>
<div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br">
<div class="box-content">
<div class="context-attributes">
<table class="list double" cellspacing="0">
<tbody>
<tr class="cdata">
    <th>{'URL'|i18n( 'awstats/stats' )} ({$awstats.data.errors404|count()})</th>
    <th>{'Error hits'|i18n( 'awstats/stats' )}</th>
    <th>{'Referers'|i18n( 'awstats/stats' )}</th>
</tr>
{foreach $awstats.data.errors404 as $error sequence array( 'bglight', 'bgdark' ) as $css}
<tr class="{$css}">
    <td><a href={$error.URL|ezurl()}>{$error.URL}</a></td>
    <td class="cdata">{$error.Hits}</td>
    <td>{cond( $error.Referer, concat( '<a href=', $error.Referer|ezurl(), '>', $error.Referer|wash(), '</a>' ), '-' )}</td>
</tr>
{/foreach}
</tbody>
</table>
</div>
</div>
</div></div></div></div></div>
</div>
