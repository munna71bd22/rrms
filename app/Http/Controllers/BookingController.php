<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
                ->addColumn('photo', function ($item) {
                    $path = "booking/" . $item->id . ".png";
                    $imgSrc = file_exists($path) ? asset($path) : asset('assets/img/avatars/booking.png');
                    return '<img width="30" height="30" src=' . $imgSrc . '>';
                })
                ->addColumn('actions', function ($item) {
                    return '<div class="btn-group" role="group" aria-label="First group">
                              <a title="Edit" href="' . route('bookings.edit', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bx-edit"></i>
                              </a>

                               <a title="Show" href="' . route('bookings.show', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bxs-notepad"></i>
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
                ->rawColumns(['photo', 'actions'])
                ->toJson();
        }

        // If it's not an AJAX request
        return view('components.list', [
            'title' => 'Booking List',
            'pageID' => 'booking101',
            'data_route' => 'bookings.index',
            'columns' => ['Mobile', 'Start Time', 'End Time', 'Photo', 'Actions'],
            'columns_for_datatable' => [
                [
                    "data" => "customer_mobile",
                    "name" => "customer_mobile",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "booking_start_time",
                    "name" => "booking_start_time",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "booking_end_time",
                    "name" => "booking_end_time",
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
                    'url' => route('bookings.create')
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
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255|unique:bookings',
                'price_before' => 'required|min:1',
                'price' => 'required|min:1',
                'photo' => 'nullable|image|photo'
            ]);

            $data = $request->except(['_token', '_method']);

            Booking::create($data);
            return redirect()->route('bookings.index')->with('success', 'New booking created successfully!');
        } catch (Exception $exp) {
            return redirect()->route('bookings.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $obj = Booking::select(
            [
                'title',
                'price_before',
                'price',
                'photo',
                'created_at'
            ]
        )->find($id);
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
                'title' => 'required|max:255|unique:bookings,title,'.$id,
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
}
