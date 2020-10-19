let send_payment_data_request = new XMLHttpRequest;
let card_value = document.getElementById('card_number');
let send_payment_button = document.getElementById('send-payment');
let send_payment_url = 'http://paymentshost/payments/sendpayment';

send_payment_button.addEventListener('click',()=>{
	let request_obj = {
		sessionId:get_param_value,
		card_number:card_value.value
	}
	send_payment_data_request.onreadystatechange = function(){
		if(send_payment_data_request.readyState === 4){
			let response = send_payment_data_request.response;
			alert(response);
			if (send_payment_data_request.status===200){
				send_payment_button.disabled = true;
				send_payment_button.style.opacity = '0.5';
			}
			
		}
	}
	send_payment_data_request.open("POST",send_payment_url);
	send_payment_data_request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	send_payment_data_request.send(JSON.stringify(request_obj));

})