<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 9/9/2018
 * Time: 10:45 AM
 */

namespace App\Helper;


class SqlHelper
{
    public  static function printSql($query){
        dd(vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function($binding){
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray()));

    }

    public  static function printSqlDumper($query){
        dump(vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function($binding){
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray()));

    }

}