<div class="context-block">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Summary for %site'|i18n( 'awstats/stats', , hash( '%site', $awstats.site ) )}</h1>
<div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br">
<div class="box-content">
<div class="context-attributes">

<table class="list" cellspacing="0">
<tbody>
<tr>
    <th colspan="6">{'Summary'|i18n( 'awstats/stats' )}</th>
</tr>
<tr class="bglight">
    <td><b>{'Last updated'|i18n( 'awstats/stats' )}</b></td>
    <td colspan="5">{$awstats.data.general.LastUpdate.LastUpdateDate.timestamp|l10n( 'shortdatetime' )}</td>
</tr>
<tr class="bgdark">
    <td><b>{'First visit'|i18n( 'awstats/stats' )}</b></td>
    <td colspan="5">{$awstats.data.general.FirstTime.timestamp|l10n( 'shortdatetime' )}</td>
</tr>
<tr class="bglight">
    <td><b>{'Last visit'|i18n( 'awstats/stats' )}</b></td>
    <td colspan="5">{$awstats.data.general.LastTime.timestamp|l10n( 'shortdatetime' )}</td>
</tr>

<tr class="cdata">
    <th></th>
    <th>{'Unique visitors'|i18n( 'awstats/stats' )}</th>
    <th>{'Number of visits'|i18n( 'awstats/stats' )}</th>
    <th>{'Pages'|i18n( 'awstats/stats' )}</th>
    <th>{'Hits'|i18n( 'awstats/stats' )}</th>
    <th>{'Bandwidth'|i18n( 'awstats/stats' )}</th>
</tr>
<tr class="bglight">
    <td>{'Viewed traffic'|i18n( 'awstats/stats' )}*</td>
    <td class="cdata"><b>{$awstats.data.general.TotalUnique}</b></td>
    <td class="cdata">
        <b>{$awstats.data.general.TotalVisits}</b><br />
        ({$awstats.data.general.VisitsByVisitor} {'visits/visitor'|i18n( 'awstats/stats' )})
    </td>
    <td class="cdata">
        <b>{$awstats.data.total.Pages}</b><br />
        ({$awstats.data.general.PagesByVisit} {'pages/visit'|i18n( 'awstats/stats' )})
    </td>
    <td class="cdata">
        <b>{$awstats.data.total.Hits}</b><br />
        ({$awstats.data.general.HitsByVisit} {'hits/visit'|i18n( 'awstats/stats' )})
    </td>
    <td class="cdata">
        <b>{$awstats.data.total.Bandwidth|si( 'byte' )}</b><br />
        ({$awstats.data.general.BandwidthByVisit|si( 'byte' )}{'/visit'|i18n( 'awstats/stats' )})
    </td>
</tr>
<tr class="bglight">
    <td>{'Not viewed traffic'|i18n( 'awstats/stats' )}*</td>
    <td></td>
    <td></td>
    <td class="cdata"><b>{$awstats.data.total.NotViewedPages}</b></td>
    <td class="cdata"><b>{$awstats.data.total.NotViewedHits}</b></td>
    <td class="cdata"><b>{$awstats.data.total.NotViewedBandwidth|si( 'byte' )}</b></td>
</tr>

</tbody>
</table>
<p>* {'Not viewed traffic includes traffic generated by robots, worms, or replies with special HTTP status codes.'|i18n( 'awstats/stats' )}</p>
</div>
</div>
</div></div></div></div></div>
</div>
