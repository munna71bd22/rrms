<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Floor;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = Floor::select(['id', 'title'])->orderBy('created_at', 'DESC');
            return DataTables::eloquent($items)
                ->addColumn('actions', function ($item) {
                    return '<div class="btn-group" role="group" aria-label="First group">
                              <a title="Edit" href="' . route('floors.edit', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bx-edit"></i>
                              </a>

                               <a title="Show" href="' . route('floors.show', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bxs-notepad"></i>
                              </a>

                               <a
                               title="Delete"
                               name="' . route('floors.destroy', $item->id) . '"
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
            'title' => 'Floor List',
            'pageID' => 'floor101',
            'data_route' => 'floors.index',
            'columns' => ['Title','Actions'],
            'columns_for_datatable' => [
                [
                    "data" => "title",
                    "name" => "title",
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
                    'url' => route('floors.create')
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
            'title' => 'Create Floor',
            'method' => 'POST',
            'route' => 'floors.store',
            'fields' => [
                [
                    'label' => 'Floor Title',
                    'placeholder' => 'Enter Floor Title',
                    'icon' => 'bx bx-food-menu',
                    'width' => '12',
                    'name' => 'title',
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
                'title' => 'required|max:255|unique:floors',
            ]);

            $data = $request->except(['_token', '_method']);
            Floor::create($data);

            return redirect()->route('floors.index')->with('success', 'New floor created successfully!');
        } catch (Exception $exp) {
            return redirect()->route('floors.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeFromAnother(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255|unique:floors',
            ]);

            $data = $request->except(['_token', '_method']);
            Floor::create($data);

            return redirect()->back()->with('success', 'New floor created successfully!');
        } catch (Exception $exp) {
            return redirect()->back()->with('error', $exp->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $obj = Floor::select(
            [
                'title',
                'created_at'
            ]
        )->find($id);
        return view('components.show', ['title' => 'Floor Details', 'obj' => $obj]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $obj = Floor::find($id);

        return view('components.edit', [
                'obj' => $obj,
                'title' => 'Edit Floor',
                'method' => 'POST',
                'route' => 'floors.update',
                'fields' => [
                    [
                        'label' => 'Floor Title',
                        'placeholder' => 'Enter Floor Title',
                        'icon' => 'bx bx-food-menu',
                        'width' => '6',
                        'name' => 'title',
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
                'title' => 'required|max:255|unique:floors,title,' . $id,
            ]);

            $data = $request->except(['_token', '_method']);


            $obj = Floor::where('id', $id)->first();
            $obj->fill($data)->save();

            return redirect()->route('floors.index')->with('success', 'Floor updated successfully!');
        } catch (Exception $exp) {
            return redirect()->route('floors.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $checkDependency = Table::where('floor_id', $id)->count('id');
            if ($checkDependency > 1) {
                return redirect()
                    ->route('floors.index')
                    ->with('error', 'This floor can not be deleted at this moment because a table already holds it!');
            }
            $obj = Floor::where('id', $id)->first();
            $obj->delete();
            return redirect()->route('floors.index')->with('success', 'Floor deleted successfully!');
        } catch (Exception $exp) {
            return redirect()->route('floors.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getAllFloor(Request $request): \Illuminate\Http\JsonResponse
    {
        $model = Floor::select(['id',]);
        $data = $model->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
