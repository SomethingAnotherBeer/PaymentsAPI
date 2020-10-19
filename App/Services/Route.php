<?php 
namespace App\Services;
use App\Errors;
class Route
{
	private $uri;
	private $uri_arr = [
		'/register'=>['controller'=>'Payments','action'=>'register'],
		'/payments/paymentform'=>['controller'=>'Payments','action'=>'getPaymentForm'],
		'/payments/getpaymentdata'=>['controller'=>'Payments','action'=>'getPaymentData'],
		'/payments/sendpayment'=>['controller'=>'Payments','action'=>'sendPayment']
	];

	public function __construct(string $uri){
		$this->uri = $this->prepareGet(strtolower($uri));

	}

	public function getUriParams(){
		$this->checkUri();
		return $this->getPreparedUriParams();

	}



	private function checkUri():bool{
		if(isset($this->uri_arr[$this->uri])){
			return true;
		}
		else{
			throw new Errors\NotFoundException();
			
		}
	}

	private function getPreparedUriParams():array{
		$uri_params = $this->uri_arr[$this->uri];
		$controller = 'App\\Controllers\\'.$uri_params['controller'].'Controller';
		$action = $uri_params['action'];
		return ['controller'=>$controller,'action'=>$action];
		

	}

	


	private function prepareGet(string $uri):string{
		$new_uri = null;
		for($i = 0;$i<strlen($uri);$i++){
			if ($uri[$i]==='?')break;
			$new_uri.=$uri[$i];
		}
		return $new_uri;
	}
}





 ?>