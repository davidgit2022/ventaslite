<?php

namespace App\Http\Livewire\Pos;

use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use App\Models\SaleDetail;
use App\Models\SaleDatails;
use App\Models\Denomination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;


class PosComponent extends Component
{
    public $total, $itemsQuantity, $efectivo, $change;

    public function mount()
    {
        $this->efectivo = 0;
        $this->change = 0;
        $this->total = Cart::total();
        $this->itemsQuantity = Cart::count();
        $this->total = 10000;
        $this->itemsQuantity = 2;
    }

    public function render()
    {
        return view('livewire.pos.pos-component', [
            'denominations' => Denomination::orderBy('value', 'desc')->get(),
            'cart' => Cart::content()->sortBy('name')
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function ACash($value)
    {
        $this->efectivo += ($value == 0 ? $this->total : $value);
        $this->change = ($this->efectivo - $this->total);
    }

    protected $listeners = [
        'scan-code' => 'ScanCode',
        'removeItem' => 'removeItem',
        'clearCart' => 'clearCart',
        'saveSale' => 'saveSale'
    ];

    public function ScanCode($barcode, $cant = 1)
    {
        $product = Product::where('barcode', $barcode)->first();
        if ($product == null || empty($empty)) {
            
            $this->emit('scan-notfound', 'El producto no está registrado');
            dd($product);
        } else {
            if ($this->InCart($product->id)) {
                $this->increaseQty($product->id);
                return;
            }
            if ($product->stock < 1) {
                $this->emit('no-stock', 'Stock insuficiente :/');
            }

            Cart::add($product->id, $product->name, $cant, $product->price, $product->image);
            $this->total = Cart::total();

            $this->emit('scan-ok', 'Producto agregado');
        }
    }

    public function InCart($productId)
    {
        $exist = Cart::get($productId);
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }

    public function increaseQty($productId, $cant = 1)
    {
        $title = '';
        $product = Product::find($productId);
        $exist = Cart::get($productId);

        if ($exist) {
            $title = 'Cantidad actualizada';
        } else {
            $title = 'Producto agregado';
        }
        if ($exist) {
            if ($product->stock < ($cant + $exist->quantity)) {
                $this->emit('no-stock', 'Stock insuficiente :/');
                return;
            }
        }

        Cart::add($product->id, $product->name, $cant, $product->price,  $product->image);

        $this->total = Cart::total();
        $this->itemsQuantity = Cart::count();

        $this->emit('scan-ok', $title);
    }

    public function updateQty($productId, $cant=1)
    {
        $title = '';
        $product = Product::find($productId);
        $exist = Cart::get($productId);

        if ($exist)
        {
            $title = 'Cantidad actualizada';
        }
        else {
            $title = 'Producto agregado';
        }
        if ($exist)
        {
            if ($product->stock < $cant)
            {
                $this->emit('no-stock','Stock insuficiente :/');
                return;
            }

        }

        $this->removeItem($productId);

        if($cant > 0)
        {
            Cart::add($product->id, $product->name, $cant, $product->price,  $product->image);

            $this->total = Cart::total();
            $this->itemsQuantity = Cart::count();

            $this->emit('scan-ok', $title);
        }
        else {
            $title = 'La cantidad debe ser mayor a 0';
        }
    }

    public function revomeItem($productId)
    {
        Cart::remove($productId);

        $this->total = Cart::total();
        $this->itemsQuantity = Cart::count();

        $this->emit('scan-ok', 'Producto eliminado');
    }

    public function decreaseQty($productId)
    {
        $item = Cart::get($productId);
        Cart::remove($productId);

        $newQty = ($item->quantity) - 1;

        if ($newQty > 0)
        {
            Cart::add($item->id, $item->name, $newQty, $item->price,  $item->attributes[0]);
        }

        $this->total = Cart::total();
        $this->itemsQuantity = Cart::count();

        $this->emit('scan-ok', 'Cantidad actualizada');
    }

    public function clearCart()
    {
        Cart::clear();
        $this->efectivo = 0;
        $this->change = 0;
        $this->total = Cart::total();
        $this->itemsQuantity = Cart::count();

        $this->emit('scan-ok', 'Carrito vacio');
    }

    public function saveSale()
    {
        if ($this->total <= 0)
        {
            $this->emit('sale-error','AGREGA PRODUCTO A LA VENTA');
            return;
        }
        if ($this->efectivo <= 0)
        {
            $this->emit('sale-error','INGRESA EL EFECTIVO');
            return;
        }
        if ($this->total > $this->efectivo)
        {
            $this->emit('sale-error','EL EFECTIVO DEBE SER MAYOR O IGUAL AL TOTAL');
            return;
        }

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'total' => $this->total,
                'items' => $this->itemsQuantity,
                'cash' => $this->efectivo,
                'change' => $this->change,
                'user_id' => Auth()->user()->id,
            ]);

            if ($sale)
            {
                $items = Cart::content();
                foreach ($items as $item)
                {
                    SaleDetail::create([
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'product_id' => $item->id,
                        'sale' => $sale->id,
                    ]);

                    //Update stock

                    $product = Product::find($item->id);
                    $product->stock = $product->stock - $item->quantity;
                    $product->save();
                }

            }

            DB::commit();

                Cart::clear();
                $this->efectivo = 0;
                $this->change = 0;
                $this->total = Cart::count();
                $this->emit('sale-ok', 'Venta registrada con éxito');
                $this->emit('print-ticket', $sale->id);

        } catch (Exception $e)
        {
            DB::rollback();
            $this->emit('sale-error', $e->getMessage());
        }
    }

    public function printTicket($sale)
    {
        return Redirect::to("print://$sale->id");
    }
}
