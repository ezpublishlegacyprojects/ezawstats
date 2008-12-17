<?php

class eZAWStatsOperators
{
    function __construct()
    {
    }

    function operatorList()
    {
        return array( 'is_week_end', 'date_eq' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'is_week_end' => array(),
                      'date_eq' => array( 'date' => array( 'type' => 'object',
                                                           'required' => true ) ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'is_week_end':
            {
                $timestamp = $operatorValue->attribute( 'timestamp' );
                $dayNumber = date( 'w', $timestamp );
                // 0 is sunday
                // 6 is saturday
                $operatorValue = in_array( $dayNumber, array( 0, 6 ) );
            } break;
            case 'date_eq':
            {
                $date = $namedParameters['date'];
                if ( $date->attribute( 'day' ) == $operatorValue->attribute( 'day' )
                        && $date->attribute( 'month' ) == $operatorValue->attribute( 'month' )
                        && $date->attribute( 'year' ) == $operatorValue->attribute( 'year' ) )
                {
                    $operatorValue = true;
                }
                else
                {
                    $operatorValue = false;
                }
            }break;
        }
    }
}

?>
