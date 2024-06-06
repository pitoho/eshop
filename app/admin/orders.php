<?php
$orders = Eshop::getOrders();
if(!($orders instanceof Iterator)){
	echo ORDERS_SHOW_ERROR;
	throw new Exception(ORDERS_SHOW_FATAL_ERROR);
	exit;
}
if($orders instanceof EmptyIterator){
	echo ORDERS_SHOW_EMPTY;
}
?>
<h1>Поступившие заказы:</h1>
<a href='/admin'>Назад в админку</a>
<?php
foreach($orders as $order):
?>
<hr>
<h2>Заказ номер: <?=$order->id?></h2>
<p><b>Заказчик</b>: <?=$order->customer?></p>
<p><b>Email</b>: <?=$order->email?></p>
<p><b>Телефон</b>: <?=$order->phone?></p>
<p><b>Адрес доставки</b>: <?=$order->address?></p>
<p><b>Дата размещения заказа</b>: <?=date('d-m-Y H:i:s', $order->date)?></p>

<h3>Купленные товары:</h3>
<table>
<tr>
	<th>N п/п</th>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>Количество</th>
</tr>
<?php
$i = 0;
$sum = 0;
foreach($order->items as $book):
?>
	<tr>
		<td><?=++$i?></td>	
		<td><?=$book->title?></td>
		<td><?=$book->author?></td>
		<td><?=$book->pubyear?></td>
		<td><?=$book->price?></td>
		<td><?=$book->quantity?></td>
	</tr>
<?php
	$sum += $book->price * $book->quantity;
endforeach;
?>
</table>

<p>Всего товаров в корзине на сумму: <?=$sum?> руб.</p>
<?php
endforeach; // big foreach
?>