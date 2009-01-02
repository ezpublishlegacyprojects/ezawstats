<?php

class eZAWStatsOperators
{
    function __construct()
    {
    }

    function operatorList()
    {
        return array( 'is_week_end', 'date_eq', 'data_multiplier' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'is_week_end' => array(),
                      'date_eq' => array( 'date' => array( 'type' => 'object',
                                                           'required' => true ) ),
                      'data_multiplier' => array( 'data_array' => array( 'type' => 'array',
                                                                         'required' => true ),
                                                  'key' => array( 'type' => 'string',
                                                                  'required' => true ),
                                                  'total' => array( 'type' => 'number',
                                                                    'required' => true ),
                                                  'max_value' => array( 'type' => 'integer',
                                                                        'required' => true ) ) );
    }

    private static function dataMultiplier( $dataArray, $key, $total, $maxValue )
    {
        $max = 0;
        foreach( $dataArray as $element )
        {
            if ( isset( $element[$key] ) )
            {
                $max = max( $element[$key], $max );
            }
        }
        return $total * $maxValue / $max;
    }

    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'data_multiplier':
            {
                $operatorValue = self::dataMultiplier( $namedParameters['data_array'], $namedParameters['key'],
                                                       $namedParameters['total'], $namedParameters['max_value'] );
            } break;
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
