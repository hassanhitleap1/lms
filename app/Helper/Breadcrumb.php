<?php

namespace  App\Helper;

use Request;
use Illuminate\Support\Facades\App;
use Url;
class Breadcrumb {
    


    public static function getBreadcrum($title){

        $breadcrumb='<li ><a href="'.Url::to('/').'/'.App::getLocale().'">Home</a></li>';
        if(Request::segment(2) =='home' or Request::segment(2)==''){
            return  $breadcrumb;
        }else{
            if(Request::segment(2)=='domains'or Request::segment(2)=='pivots' or Request::segment(2)=='standards' or Request::segment(2)== 'competencies'){
                $breadcrumb .= '<li><a href="'.Url::to('/').'/'.App::getLocale().'/"> Standards</a></li>';
                $breadcrumb .= self::toggledLi();

                return $breadcrumb .= '<li><a href="'.Url::to('/').'/'.App::getLocale().'/'.Request::segment(2).'">'.$title.'</a></li>';
            }
                $breadcrumb .= '<li><a href="'.Url::to('/').'/'.App::getLocale().'/'.Request::segment(2).'">'.$title.'</a></li>';
                return  $breadcrumb;


        }

    }


    public static function toggledLi(){
        echo "<script>
                $(document).ready(function() {
                $('#li-standerd').addClass('toggled');
                $('#ul-standerd').css(\"display\",\"block\");
                });
            </script>";

    }

}