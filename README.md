# PaymentsAPI
Приложение представляет собой систему имитации платежей.

Для того чтобы сформировать сумму и назначение платежа, осуществите POST запрос по адресу http://yourhost/register  со следующими параметрами : {"summ":25580.75,"purpose":"ЖКХ"}.Примерные параметры уже определены в файле data.json.

Далее, в случае валидных данных платежа, придет в ответ ссылка с формой для осуществления платежа. GET параметр данной ссылки содержит ключ платежной сессии, однозначно идентифицирующий ее данные.

Введите номер карты. В случае если номер карты валиден (Проверка осуществляется по алгоритму Луна), то платеж будет успешно осуществлен и ключ платежной сессии будет удален;
