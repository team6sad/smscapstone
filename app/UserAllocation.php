<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UserAllocation extends Model
{
	protected $table = 'user_allocation';
	public $timestamps = false;
	protected $dates = ['date_claimed'];
}
