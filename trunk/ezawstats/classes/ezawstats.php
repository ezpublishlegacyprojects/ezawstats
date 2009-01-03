<?php

class eZAWStats
{
    private $DataDir;
    private $Site;
    private $Year;
    private $Month;
    private $Data;

    function __construct( $dataDir, $site, $year, $month )
    {
        $this->DataDir = $dataDir;
        $this->Site = $site;
        $this->Year = $year;
        $this->Month = $month;
        $this->Data = array();
    }

    function attributes()
    {
        return array( 'data_dir',
                      'site',
                      'year',
                      'month',
                      'data_file',
                      'data' );
    }

    function hasAttribute( $attribute )
    {
        return in_array( $attribute, $this->attributes() );
    }

    function attribute( $name )
    {
        switch( $name )
        {
            case 'data_dir':
            {
                return $this->DataDir;
            }break;
            case 'site':
            {
                return $this->Site;
            }break;
            case 'year':
            {
                return $this->Year;
            }break;
            case 'month':
            {
                return $this->Month;
            }break;
            case 'data_file':
            {
                return $this->dataFilePath();
            }break;
            case 'data':
            {
                return $this->Data;
            }break;
            default:
            {
                eZDebug::writeError( 'Attribute ' . $name . ' does not exist', __CLASS__ );
                return null;
            }
        }
    }

    public function parse()
    {
        $dom = new DomDocument();
        $dom->strictErrorChecking = false;
        $dom->recover = true;
        $file = $this->dataFilePath();
        $result = @$dom->load( $file );
        if ( $result === false )
        {
            eZDebug::writeError( $file . ' seems to not be a valid XML file', __CLASS__ );
            return false;
        }
        $xpath = new DomXPath( $dom );

        $sectionNodes = $xpath->query( '//section[@id!="header"]' );
        foreach( $sectionNodes as $section )
        {
            $this->_parseSection( $section, $xpath );
        }
        $this->Data['general']['VisitsByVisitor'] = round( $this->Data['general']['TotalVisits'] / $this->Data['general']['TotalUnique'], 2 );
        $this->Data['general']['PagesByVisit'] = round( $this->Data['total']['Pages'] / $this->Data['general']['TotalVisits'], 2 );
        $this->Data['general']['HitsByVisit'] = round( $this->Data['total']['Hits'] / $this->Data['general']['TotalVisits'], 2 );
        $this->Data['general']['BandwidthByVisit'] = round( $this->Data['total']['Bandwidth'] / $this->Data['general']['TotalVisits'], 2 );
        return true;
    }

    public function parseRobots()
    {
        $dom = new DomDocument();
        $dom->strictErrorChecking = false;
        $dom->recover = true;
        $file = $this->dataFilePath();
        $result = @$dom->load( $file );
        if ( $result === false )
        {
            eZDebug::writeError( $file . ' seems to not be a valid XML file', __CLASS__ );
            return false;
        }
        $xpath = new DomXPath( $dom );
        $sectionRobots = $xpath->query( '//section[@id="robot"]' );
        $this->_parseSectionRobot( $sectionRobots->item( 0 ), $xpath, false );
    }

    private function _parseSection( $section, $xpath )
    {
        $sectionID = $section->getAttribute( 'id' );
        $methodName = '_parseSection' . ucfirst( $sectionID );
        if ( method_exists( $this, $methodName ) )
        {
            call_user_func( array( $this, $methodName ), $section, $xpath );
        }
        else
        {
            eZDebug::writeNotice( $methodName . ' is not implemented', __CLASS__ );
        }
    }

    private function _parseSectionGeneral( $section, $xpath )
    {
        $this->Data['general'] = array();
        $data =& $this->Data['general'];
        $valueNodes = $xpath->query( 'table/tr', $section );
        foreach( $valueNodes as $node )
        {
            $name = trim( $node->childNodes->item( 0 )->nodeValue );
            if ( $name == 'LastLine' )
            {
                $data[$name] = array();
                $tmpArray = explode( ' ', trim( $node->childNodes->item( 1 )->nodeValue ) );
                $data[$name]['LastRecordDate'] = self::createDateTime( $tmpArray[0] );
                $data[$name]['LastRecordLineNumber'] = $tmpArray[1];
                $data[$name]['LastRecordOffset'] = $tmpArray[2];
                $data[$name]['LastRecordSignature'] = $tmpArray[3];
            }
            elseif ( $name == 'LastUpdate' )
            {
                $data[$name] = array();
                $tmpArray = explode( ' ', trim( $node->childNodes->item( 1 )->nodeValue ) );
                $data[$name]['LastUpdateDate'] = self::createDateTime( $tmpArray[0] );
                $data[$name]['ParsedRecordNumber'] = $tmpArray[1];
                $data[$name]['ParsedOldRecordNumber'] = $tmpArray[2];
                $data[$name]['ParsedNewRecordNumber'] = $tmpArray[3];
                $data[$name]['ParsedCorruptedRecordNumber'] = $tmpArray[4];
                $data[$name]['ParsedDroppedRecordNumber'] = $tmpArray[5];
            }
            elseif ( $name == 'FirstTime' || $name == 'LastTime' )
            {
                $data[$name] = self::createDateTime( trim( $node->childNodes->item( 1 )->nodeValue ) );
            }
            else
            {
                $data[$name] = trim( $node->childNodes->item( 1 )->nodeValue );
            }
        }
    }

