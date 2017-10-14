<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Budget extends Model
{
	public $timestamps = false;
	protected $dates = ['budget_date'];
}
