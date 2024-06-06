<?php
class Book
{
    public $id;
    public $title;
    public $author;
    public $price;
    public $pubyear;
    public $quantity;
    
    public function __construct(array $item=[])
    {
        if($item){
            $this->title = $item['title'];
            $this->author = $item['author'];
            $this->price = $item['price'];
            $this->pubyear = $item['pubyear'];
            $this->id = $item['id'] ?? null;
            $this->quantity = $item['quantity'] ?? null;
        }
    }
}