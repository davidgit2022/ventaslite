<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CategoriesComponent extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $name, $search, $image, $selected_id, $pageTitle, $componentName;
    private $pagination = 5;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Categorías';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if (strlen($this->search)>0)
        {
            $categories = Category::where('name','like','%' . $this->search . '%')->paginate($this->pagination);
        }
        else
        {
            $categories = Category::orderBy('id', 'desc')->paginate($this->pagination);
        }
        return view('livewire.category.categories-component',[
            'categories' => $categories
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }


    public function store()
    {
        $rules =  [
            'name' => 'required|unique:categories|min:3'
        ];

        $messages = [
            'name.required' => 'Nombre de la categoría es requerido',
            'name.unique' => 'Ya existe el nombre de la categoría',
            'name.min' => 'El nombre de la categoría debe tener al menos 3 caracteres',
        ];

        $this->validate($rules, $messages);

        $category = Category::create([
            'name' => $this->name
        ]);


        $customFileName;

        if ($this->image)
        {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('storage/categories', $customFileName);
            $category->image = $customFileName;
            $category->save();
        }

        $this->resetUI();
        $this->emit('category-added','Categoría registrada');

    }

    public function edit($id)
    {
        $category = Category::find($id,['id','name','image']);

        $this->name = $category->name;
        $this->selected_id = $category->id;
        $this->image = null;

        $this->emit('show-modal','show-modal!');
    }

    public function update()
    {
        $rules = [
            'name' => "required|min:3|unique:categories,name, {$this->selected_id}"
        ];

        $messages = [
            'name.required' => 'Nombre de la categoría es requerido',
            'name.unique' => 'Ya existe el nombre de la categoría',
            'name.min' => 'El nombre de la categoría debe tener al menos 3 caracteres',
        ];

        $this->validate($rules, $messages);

        $category = Category::find($this->selected_id);
        $category->update([
            'name' => $this->name
        ]);

        if ($this->image)
        {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/categories', $customFileName);
            $imageName = $category->image;

            $category->image = $customFileName;
            $category->save();

            if ($imageName != null)
            {
                if (file_exists('storage/categories' . $imageName))
                {
                    unlink('storage/categories' . $imageName);
                }
            }
        }

        $this->resetUI();
        $this->emit('category-updated','Categoría Actualizada');

    }

    protected $listeners = [
        'deleteRow' => 'destroy'
    ];

    public function destroy($id)
    {
        $category = Category::find($id);
        $imageName = $category->image; //imagen temporal
        $category->delete();

        if ($imageName != null)
        {
            unlink('storage/categories/' . $imageName);
        }

        $this->resetUI();
        $this->emit('category-deleted','Categoría Eliminada');
    }

    public function resetUI()
    {
        $this->name = '';
        $this->image = '';
        $this->search = '';
        $this->selected_id = 0;
    }
}
