<?php

namespace App\Http\Livewire\Search;

use App\Models\Product;
use Livewire\Component;

class SearchComponent extends Component
{
    public $search;
    

    public function render()
    {
        return view('livewire.search.search-component');
    }


}
