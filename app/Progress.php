<?php

namespace App;


use Eloquent;
class Progress extends Eloquent {

	protected $table = 'progress';
	public $timestamps = true;



	protected $dates = ['deleted_at'];

}