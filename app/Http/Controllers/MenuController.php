<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Menu;
use App\Models\MenuType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = Menu::select(['id', 'title','type', 'price_before', 'price', 'photo'])->orderBy('created_at', 'DESC');
            return DataTables::eloquent($items)
                ->addColumn('photo', function ($item) {
                    $path = storage_path('/app/public/' . $item->photo);
                    $imgSrc = file_exists($path) ? asset('storage/' . $item->photo) : asset('assets/img/avatars/menu.png');
                    return '<img width="30" height="30" src=' . $imgSrc . '>';
                })
                ->addColumn('actions', function ($item) {
                    return '<div class="btn-group" role="group" aria-label="First group">
                              <a title="Edit" href="' . route('menu-builder.edit', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bx-edit"></i>
                              </a>

                               <a title="Show" href="' . route('menu-builder.show', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bxs-notepad"></i>
                              </a>

                               <a
                               title="Delete"
                               name="' . route('menu-builder.destroy', $item->id) . '"
                               onclick="confirmDelete(this.name)"
                               type="button"
                               class="btn btn-outline-danger">
                                <i class="tf-icons bx bx-trash"></i>
                              </a>


                            </div>';
                })
                ->rawColumns(['photo', 'actions'])
                ->toJson();
        }

        // If it's not an AJAX request
        return view('components.list', [
            'title' => 'Menu List',
            'pageID' => 'menu101',
            'data_route' => 'menu-builder.index',
            'columns' => ['Title', 'Type', 'Previous Price', 'Price', 'Photo', 'Actions'],
            'columns_for_datatable' => [
                [
                    "data" => "title",
                    "name" => "title",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "type",
                    "name" => "type",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "price_before",
                    "name" => "price_before",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "price",
                    "name" => "price",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "photo",
                    "name" => "photo",
                    "orderable" => "false",
                    "sortable" => "false",
                ],
                [
                    "data" => "actions",
                    "name" => "actions",
                    "orderable" => "false",
                    "sortable" => "false",
                ],
            ],
            'tools' => [
                [
                    'name' => 'Create',
                    'icon' => 'tf-icons bx bx-plus',
                    'class' => 'btn btn-icon btn-primary',
                    'url' => route('menu-builder.create')
                ],

            ]
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('components.create', [
            'title' => 'Create Menu',
            'method' => 'POST',
            'route' => 'menu-builder.store',
            'fields' => [
                [
                    'label' => 'Menu Title',
                    'placeholder' => 'Enter Menu Title',
                    'icon' => 'bx bx-food-menu',
                    'width' => '6',
                    'name' => 'title',
                    'type' => 'text',
                    'required' => true
                ],
                [
                    'label' => 'Menu Type',
                    'placeholder' => 'Select Menu Type',
                    'icon' => 'bx bx-food-menu',
                    'width' => '6',
                    'name' => 'type',
                    'type' => 'select',
                    'required' => true,
                    'select_type' => 'single',
                    'options' => MenuType::select(['title as value','title as text'])->get()->toArray(),
                ],
                [
                    'label' => 'Previous Price(If you want to make offer)',
                    'placeholder' => '200',
                    'icon' => 'bx bx-purchase-tag',
                    'width' => '6',
                    'name' => 'price_before',
                    'type' => 'number',
                    'pattern' => '[0-9]{10}',
                    'required' => true
                ],
                [
                    'label' => 'Price',
                    'placeholder' => '100',
                    'icon' => 'bx bx-purchase-tag-alt',
                    'width' => '6',
                    'name' => 'price',
                    'type' => 'number',
                    'pattern' => '[0-9]{10}',
                    'required' => true
                ],
                [
                    'label' => 'Photo',
                    'placeholder' => 'Upload Menu Photo',
                    'icon' => 'bx bx-cloud-upload',
                    'width' => '6',
                    'name' => 'photo',
                    'type' => 'file',
                    'file_type' => 'image',
                    'required' => false
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255|unique:menus',
                'type' => 'required',
                'price_before' => 'required|min:1',
                'price' => 'required|min:1',
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:512',
            ]);

            $data = $request->except(['_token', '_method']);

            $image_path = $request->file('photo')->store('menu', 'public');
            $data['photo'] = $image_path;
            Menu::create($data);

            return redirect()->route('menu-builder.index')->with('success', 'New menu created successfully!');
        } catch (Exception $exp) {
            return redirect()->route('menu-builder.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $obj = Menu::select(
            [
                'title',
                'type',
                'price_before',
                'price',
                'photo',
                'created_at'
            ]
        )->find($id);
        return view('components.show', ['title' => 'Menu Details', 'obj' => $obj]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $obj = Menu::find($id);

        return view('components.edit', [
                'obj' => $obj,
                'title' => 'Edit Menu',
                'method' => 'POST',
                'route' => 'menu-builder.update',
                'fields' => [
                    [
                        'label' => 'Menu Title',
                        'placeholder' => 'Enter Menu Title',
                        'icon' => 'bx bx-food-menu',
                        'width' => '6',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => true
                    ],
                    [
                        'label' => 'Menu Type',
                        'placeholder' => 'Select Menu Type',
                        'icon' => 'bx bx-food-menu',
                        'width' => '6',
                        'name' => 'type',
                        'type' => 'select',
                        'required' => true,
                        'select_type' => 'single',
                        'options' => MenuType::select(['title as value','title as text'])->get()->toArray(),
                    ],
                    [
                        'label' => 'Previous Price(If you want to make offer)',
                        'placeholder' => '200',
                        'icon' => 'bx bx-purchase-tag',
                        'width' => '6',
                        'name' => 'price_before',
                        'type' => 'text',
                        'required' => true
                    ],
                    [
                        'label' => 'Price',
                        'placeholder' => '100',
                        'icon' => 'bx bx-purchase-tag-alt',
                        'width' => '6',
                        'name' => 'price',
                        'type' => 'text',
                        'required' => true
                    ],
                    [
                        'label' => 'Photo',
                        'placeholder' => 'Upload Menu Photo',
                        'icon' => 'bx bx-cloud-upload',
                        'width' => '6',
                        'name' => 'photo',
                        'type' => 'file',
                        'file_type' => 'image',
                        'required' => false
                    ],
                ],
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            $validated = $request->validate([
                'title' => 'required|max:255|unique:menus,title,' . $id,
                'type' => 'required',
                'price_before' => 'required|min:1',
                'price' => 'required|min:1',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:512',
            ]);

            $data = $request->except(['_token', '_method']);


            $obj = Menu::where('id', $id)->first();

            if ($request->file('photo')) {
                if ($obj->photo && file_exists(storage_path('app/public/' . $obj->photo))) {
                    unlink(storage_path('/app/public/' . $obj->photo));

                    $image_path = $request->file('photo')->store('menu', 'public');
                    $data['photo'] = $image_path;
                }
            }

            $obj->fill($data)->save();

            return redirect()->route('menu-builder.index')->with('success', 'Menu updated successfully!');
        } catch (Exception $exp) {
            return redirect()->route('menu-builder.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $obj = Menu::where('id', $id)->first();
            if ($obj->photo && file_exists(storage_path('app/public/' . $obj->photo))) {
                unlink(storage_path('/app/public/' . $obj->photo));
            }
            $obj->delete();
            return redirect()->route('menu-builder.index')->with('success', 'Menu deleted successfully!');
        } catch (Exception $exp) {
            return redirect()->route('menu-builder.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getAllMenu(Request $request): \Illuminate\Http\JsonResponse
    {
        $model = Menu::select(['id', 'title','type','price_before','price','photo']);
        $data = $model->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
