<?php
error_reporting(E_ALL);

const CATALOG_ITEM_ADD_OK = 'Товар добавлен в каталог';
const CATALOG_ITEM_ADD_ERROR = 'Ошибка при добавлении товара в каталог';
const CATALOG_SHOW_ERROR = 'Ошибка при показе каталога';
const CATALOG_SHOW_FATAL_ERROR = 'Бага при показе каталога!!!';
const BASKET_ITEM_ADD_OK = 'Товар добавлен в корзину';
const BASKET_ITEM_ADD_ERROR = 'Ошибка при добавлении товара в корзину';
const BASKET_SHOW_ERROR = 'Ошибка при показе корзины';
const BASKET_SHOW_EMPTY = 'Ваша корзина пуста';
const BASKET_SHOW_FATAL_ERROR = 'Бага при показе корзины!!!';
const BASKET_ITEM_REMOVED_OK = 'Товар удалён из корзины';
const BASKET_ITEM_REMOVED_ERROR = 'Ошибка при удалении товара из корзины';
const ORDER_SAVED_OK = 'Заказ успешно создан';
const ORDER_SAVED_ERROR = 'Ошибка при создании заказа';
const ORDERS_SHOW_ERROR = 'Ошибка при показе заказов';
const ORDERS_SHOW_FATAL_ERROR = 'Бага при показе заказов!!!';
const ORDERS_SHOW_EMPTY = 'Заказов пока нет';
const SAVE_USER_OK = 'Пользователь успешно добавлен';
const SAVE_USER_ERROR = 'Ошибка добавления пользователя';
const FIND_USER_ERROR = 'Пользователь с таким именем не найден, попробуйте снова';
const LOGIN_OK = 'Вы вошли в систему';
const PASSWORD_ERROR = 'Ошибка пароля, попроюуйте заново';

require_once 'core/init.php';
require_once 'app/__header.php';
require_once 'app/__router.php';
require_once 'app/__footer.php';
