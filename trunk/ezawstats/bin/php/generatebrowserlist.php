#! /usr/bin/env php
<?php
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => 'Convert browser.pm and robots.pm files of AWStats into .ini file',
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );
$script->startup();
$options = $script->getOptions( '[browser:][robot:]', '',
                                array( 'browser' => 'Path to browser.pm file',
                                       'robot' => 'Path to robots.pm file' ) );
$script->initialize();

if ( $options['browser'] == '' || $options['robot'] == '' )
{
    $cli->error( 'No file given' );
    $script->shutdown( 1 );
}

if ( !file_exists( $options['robot'] ) || !is_readable( $options['robot'] ) )
{
    $cli->error( $options['robot'] . ' does not exist or is not readable' );
    $script->shutdown( 2 );
}


if ( !file_exists( $options['browser'] ) || !is_readable( $options['browser'] ) )
{
    $cli->error( $options['browser'] . ' does not exist or is not readable' );
    $script->shutdown( 2 );
}


function parsePerlHash( $lineArray, $hashName )
{
    $inHash = false;
    $resultList = array();
    foreach( $lineArray as $line )
    {
        if ( !$inHash && strpos( $line, $hashName ) !== false )
        {
            // start of hash
            $inHash = true;
            continue;
        }
        elseif ( !$inHash )
        {
            continue;
        }
        if ( $inHash && strpos( $line, ');' ) !== false )
        {
            // end of hash
            break;
        }
        $line = trim( preg_replace( '/#.*/', '', $line ), " \t\n\r\0\x0b," );
        if ( $line == '' )
        {
            // $line was only a comment
            continue;
        }
        list( $key, $value ) = explode( ',', $line );
        $key = trim( $key, "'" );
        $resultList[$key] = str_replace( "\\", "", trim( $value, " \t\n\r\0\x0b'" ) );
    }
    return $resultList;
}

$fileArray = file( $options['browser'] );
$browserList = parsePerlHash( $fileArray, '%BrowsersHashIDLib' );

$fileArray = file( $options['robot'] );
$robotList = parsePerlHash( $fileArray, '%RobotsHashIDLib' );

$baseExtension = eZExtension::baseDirectory() . '/ezawstats/settings/';

$ini = eZINI::instance( 'browser.ini.append.php', $baseExtension );
$ini->setGroups( array( 'BrowserSettings' => array( 'BrowserList' => $browserList ),
                        'RobotSettings'   => array( 'RobotList' => $robotList ) ) );
$ini->save();


$script->shutdown();
?>
