<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Shift extends Model
{
	public $timestamps = false;
	protected $dates = ['shift_date'];
}
