<?php
if(isset($_GET['id'])){
    if(Eshop::removeItemFromBasket($_GET['id']))
        echo BASKET_ITEM_REMOVED_OK;
    else
        echo BASKET_ITEM_REMOVED_ERROR;
}
header('Refresh: 3, url=basket');
