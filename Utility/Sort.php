<?php

namespace FanFerret\QuestionBundle\Utility;

/**
 * Contains utilities for sorting arrays.
 */
class Sort
{
    
    /**
     * Provides an identical interface to usort except
     * performs stable sorting.
     *
     * @param array $array
     *  The array to sort.  Will be sorted in place.
     * @param callable $func
     *  An optional callable to use to order the elements of
     *  \em array.  Defaults to \em null in which case default
     *  comparison shall be used.
     */
    public static function stable(array &$array, $func=null)
    {
        //  This can be done better/faster with
        //  mergesort
        if (is_null($func)) $func=function ($a, $b) {   return ($a<$b) ? -1 : (($a>$b) ? 1 : 0);    };
        $i=0;
        $arr=array_map(function ($obj) use (&$i) {  return (object)['index' => $i++,'element' => $obj]; },$array);
        usort($arr,function ($a, $b) use ($func) {
            $v=$func($a->element,$b->element);
            return ($v===0) ? ($a->index-$b->index) : $v;
        });
        $array=array_map(function ($obj) {  return $obj->element;   },$arr);
    }
    
}
