<p>Вернуться в <a href='/catalog'>каталог</a></p>
<h1>Ваша корзина</h1>
<?php
$goods = Eshop::getItemsFromBasket();
if(!($goods instanceof Iterator)){
		echo BASKET_SHOW_ERROR;
		throw new Exception(BASKET_SHOW_FATAL_ERROR);
		exit;
	}
	if($goods instanceof EmptyIterator){
		echo BASKET_SHOW_EMPTY;
		exit;
	}
?>
<table>
<tr>
	<th>N п/п</th>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>Количество</th>
	<th>Удалить</th>
</tr>
<?php
$i = 0;
$sum = 0;
foreach($goods as $book):
?>
	<tr>
		<td><?=++$i?></td>	
		<td><?=$book->title?></td>
		<td><?=$book->author?></td>
		<td><?=$book->pubyear?></td>
		<td><?=$book->price?></td>
		<td><?=$book->quantity?></td>
		<td>
			<a href='/remove_item_from_basket?id=<?=$book->id?>'>Удалить</a>
		</td>
	</tr>
<?php
	$sum += $book->price * $book->quantity;
endforeach;
?>
</table>

<p>Всего товаров в корзине на сумму: <?=$sum?> руб.</p>

<div style="text-align:center">
	<input type="button" value="Оформить заказ!"
                      onclick="location.href='/create_order'" />
</div>