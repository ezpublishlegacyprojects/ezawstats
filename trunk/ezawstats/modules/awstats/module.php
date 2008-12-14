<?php

$Module = array( 'name' => 'eZAWStats',
                 'variable_params' => false );

$ViewList = array();

$ViewList['stats'] = array( 'script' => 'stats.php',
                            'default_navigation_part' => 'awstats',
                            'params' => array( 'Year', 'Month', 'Site' ),
                            'ui_context' => 'view' );

?>
