<?php

namespace App\Http\Livewire\Search;

use App\Models\Product;
use Livewire\Component;

class SearchComponent extends Component
{
    public $search;

    public function render()
    {
        $product = Product::where('barcode', $barcode)->first()
        ->when($this->search, function($query, $search){
            return $query->where('products.barcode', 'like', '%'. $search . '%');
        });
        return view('livewire.search.search-component',[
            'product' => $product
        ]);
    }


}
