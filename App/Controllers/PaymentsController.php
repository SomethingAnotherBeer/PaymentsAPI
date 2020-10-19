<?php 
namespace App\Controllers;
use App\View;
use App\Errors;
use App\Models;

class PaymentsController extends MainController
{
	

	public function __construct(){
		parent::__construct();
		$this->model = new Models\PaymentsModel(); 
	}

	public function register(){
		if($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['summ','purpose'])) throw new Errors\BadRequestException('Ошибка: ожидались только параметры purpose и summ');
		$data = $this->model->register($this->request_post_data['summ'],$this->request_post_data['purpose']);
		$this->createResponse($data);
	}

	public function getPaymentForm(){
		if($this->request_method !== 'GET')return 0;
		if(!isset($_GET['sessionId']))return 0;
		
		View\View::renderPage('client','html');
	}

	public function getPaymentData(){
		if($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['sessionId'])) throw new Errors\BadRequestException('Ошибка: ожидался только параметр sessionId');
		$data = $this->model->getPaymentData($this->request_post_data['sessionId']);
		$this->createResponse($data);
	}

	public function sendPayment(){
		if($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['sessionId','card_number'])) throw new Errors\BadRequestException('Ошибка: ожидались параметры sessionId и card_number');
		$data = $this->model->sendPayment($this->request_post_data['sessionId'],$this->request_post_data['card_number']);
		$this->createResponse($data);

	}


}





 ?>