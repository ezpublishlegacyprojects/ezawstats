<div class="context-block">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'File type'|i18n( 'awstats/stats' )}</h1>
<div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br">
<div class="box-content">
<div class="context-attributes">
<table class="list" cellspacing="0">
<tbody>
<tr class="cdata">
    <th colspan="3">{'File type'|i18n( 'awstats/stats' )}</th>
    <th>{'Hits'|i18n( 'awstats/stats' )}</th>
    <th>{'Percent'|i18n( 'awstats/stats' )}</th>
    <th>{'Bandwidth'|i18n( 'awstats/stats' )}</th>
    <th>{'Percent'|i18n( 'awstats/stats' )}</th>
</tr>
{foreach $awstats.data.filetypes as $type sequence array( 'bglight', 'bgdark' ) as $css}
<tr class="{$css}">
    <td class="cdata">{if $type.Icon}<img src={$type.Icon|ezimage()} alt="{$type.Name|wash()}" />{else}-{/if}</td>
    <td>{$type.Type|wash()}</td>
    <td>{$type.Name|wash()}</td>
    <td class="cdata">{$type.Hits}</td>
    <td class="cdata">{$type.HitsPercent}%</td>
    <td class="cdata">{$type.Bandwidth|si( 'byte' )}</td>
    <td class="cdata">{$type.BandwidthPercent}%</td>
</tr>
{/foreach}
</tbody>
</table>
</div>
</div>
</div></div></div></div></div>
</div>
