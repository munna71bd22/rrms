<?php

namespace App\Http\Controllers;

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
            $items = User::select(['id','name','email']);
            return DataTables::eloquent($items)
                ->addColumn('avatar', function ($item) {
                    $path = "photo/" . $item->id . ".jpg";
                    $imgSrc = file_exists($path) ? asset($path) : asset('assets/img/avatars/1.png');
                    return '<img width="30" height="30" src=' . $imgSrc . '>';
                })
                ->addColumn('actions', function ($item) {
                    return '<div class="btn-group" role="group" aria-label="First group">
                              <button type="button" class="btn btn-outline-secondary">
                                <i class="tf-icons bx bx-bell"></i>
                              </button>
                              <button type="button" class="btn btn-outline-secondary">
                                <i class="tf-icons bx bx-task"></i>
                              </button>
                              <button type="button" class="btn btn-outline-secondary">
                                <i class="tf-icons bx bx-check-shield"></i>
                              </button>
                              <button type="button" class="btn btn-outline-secondary">
                                <i class="tf-icons bx bx-comment-dots"></i>
                              </button>
                            </div>';
                    })
                ->rawColumns(['avatar','actions'])
                ->toJson();
        }

        // If it's not an AJAX request, render the 'customers.list' view.
        return view('components.list', [
            'title' => 'Customer List',
            'data_route' => 'customers.index',
            'columns' => ['Name','Email','Avatar','Actions']
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Calander $calander)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calander $calander)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calander $calander)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calander $calander)
    {
        //
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
                'password_confirmation' => 'nullable|required_with:password|same:password|min:8'
            ]);

            $data = $request->except(['_token', '_method', 'password_confirmation', $request->password ? '' : 'password']);

            User::where('id', Auth::user()->id)->update($data);
            return redirect('/home/user/profile')->with('success', 'Profile updated successfully!');
        } catch (Exception $exp) {
            return redirect('/home/user/profile')->with('error', $exp->getMessage());
        }
    }
}
