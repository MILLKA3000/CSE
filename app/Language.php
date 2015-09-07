<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

	protected $guarded  = array('id');

	/**
	 * The rules for email field, automatic validation.
	 *
	 * @var array
	*/
	private $rules = array(
			'name' => 'required|min:2',
			'lang_code' => 'required|min:2'
	);
}