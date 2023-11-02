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
        return view('seat-builder.builder', [
            'title' => 'Seat Builder',
            'pageID' => 'seat-builder-101',
            'data_route' => 'seat-builder.index',
            'floors' => Floor::select(['id', 'title'])->get()
        ]);
    }

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
            foreach ($data['items'] ?? [] as $item) {
                if (Table::updateOrCreate(
                    [
                        'tbl_id' => $item['id'],
                        'floor_id' => $data['floor_id']
                    ],
                    [
                        'title' => $item['title'] ?? '',
                        'tbl_id' => $item['id'],
                        'canvas_obj' => json_encode($item),
                        'floor_id' => $data['floor_id'],
                    ])) {
                    $insertedTblIds[] = $item['id'];
                }
            }
            Table::where('floor_id', $data['floor_id'])->whereNotIn('tbl_id', $insertedTblIds)->delete();

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
        $model = Table::select(['id', 'title', 'canvas_obj', 'floor_id'])->where('floor_id', $request->floor_id);
        $data = $model->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getAllFloor(Request $request): \Illuminate\Http\JsonResponse
    {
        $model = Floor::select(['id', 'title']);
        $data = $model->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
