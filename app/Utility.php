<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Utility extends Model
{
	protected $table = 'utilities';
	protected $primaryKey = 'user_id';
	public $timestamps = false;
}
