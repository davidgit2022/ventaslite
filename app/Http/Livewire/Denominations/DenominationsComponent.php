<?php

namespace App\Http\Livewire\Denominations;

use Livewire\Component;
use App\Models\Denomination;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class DenominationsComponent extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $pageTitle, $componentName, $selected_id, $image, $search, $type, $value;

    public function mount()
    {
        $this->pageTitle ='Listado';
        $this->componentName = 'Denominaciones';
        $this->selected_id = 0;
        $this->type='Elegir';
    }

    private $pagination = 5;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    public function render()
    {
        if (strlen($this->search)>0)
        {
            $denominations = Denomination::where('type','like','%' . $this->search . '%')->paginate($this->pagination);
        }
        else
        {
            $denominations = Denomination::orderBy('id', 'desc')->paginate($this->pagination);
        }
        return view('livewire.denominations.denominations-component',[
            'denominations' => $denominations
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function store()
    {
        $rules =  [
            'type' => 'required|not_in:Elegir',
            'value' => 'required|unique:denominations'
        ];

        $messages = [
            'type.required' => 'El tipo es requerido',
            'type.not_in' => 'Elige un valor distinto para Elegir',
            'value.required' => 'El valor es requerido',
            'value.unique' => 'Ya existe el valor',
        ];

        $this->validate($rules, $messages);

        $denomination = Denomination::create([
            'type' => $this->type,
            'value' => $this->value,
        ]);


        if ($this->image)
        {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('storage/denominations', $customFileName);
            $denomination->image = $customFileName;
            $denomination->save();
        }

        $this->resetUI();
        $this->emit('item-added','Denominación registrada');

    }

    public function edit($id)
    {
        $denomination = Denomination::find($id,['id','type','value','image']);

        $this->type = $denomination->type;
        $this->value = $denomination->value;
        $this->selected_id = $denomination->id;
        $this->image = null;

        $this->emit('show-modal','show-modal!');
    }

    public function update()
    {
        $rules =  [
            'type' => 'required|not_in:Elegir',
            'value' => "required|unique:denominations,value,{$this->selected_id}"
        ];

        $messages = [
            'type.required' => 'El tipo es requerido',
            'type.not_in' => 'Elige un valor distinto para Elegir',
            'value.required' => 'El valor es requerido',
            'value.unique' => 'Ya existe el valor',
        ];

        $this->validate($rules, $messages);

        $denomination = Denomination::find($this->selected_id);
        $denomination->update([
            'type' => $this->type,
            'value' => $this->value
        ]);

        if ($this->image)
        {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/denominations', $customFileName);
            $imageName = $denomination->image;

            $denomination->image = $customFileName;
            $denomination->save();

            if ($imageName != null)
            {
                if (file_exists('storage/denominations' . $imageName))
                {
                    unlink('storage/denominations' . $imageName);
                }
            }
        }

        $this->resetUI();
        $this->emit('item-updated','Denominación Actualizada');

    }

    protected $listeners = [
        'deleteRow' => 'destroy'
    ];

    public function destroy($id)
    {
        $denomination = Denomination::find($id);
        $imageName = $denomination->image; //imagen temporal
        $denomination->delete();

        if ($imageName != null)
        {
            unlink('storage/denominations/' . $imageName);
        }

        $this->resetUI();
        $this->emit('item-deleted','Denominación Eliminada');
    }

    public function resetUI()
    {
        $this->type = '';
        $this->value = '';
        $this->image = '';
        $this->search = '';
        $this->selected_id = 0;
    }
}
