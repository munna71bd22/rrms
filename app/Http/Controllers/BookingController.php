<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = Booking::select(['*'])->orderBy('created_at', 'DESC');
            return DataTables::eloquent($items)
                ->addColumn('actions', function ($item) {
                    return '<div class="btn-group" role="group" aria-label="First group">

                               <a title="Show" href="' . route('bookings.show', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bxs-notepad"></i>
                              </a>
                               <a
                               title="Approve"
                               name="' . route('bookings.update-status', $item->id) . '"
                               onclick="updateStatus(this.name,`approved`)"
                               type="button"
                               class="btn btn-outline-success">
                                <i class="tf-icons bx bx-check-circle"></i>
                              </a>

                               <a
                               title="Cancel"
                               name="' . route('bookings.update-status', $item->id) . '"
                               onclick="updateStatus(this.name,`cancel`)"
                               type="button"
                               class="btn btn-outline-warning">
                                <i class="tf-icons bx bx-window-close"></i>
                              </a>

                               <a
                               title="Delete"
                               name="' . route('bookings.destroy', $item->id) . '"
                               onclick="confirmDelete(this.name)"
                               type="button"
                               class="btn btn-outline-danger">
                                <i class="tf-icons bx bx-trash"></i>
                              </a>


                            </div>';
                })
                /*
                ->addColumn('tables',function ($item){
                    $str = '';
                    foreach ($item->tables()->get() as $tbl) {
                        $str.= '<span class="badge bg-label-primary me-1">'.$tbl->title.'</span><br>';
                    }
                    return $str;
                })
                ->addColumn('menus',function ($item){
                    $str = '';
                    foreach ($item->menus()->get() as $tbl) {
                        $str.= '<span class="badge bg-label-dark me-1">'.$tbl->title.'</span><br>';
                    }
                    return $str;
                })
                */
                ->rawColumns(['actions'])
                ->toJson();
        }

        // If it's not an AJAX request
        return view('components.list', [
            'title' => 'Booking List',
            'pageID' => 'booking101',
            'data_route' => 'bookings.index',
            'columns' => ['Name', 'Mobile', 'Email', 'Date', 'Status', 'Actions'],
            'columns_for_datatable' => [
                [
                    "data" => "name",
                    "name" => "name",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "customer_mobile",
                    "name" => "customer_mobile",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "email",
                    "name" => "email",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "booking_date",
                    "name" => "booking_date",
                    "orderable" => "true",
                    "sortable" => "true",
                ],

                [
                    "data" => "status",
                    "name" => "status",
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
                /*
                [
                    'name' => 'Create',
                    'icon' => 'tf-icons bx bx-plus',
                    'class' => 'btn btn-icon btn-primary',
                    'url' => route('bookings.create')
                ],
                */

            ]
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('components.create', [
            'title' => 'Create Booking',
            'method' => 'POST',
            'route' => 'bookings.store',
            'fields' => [
                [
                    'label' => 'Booking Title',
                    'placeholder' => 'Enter Booking Title',
                    'icon' => 'bx bx-food-booking',
                    'width' => '6',
                    'name' => 'title',
                    'type' => 'text',
                    'required' => true
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
                    'placeholder' => 'Upload Booking Photo',
                    'icon' => 'bx bx-cloud-upload',
                    'width' => '4',
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
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'customer_mobile' => 'required|max:255',
                'email' => 'required|email',
                'booking_date' => 'required|date',
                'guest_qty' => 'required|min:1',
                'tbl_id' => 'required|array',
                'menus' => 'nullable|array',
                'remarks' => 'nullable|max:1406',
            ]);

            $data = $request->except(['_token', '_method']);

            $customer = User::where('email', $data['email'])->first();

            if (!$customer) {
                $customer = User::create([
                    'name' => $data['name'],
                    'mobile' => $data['customer_mobile'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['email'])
                ]);
            }
            $data['user_id'] = $customer->id;
            $data['tbl_id'] = json_encode($data['tbl_id']);
            $data['menus'] = json_encode($data['menus'] ?? []);

            Booking::create($data);

            return response()->json([
                'type' => 'success',
                'message' => '🎉Congratulations! Your booking request has been sent to us successfully! Our representative will contact with you immediately. Thank you very much.'
            ], 200);
        } catch (Exception $exp) {
            return response()->json([
                'type' => 'error',
                'message' => $exp->getMessage()
            ], 405);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $obj = Booking::select(
            [
                'name',
                'customer_mobile',
                'email',
                'booking_date',
                'guest_qty',
                'tbl_id',
                'menus',
                'remarks',
                'status',
                'confirmed_by',
                'confirmed_date',
            ]
        )->find($id);
        if ($obj) {
            $obj->tbl_id = $obj->tables()->get();
            $obj->menus = $obj->menus()->get();
            $obj->confirmed_by = $obj->conformedBy->name ?? null;
        }
        //return $obj;
        return view('components.show', ['title' => 'Booking Details', 'obj' => $obj]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $obj = Booking::find($id);

        return view('components.edit', [
                'obj' => $obj,
                'title' => 'Edit Booking',
                'method' => 'POST',
                'route' => 'bookings.update',
                'fields' => [
                    [
                        'label' => 'Booking Title',
                        'placeholder' => 'Enter Booking Title',
                        'icon' => 'bx bx-food-booking',
                        'width' => '6',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => true
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
                        'placeholder' => 'Upload Booking Photo',
                        'icon' => 'bx bx-cloud-upload',
                        'width' => '4',
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
                'title' => 'required|max:255|unique:bookings,title,' . $id,
                'price_before' => 'required|min:1',
                'price' => 'required|min:1',
                'photo' => 'nullable|image|photo'
            ]);

            $data = $request->except(['_token', '_method']);

            Booking::where('id', $id)->update($data);
            return redirect()->route('bookings.index')->with('success', 'Booking updated successfully!');
        } catch (Exception $exp) {
            return redirect()->route('bookings.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Booking::where('id', $id)->delete();
            return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully!');
        } catch (Exception $exp) {
            return redirect()->route('bookings.index')->with('error', $exp->getMessage());
        }
    }

    public function updateStatus($id, Request $request)
    {
        try {
            Booking::where('id', $id)->update(['status' => $request->status, 'confirmed_by' => auth()->user()->id, 'confirmed_date' => now()]);
            return redirect()->route('bookings.index')->with('success', 'Booking status updated successfully!');
        } catch (Exception $exp) {
            return redirect()->route('bookings.index')->with('error', $exp->getMessage());
        }
    }
}
