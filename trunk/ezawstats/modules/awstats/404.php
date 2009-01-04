<?php
$http = eZHTTPTool::instance();
$Module = $Params['Module'];
$statsINI = eZINI::instance( 'ezawstats.ini' );

$dataDir = $statsINI->variable( 'AWStatsSettings', 'DataDir' );

if ( !is_dir( $dataDir ) || !is_readable( $dataDir ) )
{
    eZDebug::writeError( 'DataDir ' . $dataDir . ' does not exist or is not readable', 'ezawstats' );
    eZExecution::cleanExit();
}

$siteList = $statsINI->variable( 'AWStatsSettings', 'SiteList' );
$Site = $statsINI->variable( 'AWStatsSettings', 'DefaultSite' );
if ( isset( $Params['Site'] ) )
{
    if ( !in_array( $Params['Site'], $siteList ) )
    {
        eZDebug::writeWarning( 'Site in parameters is not in available site list', 'ezawstats' );
    }
    else
    {
        $Site = $Params['Site'];
    }
}

$Year = (int) date( 'Y' );
$Month = sprintf( '%02d', (int) date( 'm' ) );
if ( isset( $Params['Year'] ) )
{
    $Year = (int) $Params['Year'];
}
if ( isset( $Params['Month'] ) )
{
    $Month = (int) $Params['Month'];
    $Month = sprintf( '%02d', $Month );
}

$awstats = new eZAWStats( $dataDir, $Site, $Year, $Month );
eZDebug::accumulatorStart( 'parsing', 'AWStats', 'Parsing AWStats XML' );
$result = $awstats->parse404();
eZDebug::accumulatorStop( 'parsing' );
if ( $result === false )
{
    eZDebug::writeError( 'Error when parsing ' . $awstats->attribute( 'data_file' ), 'awstats' );
    eZExecution::cleanExit();
}
//eZDebug::writeDebug( $awstats );
eZDebug::accumulatorStart( 'datelist', 'AWStats', 'Date list' );
$dateList = $awstats->dateList();
eZDebug::accumulatorStop( 'datelist' );

require_once 'kernel/common/template.php';
$tpl = templateInit();
$tpl->setVariable( 'awstats_sites', $siteList );
$tpl->setVariable( 'awstats_dates', $dateList );
$tpl->setVariable( 'awstats', $awstats );
$tpl->setVariable( 'date', new eZDate() );

$Result['content'] = $tpl->fetch( 'design:awstats/404.tpl' );
$Result['left_menu']  = 'design:parts/awstats/menu.tpl';
$Result['pagelayout'] = 'design:pagelayout_awstats.tpl';
$Result['path'] = array( array( 'text' => 'AWStats',
                                'url'  => false ),
                         array( 'text' => $Site,
                                'url'  => false ) );
?>
