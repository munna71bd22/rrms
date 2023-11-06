<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Menu;
use App\Models\MenuType;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MenuTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = MenuType::select(['id', 'title','icon'])->orderBy('created_at', 'DESC');
            return DataTables::eloquent($items)
                ->addColumn('actions', function ($item) {
                    return '<div class="btn-group" role="group" aria-label="First group">
                              <a title="Edit" href="' . route('menu-types.edit', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bx-edit"></i>
                              </a>

                               <a title="Show" href="' . route('menu-types.show', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bxs-notepad"></i>
                              </a>

                               <a
                               title="Delete"
                               name="' . route('menu-types.destroy', $item->id) . '"
                               onclick="confirmDelete(this.name)"
                               type="button"
                               class="btn btn-outline-danger">
                                <i class="tf-icons bx bx-trash"></i>
                              </a>


                            </div>';
                })
                ->rawColumns(['actions'])
                ->toJson();
        }

        // If it's not an AJAX request
        return view('components.list', [
            'title' => 'Menu Type List',
            'pageID' => 'menu-type101',
            'data_route' => 'menu-types.index',
            'columns' => ['Title','Fontawsome Icon','Actions'],
            'columns_for_datatable' => [
                [
                    "data" => "title",
                    "name" => "title",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "icon",
                    "name" => "icon",
                    "orderable" => "true",
                    "sortable" => "true",
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
                    'url' => route('menu-types.create')
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
            'title' => 'Create MenuType',
            'method' => 'POST',
            'route' => 'menu-types.store',
            'fields' => [
                [
                    'label' => 'Menu Type Title',
                    'placeholder' => 'Enter Menu Type Title',
                    'icon' => 'bx bx-food-menu',
                    'width' => '12',
                    'name' => 'title',
                    'type' => 'text',
                    'required' => true
                ],
                [
                    'label' => 'Fontawsome Icon',
                    'placeholder' => 'Enter Fontawsome Icon',
                    'icon' => 'bx bx-food-crosshair',
                    'width' => '12',
                    'name' => 'icon',
                    'type' => 'text',
                    'required' => true
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
                'title' => 'required|max:255|unique:menu_types',
            ]);

            $data = $request->except(['_token', '_method']);
            MenuType::create($data);

            return redirect()->route('menu-types.index')->with('success', 'New floor created successfully!');
        } catch (Exception $exp) {
            return redirect()->route('menu-types.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeFromAnother(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255|unique:menu_types',
            ]);

            $data = $request->except(['_token', '_method']);
            MenuType::create($data);

            return redirect()->back()->with('success', 'New menu type created successfully!');
        } catch (Exception $exp) {
            return redirect()->back()->with('error', $exp->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $obj = MenuType::select(
            [
                'title',
                'icon',
                'created_at'
            ]
        )->find($id);
        return view('components.show', ['title' => 'Menu Type Details', 'obj' => $obj]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $obj = MenuType::find($id);

        return view('components.edit', [
                'obj' => $obj,
                'title' => 'Edit MenuType',
                'method' => 'POST',
                'route' => 'menu-types.update',
                'fields' => [
                    [
                        'label' => 'Menu Type Title',
                        'placeholder' => 'Enter MenuType Title',
                        'icon' => 'bx bx-food-menu',
                        'width' => '6',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => true
                    ],
                    [
                        'label' => 'Fontawsome Icon',
                        'placeholder' => 'Enter Fontawsome Icon',
                        'icon' => 'bx bx-crosshair',
                        'width' => '6',
                        'name' => 'icon',
                        'type' => 'text',
                        'required' => true
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
                'title' => 'required|max:255|unique:menu_types,title,' . $id,
            ]);

            $data = $request->except(['_token', '_method']);


            $obj = MenuType::where('id', $id)->first();
            $obj->fill($data)->save();

            return redirect()->route('menu-types.index')->with('success', 'Menu type updated successfully!');
        } catch (Exception $exp) {
            return redirect()->route('menu-types.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $obj = MenuType::where('id', $id)->first();

            $checkDependency = Menu::where('type', $obj->title ?? '')->count('id');
            if ($checkDependency > 1) {
                return redirect()
                    ->route('menu-types.index')
                    ->with('error', 'This menu type can not be deleted at this moment because a menu already holds it!');
            }
            $obj->delete();
            return redirect()->route('menu-types.index')->with('success', 'MenuType deleted successfully!');
        } catch (Exception $exp) {
            return redirect()->route('menu-types.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getAllMenuType(Request $request): \Illuminate\Http\JsonResponse
    {
        $model = MenuType::select(['id','title','icon'])->with(['menus']);
        $data = $model->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
