<?php 
namespace App\Models;

class PaymentsModel extends Model
{
	
	public function register(float $summ, string $purpose):array{
		$checked_summ = $this->checkSumm($summ);
		if(!$checked_summ['status'])return ['header'=>'HTTP/1.1 400 Bad Request','body'=>$checked_summ['message']];

		$session_key = $this->generateSessionKey();
		$this->connection->exec("INSERT INTO payments (summ,purpose,payment_key,is_implemented) VALUES ($summ,'$purpose','$session_key',0)");
		$url = 'http://paymentshost/payments/paymentform?sessionId='.$session_key;
		return ['header'=>'HTTP/1.1 201 Created','body'=>"Для совершения платежа перейдите по url: ".$url];


	}


	public function getPaymentData(string $payment_key) :array {
		$query = $this->connection->query("SELECT summ,purpose FROM payments WHERE payment_key = '$payment_key'");
		return ($query->rowCount()!==0) ?  ['header'=>'HTTP/1.1 200 OK','body'=>$this->fetchQueryRows($query)] : ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Неизвестный идентификатор платежа'];

	}

	public function sendPayment(string $payment_key,string $card_number):array{
	
		if(!$this->checkPaymentKey($payment_key))return ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Ошибка: Неизвестный идентификатор платежа'];
		if(!$this->checkCardNumber($card_number))return ['header'=>'HTTP/1.1 400 Bad Request','body'=>"Номер карты не валиден"];
		$this->connection->exec("UPDATE payments SET card_number = '$card_number',is_implemented = 1,payment_key = null WHERE payment_key = '$payment_key'");
		return ['header'=>'HTTP/1.1 200 OK','body'=>'Платеж успешно совершен'];
	}

	private function checkPaymentKey(string $payment_key):bool {
		$query = $this->connection->query("SELECT payment_key FROM payments WHERE payment_key = '$payment_key'");
		return ($query->rowCount()!==0) ? true:false;
	}

	private function checkCardNumber(string $card_number):bool{

		$summ = 0;
		$current_value = 0;
		$parity = strlen($card_number)%2;

		for($i = 0;$i<strlen($card_number);$i++){
			$current_value = intval($card_number[$i]);
			if(($i%2) == $parity ){
				$current_value*=2;
				if($current_value>9) $current_value-=9;

			}
			$summ+=$current_value;
			
		}


		return (($summ%10) === 0) ? true : false;
	}












	private function checkSumm(float $summ) : array{
		if($summ<=0)return ['message'=>'Некорректная сумма: сумма не может быть отрицательным или равным нулю числом','status'=>false];
		$summ_string = strval($summ);
		$penny_count = 0;
		$point_position = strpos($summ_string, '.');

		if($point_position){
			for($i = $point_position+1;$i<strlen($summ_string);$i++){
				$penny_count++;
			}
			if($penny_count>2)return ['message'=>'Некорректная сумма: допустимы только два знака после десятичной точки','status'=>false];
		}
		return ['message'=>'Валидация прошла успешно','status'=>true];
	}


	private function generateSessionKey(){
		$session_key = '';
		$length = rand(20,25);
		$delimiters_count = 3;
		$delimiter_position = intval($length/$delimiters_count);
		$current_delimiter_position = 0;
		$delimiter_couter = 0;

		for($i = 0;$i<$length;$i++){
			$symbol_group = rand(0,1);
			
			switch ($symbol_group) {
				case 0:
					$session_key.= chr(rand(97,122));
				break;
				
				case 1:
					$session_key.=rand(0,9);
				break;
			}
			if($delimiter_couter<($delimiters_count-1)){
				$current_delimiter_position++;
				if($current_delimiter_position === $delimiter_position){
				$session_key.='-';
				$current_delimiter_position = 0;
				$delimiter_couter++;
				
			}
			}

		}

		return $session_key;

	}



}







 ?>