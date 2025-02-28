<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImporter implements ToCollection, WithHeadingRow
{
    protected $additionalData = [];

    protected $customImportData = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Find or create category
            $category = Category::firstOrCreate(
                ['name' => $row['categoria']]
            );

            // Create product
            Product::create([
                'bar_code' => $row['codigo_de_barra'],
                'name' => $row['nombre'],
                'purchase_price' => $row['precio_compra'],
                'sales_price' => $row['precio_venta'],
                'stock' => $row['stock'],
                'stock_minimum' => $row['stock_minimo'],
                'unit_measure' => $row['unidad_medida'],
                'category_id' => $category->id,
                'status' => $row['estado'] ?? true,
                'expiration' => \Carbon\Carbon::parse($row['vencimiento']),
            ]);
        }
    }

    public function setAdditionalData($data)
    {
        $this->additionalData = $data;
    }

    public function setCustomImportData($data)
    {
        $this->customImportData = $data;
    }
}
