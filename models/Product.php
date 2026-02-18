<?php
require_once __DIR__ . '/Model.php';

class Product extends Model
{
    protected static $table = 'products';
    protected static $primaryKey = 'id';

    public $id;
    public $name;
    public $category;
    public $unit;
    public $quantity_in_stock;
    public $price;
    public $capital_price;
    public $total_capital;
    public $total_income;
    public $reorder_level;
    public $status;
    public $created_at;
    public $updated_at;
    private static $STATUS_IN_STOCK = 'In Stock';
    private static $STATUS_LOW_STOCK = 'Low Stock';
    private static $STATUS_OUT_OF_STOCK = 'Out of Stock';

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public static function setConnection($conn)
    {
        parent::setConnection($conn);
    }

    public static function all()
    {
        $results = parent::all();
        return $results ? array_map(fn($row) => new self($row), $results) : null;
    }

    public static function find($id)
    {
        $row = parent::find($id);
        return $row ? new self($row) : null;
    }

    public function update(array $data)
    {
        $result = parent::updateById($this->id, $data);
        if ($result) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
            return $this;
        }
        return false;
    }

    public function delete()
    {
        return parent::deleteById($this->id);
    }



    /**
     * Recompute and persist status based on current quantity_in_stock & reorder_level.
     */
    public function refreshStatus()
    {
        // ensure numeric values
        $qty = (int) $this->quantity_in_stock;
        $reorder = (int) $this->reorder_level;

        $newStatus = self::$STATUS_IN_STOCK;

        if ($qty <= 0) {
            $newStatus = self::$STATUS_OUT_OF_STOCK;
        } elseif ($qty <= $reorder) {
            $newStatus = self::$STATUS_LOW_STOCK;
        }

        // Only update if different
        if (($this->status ?? '') !== $newStatus) {
            // update local property so subsequent logic sees it
            $this->status = $newStatus;
            // persist change
            parent::updateById($this->id, ['status' => $newStatus]);
        }
    }

    /**
     * Add stock and update totals (keeps status in sync)
     */
    public function addStock($addedQuantity)
    {
        $addedQuantity = (int) $addedQuantity;
        if ($addedQuantity <= 0)
            return false;

        $newStock = (int) $this->quantity_in_stock + $addedQuantity;
        $addedCapital = (float) $this->capital_price * $addedQuantity;
        $newTotalCapital = (float) $this->total_capital + $addedCapital;

        // update DB and object
        $result = parent::updateById($this->id, [
            'quantity_in_stock' => $newStock,
            'total_capital' => $newTotalCapital
        ]);

        if ($result) {
            // reflect on this instance
            $this->quantity_in_stock = $newStock;
            $this->total_capital = $newTotalCapital;
            // refresh status after change
            $this->refreshStatus();
            return true;
        }

        return false;
    }

    /**
     * Sell a product: reduce stock, update totals, record sale; then refresh status
     */
    public function sell($quantitySold)
    {
        $quantitySold = (int) $quantitySold;
        if ($quantitySold <= 0 || $quantitySold > (int) $this->quantity_in_stock) {
            return false;
        }

        $newStock = (int) $this->quantity_in_stock - $quantitySold;
        $income = ((float) $this->price - (float) $this->capital_price) * $quantitySold;
        $newTotalIncome = (float) $this->total_income + $income;

        // update DB and object
        $result = parent::updateById($this->id, [
            'quantity_in_stock' => $newStock,
            'total_income' => $newTotalIncome
        ]);

        if ($result) {
            $this->quantity_in_stock = $newStock;
            $this->total_income = $newTotalIncome;

            // record sale
            require_once __DIR__ . '/Sales.php';
            Sale::setConnection(self::$conn);
            $total_price = (float) $this->price * $quantitySold;
            $total_capital = (float) $this->capital_price * $quantitySold;
            $profit = $total_price - $total_capital;
            Sale::record($this->id, $quantitySold, $total_price, $total_capital, $profit);

            // refresh status
            $this->refreshStatus();

            return true;
        }

        return false;
    }

    public static function createProduct(array $data)
    {
        $id = parent::create($data); // uses the protected Model::create()
        if ($id) {
            return new self(array_merge(['id' => $id], $data));
        }
        return false;
    }


}
