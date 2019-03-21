<?php

namespace App;


use Eloquent;
use Illuminate\Support\Facades\Auth;

class Notification extends Eloquent {
    const READ=1;
    const UNREAD=0;
    const TYPE_add_shopping_cart='add_shopping_cart';
    const TYPE_person_add='person_add';
    const TYPE_mode_edit='mode_edit';
    const TYPE_comment='comment';
    const TYPE_cached='cached';
    const TYPE_settings='settings';
    const ICON_COLOR_bg_light_green='bg-light-green';
    const ICON_COLOR_bg_cyan='bg-cyan';
    const ICON_COLOR_bg_red='bg-red';
    const ICON_COLOR_bg_orange='bg-orange';
    const ICON_COLOR_bg_blue_grey='bg-blue-grey';
    const ICON_COLOR_bg_purple='bg-purple';

    protected $table = 'notifications';
    public $timestamps = true;
    protected $primaryKey = 'notification_id';

//notification_id	message	link	date	type	color_type	mss_read	user_id
    public static function getLastNotifications(){
       return self::where('user_id',Auth::user()->userid);

    }


    public function byUser(){
        return $this->hasOne(Users::class,'userid','by_user');
    }
}