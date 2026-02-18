<?php
require_once __DIR__ . '/Model.php';

class Sale extends Model {
    protected static $table = 'sales';
    protected static $primaryKey = 'id';

    public $id;
    public $product_id;
    public $quantity_sold;
    public $total_price;
    public $total_capital;
    public $profit;
    public $date_sold;

    public function __construct(array $data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
        public static function find($id)
    {
        $row = parent::find($id);
        return $row ? new self($row) : null;
    }
    
    public static function record($product_id, $quantity_sold, $total_price, $total_capital, $profit) {
        return parent::create([
            'product_id' => $product_id,
            'quantity_sold' => $quantity_sold,
            'total_price' => $total_price,
            'total_capital' => $total_capital,
            'profit' => $profit
        ]);
    }

    public function delete()
    {
        return parent::deleteById($this->id);
    }

    public static function all() {
        $results = parent::all();
        return $results ? array_map(fn($row) => new self($row), $results) : null;
    }
}
