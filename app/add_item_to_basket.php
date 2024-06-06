<?php
if(isset($_GET['id'])){
    if(Eshop::addItemToBasket($_GET['id']))
        echo BASKET_ITEM_ADD_OK;
    else
        echo BASKET_ITEM_ADD_ERROR;
}
header('Refresh: 3, url=catalog');