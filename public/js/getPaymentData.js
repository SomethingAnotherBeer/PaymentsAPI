let get_payment_data_request = new XMLHttpRequest;
let url = 'http://paymentshost/payments/getpaymentdata';
let full_get_param = String(window.location).slice(String(window.location).indexOf('?')+1);
let get_param_value = full_get_param.slice(full_get_param.indexOf('=')+1);
let view_purpose = document.getElementById('purpose');
let view_summ = document.getElementById('summ');


	let request_obj = {
		sessionId:get_param_value
	}
	get_payment_data_request.onreadystatechange = function(){
		if(get_payment_data_request.readyState === 4){
			let response = JSON.parse(get_payment_data_request.response)[0];

			if(get_payment_data_request.status === 200){
				view_purpose.innerText = 'Назначение платежа: '+response.purpose;
				view_summ.innerText = 'Сумма платежа: '+response.summ;

			}
			else if(get_payment_data_request.status === 400){
				document.querySelector('body').innerHTML = 'Валидные платежи не найдены';
			}
		}
	}
	get_payment_data_request.open('POST',url);
	get_payment_data_request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	get_payment_data_request.send(JSON.stringify(request_obj));



