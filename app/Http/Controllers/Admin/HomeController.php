<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();
        return view('admin.dashboard', array('users' => $users));
    }

    /*
	------------------------------
	======== Delete User =========
	------------------------------
	*/
    public function deleteUser()
    {
        print_r($_POST);
    }

    public function adminprofile(Request $request)
    {
        if (auth()->check()) {
            return view('admin.profile');
        } else {
            return redirect('/admin/login');
        }
    }

    public function adminStore(Request $request)
    {
        $adminName = $request->post('name');
        $adminEmail = $request->post('email');
        $id = Auth::user()->id;
        $adminUser = Admin::where('id', $id)->update(['name' => $adminName, 'email' => $adminEmail]);
        if ($adminUser) {
            session()->flash('success', 'Successfully updated profile.');
            return redirect()->back();
        } else {
            session()->flash('error', 'Something went wrong!');
            return redirect()->back();
        }
    }

    public function adminPassword(Request $request)
    {
        $old_pass = $request->post('old_pass');
        if ($old_pass == Hash::check($old_pass, Auth::user()->password)) {
            $new_pass = $request->post('new_pass');
            $user = Auth::user();
            $rules = [
                'new_pass' => 'required|min:8',
                'confirm_pass' => 'required|same:new_pass',
            ];

            $input = [
                'new_pass' => $request->post('new_pass'),
                'confirm_pass' => $request->post('confirm_pass')
            ];

            $messages = [
                'confirm_pass.same' => 'Password Confirmation should match the New Password',
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                session()->flash('error', $validator->customMessages["confirm_pass.same"]);
                return redirect()->back();
            }

            $user->password = Hash::make($new_pass);
            $user->save();
            if ($user) {
                session()->flash('success', 'Successfully updated password.');
                return redirect()->back();
            } else {
                session()->flash('error', 'Something went wrong!');
                return redirect()->back();
            }
        } else {
            session()->flash('error', 'Invalid Old Password');
            return redirect()->back();
        }
    }
}
