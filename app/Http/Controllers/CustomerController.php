<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = User::select(['id', 'name', 'email', 'mobile', 'avatar'])->orderBy('created_at', 'DESC');
            return DataTables::eloquent($items)
                ->addColumn('avatar', function ($item) {
                    $imgSrc =  $item->avatar ? asset('storage/'. $item->avatar) : asset('assets/img/avatars/1.png');
                    return '<img width="30" height="30" src=' . $imgSrc . '>';
                })
                ->addColumn('actions', function ($item) {
                    return '<div class="btn-group" role="group" aria-label="First group">
                              <a title="Edit" href="' . route('customers.edit', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bx-edit"></i>
                              </a>

                               <a title="Show" href="' . route('customers.show', $item->id) . '" type="button" class="btn btn-outline-info">
                                <i class="tf-icons bx bxs-notepad"></i>
                              </a>

                               <a
                               title="Delete"
                               name="' . route('customers.destroy', $item->id) . '"
                               onclick="confirmDelete(this.name)"
                               type="button"
                               class="btn btn-outline-danger">
                                <i class="tf-icons bx bx-trash"></i>
                              </a>


                            </div>';
                })
                ->rawColumns(['avatar', 'actions'])
                ->toJson();
        }

        // If it's not an AJAX request, render the 'customers.list' view.
        return view('components.list', [
            'title' => 'Customer List',
            'pageID' => 'customer101',
            'data_route' => 'customers.index',
            'columns' => ['Name', 'Email', 'Mobile', 'Avatar', 'Actions'],
            'columns_for_datatable' => [
                [
                    "data" => "name",
                    "name" => "name",
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
                    "data" => "mobile",
                    "name" => "mobile",
                    "orderable" => "true",
                    "sortable" => "true",
                ],
                [
                    "data" => "avatar",
                    "name" => "avatar",
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
                    'url' => route('customers.create')
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
            'title' => 'Create Customer',
            'method' => 'POST',
            'route' => 'customers.store',
            'fields' => [
                [
                    'label' => 'Customer Name',
                    'placeholder' => 'Enter Customer Name',
                    'icon' => 'bx bx-user',
                    'width' => '6',
                    'name' => 'name',
                    'type' => 'text',
                    'required' => true
                ],
                [
                    'label' => 'Mobile',
                    'placeholder' => '8801XXXXXXXXX',
                    'icon' => 'bx bx-phone',
                    'width' => '6',
                    'name' => 'mobile',
                    'type' => 'tel',
                    'pattern' => '[0-9]{10}',
                    'required' => true
                ],
                [
                    'label' => 'Email',
                    'placeholder' => 'Enter Customer Email',
                    'icon' => 'bx bx-envelope',
                    'width' => '3',
                    'name' => 'email',
                    'type' => 'email',
                    'required' => true
                ],
                [
                    'label' => 'Password',
                    'placeholder' => 'Enter Password',
                    'icon' => 'bx bx-key',
                    'width' => '3',
                    'name' => 'password',
                    'type' => 'text',
                    'required' => true
                ],
                [
                    'label' => 'Avatar',
                    'placeholder' => 'Upload Customer Avatar',
                    'icon' => 'bx bx-cloud-upload',
                    'width' => '6',
                    'name' => 'avatar',
                    'type' => 'file',
                    'file_type' => 'image',
                    'required' => false
                ],
                [
                    'label' => 'Address',
                    'placeholder' => 'Enter Customer Address',
                    'icon' => 'bx bx-comment',
                    'width' => '12',
                    'name' => 'address',
                    'type' => 'textarea',
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
                'name' => 'required|max:255',
                'mobile' => 'required|unique:users|min:11',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|min:8',
                'address' => 'nullable|max:2000',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:512',
            ]);

            $data = $request->except(['_token', '_method', $request->password ? '' : 'password']);
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            if($request->file('avatar')) {
                $image_path = $request->file('avatar')->store('customer', 'public');
                $data['avatar'] = $image_path;
            }

            User::create($data);
            return redirect()->route('customers.index')->with('success', 'New customer created successfully!');
        } catch (Exception $exp) {
            return redirect()->route('customers.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $obj = User::select(
            [
                'name',
                'email',
                'mobile',
                'address',
                'avatar',
                'created_at'
            ]
        )->find($id);
        return view('components.show', ['title' => 'Customer Details', 'obj' => $obj]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $obj = User::find($id);
        return view('components.edit', [
                'obj' => $obj,
                'title' => 'Edit Customer',
                'method' => 'POST',
                'route' => 'customers.update',
                'fields' => [
                    [
                        'label' => 'Customer Name',
                        'placeholder' => 'Enter Customer Name',
                        'icon' => 'bx bx-user',
                        'width' => '6',
                        'name' => 'name',
                        'type' => 'text',
                        'required' => true
                    ],
                    [
                        'label' => 'Mobile',
                        'placeholder' => '8801XXXXXXXXX',
                        'icon' => 'bx bx-phone',
                        'width' => '6',
                        'name' => 'mobile',
                        'type' => 'tel',
                        'pattern' => '[0-9]{10}',
                        'required' => true
                    ],
                    [
                        'label' => 'Email',
                        'placeholder' => 'Enter Customer Email',
                        'icon' => 'bx bx-envelope',
                        'width' => '3',
                        'name' => 'email',
                        'type' => 'email',
                        'required' => true
                    ],
                    [
                        'label' => 'Password',
                        'placeholder' => 'Enter Password',
                        'icon' => 'bx bx-key',
                        'width' => '3',
                        'name' => 'password',
                        'type' => 'text',
                        'required' => false
                    ],
                    [
                        'label' => 'Avatar',
                        'placeholder' => 'Upload Customer Avatar',
                        'icon' => 'bx bx-avatar',
                        'width' => '6',
                        'name' => 'avatar',
                        'type' => 'file',
                        'file_type' => 'image',
                        'required' => false
                    ],
                    [
                        'label' => 'Address',
                        'placeholder' => 'Enter Customer Address',
                        'icon' => 'bx bx-comment',
                        'width' => '12',
                        'name' => 'address',
                        'type' => 'textarea',
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
                'name' => 'required|max:255',
                'mobile' => 'required|min:11|unique:users,mobile,' . $id,
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|min:8',
                'address' => 'nullable|max:2000',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:512',
            ]);

            $data = $request->except(['_token', '_method', $request->password ? '' : 'password']);

            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $obj = User::where('id', $id)->first();

            if ($request->file('avatar')) {

                if ( $obj->avatar && file_exists(storage_path('app/public/' . $obj->avatar))) {
                    unlink(storage_path('/app/public/' . $obj->avatar));
                }

                $image_path = $request->file('avatar')->store('customer', 'public');
                $data['avatar'] = $image_path;
            }

            $obj->fill($data)->save();

            return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
        } catch (Exception $exp) {
            return redirect()->route('customers.index')->with('error', $exp->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $checkDependency = Booking::where('user_id', $id)->count('id');
            if ($checkDependency == 1) {
                return redirect()
                    ->route('customers.index')
                    ->with('error', 'Customer can not be deleted at this moment because he/she has reservation information!');
            }
            $obj = User::where('id', $id)->first();
            if ($obj->avatar && file_exists(storage_path('app/public/' . $obj->avatar))) {
                unlink(storage_path('/app/public/' . $obj->avatar));
            }
            $obj->delete();
            return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
        } catch (Exception $exp) {
            return redirect()->route('customers.index')->with('error', $exp->getMessage());
        }
    }

    public function getOwnProfile()
    {
        return view('auth.profile');
    }

    public function updateOwnProfile(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'password' => 'nullable|min:8',
                'password_confirmation' => 'nullable|required_with:password|same:password|min:8',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:512',
            ]);

            $data = $request->except(['_token', '_method', 'email', 'user', 'password_confirmation', $request->password ? '' : 'password']);
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $obj = User::where('id', Auth::user()->id)->first();

            if ($request->file('avatar')) {

                if ( $obj->avatar && file_exists(storage_path('app/public/' . $obj->avatar))) {
                    unlink(storage_path('/app/public/' . $obj->avatar));
                }

                $image_path = $request->file('avatar')->store('customer', 'public');
                $data['avatar'] = $image_path;
            }

            $obj->fill($data)->save();

            return redirect('/home/user/profile')->with('success', 'Profile updated successfully!');
        } catch (Exception $exp) {
            return redirect('/home/user/profile')->with('error', $exp->getMessage());
        }
    }
}
