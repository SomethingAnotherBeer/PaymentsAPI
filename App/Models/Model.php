<?php 
namespace App\Models;
use App\Includes;
abstract class Model
{
	protected $connection;

	public function __construct(){
		$this->connection = Includes\DB::getConnection();
	}

	protected function fetchQueryRows($query):array {
		$arr = [];
		while($row = $query->fetch(\PDO::FETCH_ASSOC)){
			$arr[] = $row;
		}
		return $arr;
	}
}




 ?>