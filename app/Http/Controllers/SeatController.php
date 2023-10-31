<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SeatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = Table::select(['id', 'title', 'price_before', 'price', 'photo'])->orderBy('created_at', 'DESC');
            return DataTables::eloquent($items)
                ->addColumn('photo', function ($item) {
                    $path = "menu/" . $item->id . ".png";
                    $imgSrc = file_exists($path) ? asset($path) : asset('assets/img/avatars/menu.png');
                    return '<img width="30" height="30" src=' . $imgSrc . '>';
                })
                ->addColumn('actions', function ($item) {
                    return '<div class="btn-group" role="group" aria-label="First group">
                              <a title="Edit" href="' . route('seat-builder.edit', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bx-edit"></i>
                              </a>

                               <a title="Show" href="' . route('seat-builder.show', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bxs-notepad"></i>
                              </a>

                               <a
                               title="Delete"
                               name="' . route('seat-builder.destroy', $item->id) . '"
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
        return view('seat-builder.builder', [
            'title' => 'Seat Builder',
            'pageID' => 'seat-builder-101',
            'data_route' => 'seat-builder.index',
            'floors' => Floor::select(['id', 'title'])->get()
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
            return redirect()->route('seat-builder.index')->with('success', 'New floor created successfully!');
        } catch (Exception $exp) {
            return redirect()->route('seat-builder.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255|unique:menus,title,' . $id,
            ]);

            $data = $request->except(['_token', '_method']);

            Floor::where('id', $id)->update($data);
            return redirect()->route('seat-builder.index')->with('success', 'Floor updated successfully!');
        } catch (Exception $exp) {
            return redirect()->route('seat-builder.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Floor::where('id', $id)->delete();
            return redirect()->route('seat-builder.index')->with('success', 'Floor deleted successfully!');
        } catch (Exception $exp) {
            return redirect()->route('seat-builder.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function build(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validate([
                'floor_id' => 'required|integer',
                'tbl_id.*' => 'required',
            ]);

            $data = $request->except(['_token', '_method']);

            $insertedTblIds = [];
            foreach ($data['items'] as $item) {
                if(Table::updateOrCreate(
                    [
                        'tbl_id' => $item['id'],
                        'floor_id' => $data['floor_id']
                    ],
                    [
                        'title' => $item['title'] ?? '',
                        'tbl_id' => $item['id'],
                        'canvas_obj' => json_encode($item),
                        'floor_id' => $data['floor_id'],
                    ]) ) {
                    $insertedTblIds[] = $item['id'];
                }
            }
            Table::where('floor_id',$data['floor_id'])->whereNotIn('tbl_id',$insertedTblIds)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Seats built successfully!',
            ], 200);
        } catch (Exception $exp) {
            return response()->json([
                'status' => 'error',
                'message' => $exp->getMessage(),
            ], 405);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBuildData(Request $request): \Illuminate\Http\JsonResponse
    {
        $model = Table::select(['id','title','canvas_obj','floor_id']);

        if ($request->floor_id) {
            $model->where('floor_id',$request->floor_id);
        }
        $data = $model->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
