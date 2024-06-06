<?php
class Eshop{
    private static $db = null;

    public static function init(array $db)
    {
        self::$db = new PDO("pgsql:host=" . DB['HOST'] . ";port=" . DB['PORT'] . ";dbname=" . DB['NAME'], DB['USER'], DB['PASS']);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }

    public static function getUserByLogin(string $login): ?User {
        $sql = "SELECT * FROM spGetUser(:login)";
        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row);
        } else {
            return null;
        }
    }
    public static function addItemToCatalog(Book $item): bool{
        self::cleanItem($item);
        $params = "{$item->title}, {$item->author}, {$item->price}, {$item->pubyear}";

        $sql = "SELECT spAddItemToCatalog($params)";
        if(self::$db->prepare($sql))
            return true;
        return false;
    }

    public static function getItemsFromCatalog(): iterable{
        $sql = "SELECT * FROM spGetCatalog()";
        $result = self::$db->query($sql, PDO::FETCH_ASSOC); 
        if(!$result)
            return new EmptyIterator();

        $items = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $items[] = new Book($row); 
        }

        return new ArrayIterator($items);
    }
    private static function cleanItem(Book $item)
    {
        $item->title = Cleaner::str2db($item->title, self::$db);
        $item->author = Cleaner::str2db($item->author, self::$db);
        $item->price = Cleaner::uint($item->price);
        $item->pubyear = Cleaner::uint($item->pubyear);
    }
    private static function cleanUser(User $item)
    {
        $item->login = Cleaner::str2db($item->login, self::$db);
        $item->password = Cleaner::str2db($item->password, self::$db);
        $item->email = Cleaner::str2db($item->email, self::$db);
    }

    public static function countItemsInBasket(){
        return Basket::size();
    }

    public static function addItemToBasket($id){
       $id = Cleaner::uint($id);
       if(!$id){
            return false;
       }
       Basket::add($id); 
       return true; 
    }

    public static function getItemsFromBasket(): iterable{
       if(!self::countItemsInBasket())
        return new EmptyIterator();
        
       $keys = array_keys(iterator_to_array(Basket::get()));
       $ids = implode(',', $keys);
       $sql = "SELECT * FROM spGetItemsForBasket('$ids')";
       $stmt = self::$db->query($sql);
       $books = $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
       if(!count($books))
        return new EmptyIterator();
       foreach($books as $book){
        $book->quantity = Basket::quantity($book->id);
       } 
       return new ArrayIterator($books);
    }

    public static function removeItemFromBasket($id){
       $id = Cleaner::uint($id);
       if(!$id){
            return false;
       }
       Basket::remove($id); 
       return true; 
    }

    public static function saveOrder(Order $order): bool{
        self::cleanOrder($order);
        $order->id = Cleaner::str2db(Basket::getOrderId(), self::$db);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$db->beginTransaction();
        try{
            $params = "{$order->id}, {$order->customer}, {$order->email}, {$order->phone}, {$order->address}";
            $sql = "SELECT spSaveOrder($params)";
            self::$db->exec($sql);
            foreach(Basket::get() as $itemId => $quantity){
                $params = "{$order->id}, $itemId, $quantity";
                $sql = "SELECT spSaveOrderedItems($params)";
                self::$db->exec($sql);
            }
            self::$db->commit();
        }catch(PDOException $e){
            self::$db->rollBack();
            trigger_error($e->getMessage());
            return false;
        }
        Basket::clear();
        return true;
    }
    
    private static function cleanOrder(Order $item)
    {
        $item->customer = Cleaner::str2db($item->customer, self::$db);
        $item->phone = Cleaner::str2db($item->phone, self::$db);
        $item->email = Cleaner::str2db($item->email, self::$db);
        $item->address = Cleaner::str2db($item->address, self::$db);
    }
    
    public static function getOrders(): iterable{
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM spGetOrders()";
        $stmt = self::$db->query($sql);
        $orders = $stmt->fetchAll(PDO::FETCH_CLASS, 'Order');
        $stmt->closeCursor();
        if(!count($orders))
            return new EmptyIterator();
        
        unset($stmt);
        foreach($orders as $order){
            $sql = "SELECT * FROM spGetOrderedItems('{$order->id}')";
            $stmt = self::$db->query($sql);
            $order->items = $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
        }
        return new ArrayIterator($orders);
    }

    public static function saveUser(User $item): bool {
        self::cleanUser($item);
    
        $params = "{$item->login}, {$item->password}, {$item->email}";
        $sql = "SELECT spSaveUser($params)";
        $stmt = self::$db->prepare($sql);
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }
        
        return true;
    }    
    
}