    private function _parseSectionSession( $section, $xpath )
    {
        $this->Data['session'] = array();
        $data =& $this->Data['session'];
        $valueNodes = $xpath->query( 'table/tr', $section );
        $total = 0;
        foreach( $valueNodes as $node )
        {
            $name = trim( $node->childNodes->item( 0 )->nodeValue );
            $data[$name]['Name'] = $name;
            $data[$name]['Visits'] = $node->childNodes->item( 1 )->nodeValue;
            $total += $data[$name]['Visits'];
        }
        $data['Unknown']['Name'] = 'Unknown';
        $data['Unknown']['Visits'] = $this->Data['general']['TotalVisits'] - $total;
        foreach( $data as $key => $value )
        {
            $data[$key]['Percent'] = round( $data[$key]['Visits'] * 100 / $total, 1 );
        }
        usort( $data, array( __CLASS__, 'sessionCmp' ) );
    }

    private static function sessionCmp( $s1, $s2 )
    {
        $res = $s2['Visits'] - $s1['Visits'];
        return $res;
    }

    private function _parseSectionFiletypes( $section, $xpath )
    {
        $mimeINI = eZINI::instance( 'mime.ini' );
        $mimeArray = $mimeINI->variable( 'MimeSettings', 'MimeName' );
        $mimeFamilyArray = $mimeINI->variable( 'MimeSettings', 'MimeFamily' );
        $mimeIconArray = $mimeINI->variable( 'MimeSettings', 'MimeIcon' );
        $this->Data['filetypes'] = array();
        $data =& $this->Data['filetypes'];
        $valueNodes = $xpath->query( 'table/tr', $section );
        $total = array( 'Hits' => 0, 'Bandwidth' => 0 );
        foreach( $valueNodes as $node )
        {
            $type = trim( $node->childNodes->item( 0 )->nodeValue );
            $data[$type] = array();
            $family = isset( $mimeFamilyArray[$type] ) ? $mimeFamilyArray[$type] : false;
            $data[$type]['Icon'] = isset( $mimeIconArray[$type] ) ? 'mime/' . $mimeIconArray[$type] . '.png' : false;
            $data[$type]['Name'] = ( $family && isset( $mimeArray[$family] ) ) ? $mimeArray[$family] : '';
            $data[$type]['Type'] = $type;
            $data[$type]['Hits'] = $node->childNodes->item( 1 )->nodeValue;
            $data[$type]['Bandwidth'] = $node->childNodes->item( 2 )->nodeValue;
            $total['Hits'] += $data[$type]['Hits'];
            $total['Bandwidth'] += $data[$type]['Bandwidth'];
        }
        foreach( $data as $type => $element )
        {
            $data[$type]['HitsPercent'] = round( $data[$type]['Hits'] * 100 / $total['Hits'], 1 );
            $data[$type]['BandwidthPercent'] = round( $data[$type]['Bandwidth'] * 100 / $total['Bandwidth'], 1 );
        }
        usort( $data, array( __CLASS__, 'filetypeCmp' ) );
    }

    private static function filetypeCmp( $type1, $type2 )
    {
        $res = $type2['Hits'] - $type1['Hits'];
        return $res;
    }

    private function _parseSectionTime( $section, $xpath )
    {
        $this->Data['time'] = array();
        $data =& $this->Data['time'];
        $valueNodes = $xpath->query( 'table/tr', $section );
        $total = array( 'Pages' => 0, 'Hits' => 0, 'Bandwidth' => 0,
                        'NotViewedPages' => 0, 'NotViewedHits' => 0,
                        'NotViewedBandwidth' => 0 );
        foreach( $valueNodes as $node )
        {
            $hour = trim( $node->childNodes->item( 0 )->nodeValue );
            $data[$hour] = array();
            $data[$hour]['Pages'] = trim( $node->childNodes->item( 1 )->nodeValue );
            $data[$hour]['Hits'] = trim( $node->childNodes->item( 2 )->nodeValue );
            $data[$hour]['Bandwidth'] = trim( $node->childNodes->item( 3 )->nodeValue );
            $data[$hour]['NotViewedPages'] = trim( $node->childNodes->item( 4 )->nodeValue );
            $data[$hour]['NotViewedHits'] = trim( $node->childNodes->item( 5 )->nodeValue );
            $data[$hour]['NotViewedBandwidth'] = trim( $node->childNodes->item( 6 )->nodeValue );
            $total['Pages'] += $data[$hour]['Pages'];
            $total['Hits'] += $data[$hour]['Hits'];
            $total['Bandwidth'] += $data[$hour]['Bandwidth'];
            $total['NotViewedPages'] += $data[$hour]['NotViewedPages'];
            $total['NotViewedHits'] += $data[$hour]['NotViewedHits'];
            $total['NotViewedBandwidth'] += $data[$hour]['NotViewedBandwidth'];
        }
        $this->Data['total'] = $total;
    }

