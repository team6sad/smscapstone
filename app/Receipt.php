<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Receipt extends Model
{
	public $timestamps = false;
	protected $dates = ['date_claimed'];
}