    private function _parseSectionDay( $section, $xpath )
    {
        $this->Data['day'] = array();
        $data =& $this->Data['day'];
        $template = array( 'Date' => 0, 'Pages' => 0, 'Hits' => 0,
                           'Bandwidth' => 0, 'Visits' => 0 );
        $ts = mktime( 1, 0, 0, $this->Month, 1, $this->Year );
        $dayNumbers = date( 't', $ts );
        for( $i = 1; $i!= $dayNumbers + 1 ; $i++ )
        {
            $data["$i"] = $template;
            $data["$i"]['Date'] = eZDate::create( $this->Month, $i, $this->Year );
        }
        $valueNodes = $xpath->query( 'table/tr', $section );
        foreach( $valueNodes as $node )
        {
            $awstatsDate = trim( $node->childNodes->item( 0 )->nodeValue );
            $day = (int) substr( $awstatsDate, 6, 2 );
            $data["$day"]['Pages'] = trim( $node->childNodes->item( 1 )->nodeValue );
            $data["$day"]['Hits'] = trim( $node->childNodes->item( 2 )->nodeValue );
            $data["$day"]['Bandwidth'] = trim( $node->childNodes->item( 3 )->nodeValue );
            $data["$day"]['Visits'] = trim( $node->childNodes->item( 4 )->nodeValue );
        }
    }

    private function _parseSectionRobot( $section, $xpath, $number = 10 )
    {
        $ini = eZINI::instance( 'browser.ini' );
        $robotList = $ini->variable( 'RobotSettings', 'RobotList' );
        $this->Data['robot'] = array();
        $data =& $this->Data['robot'];
        $xpathExpr = 'table/tr';
        if ( is_integer( $number ) )
        {
            $xpathExpr .= '[position() < ' . $number . ']';
        }
        $nodeList = $xpath->query( $xpathExpr, $section );
        $count = 0;
        foreach( $nodeList as $node )
        {
            $data[$count]['RobotID'] = trim( $node->childNodes->item( 0 )->nodeValue );
            $robotName = trim( $node->childNodes->item( 0 )->nodeValue );
            $robotID = str_replace( '[', '<<', $robotName );
            $robotID = str_replace( ']', '>>', $robotID );
            if ( isset( $robotList[$robotID] ) )
            {
                $robotName = $robotList[$robotID];
            }
            $data[$count]['Name'] = $robotName;
            $data[$count]['Hits'] = trim( $node->childNodes->item( 1 )->nodeValue );
            $data[$count]['Bandwidth'] = trim( $node->childNodes->item( 2 )->nodeValue );
            $data[$count]['LastVisitDate'] = self::createDateTime( trim( $node->childNodes->item( 3 )->nodeValue ) );
            $data[$count]['RobotsTxtHits'] = trim( $node->childNodes->item( 4 )->nodeValue );
            $count++;
        }
        if ( $number === 10 )
        {
            $data[$count]['RobotID'] = 'other';
            $data[$count]['Name'] = 'Others';
            $data[$count]['Hits'] = $xpath->evaluate( 'sum( table/tr[position() > 10]/td[2]/text() )', $section );
            $data[$count]['Bandwidth'] = $xpath->evaluate( 'sum( table/tr[position() > 10]/td[3]/text() )', $section );
            $data[$count]['RobotsTxtHits'] = $xpath->evaluate( 'sum( table/tr[position() > 10]/td[5]/text() )', $section );
            $data[$count]['LastVisitDate'] = false;
        }
    }

    static function createDateTime( $awstatsDateTime )
    {
        $year = substr( $awstatsDateTime, 0, 4 );
        $month = substr( $awstatsDateTime, 4, 2 );
        $day = substr( $awstatsDateTime, 6, 2 );
        $hour = substr( $awstatsDateTime, 8, 2 );
        $minute = substr( $awstatsDateTime, 10, 2 );
        $second = substr( $awstatsDateTime, 12, 2 );
        return eZDateTime::create( $hour, $minute, $second,
                                          $month, $day, $year );
    }

    private function dataFilePath()
    {
        return $this->DataDir . '/awstats' . $this->Month
                              . $this->Year . '.' . $this->Site . '.txt';
    }

    public function dateList()
    {
        $dataFiles = eZDir::findSubitems( $this->DataDir, 'f' );
        $result = array();
        eZDebug::writeDebug( $dataFiles, __CLASS__ );
        foreach( $dataFiles as $file )
        {
            $tmpArray = explode( '.', $file );
            $dateString = str_replace( 'awstats', '', $tmpArray[0] );
            unset( $tmpArray[0] );  // remove awstats<date> part
            array_pop( $tmpArray ); // remove .txt part
            $siteString = implode( '.', $tmpArray );
            if ( $siteString == $this->Site
                    && strlen( $dateString ) == 6
                    && is_numeric( $dateString ) )
            {
                $month = substr( $dateString, 0, 2 );
                $year  = substr( $dateString, 2, 4 );
                $result[$year][] = $month;
            }
        }
        return $result;
    }
}



?>
