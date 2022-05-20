<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
        'message'=>'User logout successfully',
        'alert-type'=>'success'
      );


        return redirect('/login')->with($notification);
    }


    public function profile()
    {
      $id = Auth::user()->id;
      $adminData = User::find($id);

      return view('admin.admin_profile_view', compact('adminData'));
    }


    public function edit_profile()
    {
      $id = Auth::user()->id;
      $editData = User::find($id);
      return view('admin.admin_profile_edit', compact('editData'));
    }

    public function store_profile(Request $request)
    {
      $id = Auth::user()->id;
      $data = User::find($id);
      $data->name = $request->name;
      $data->email = $request->email;
      $data->username = $request->username;

      if($request->file('profile_image')){
        $file = $request->file('profile_image');

        $filename = date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('uploads/admin_images'), $filename);
        $data['profile_image'] = $filename;
      }

      $data->save();

      $notification = array(
        'message'=>'Admin profile updated successfully',
        'alert-type'=>'success',
      );

      return redirect()->route('admin.profile')->with($notification);
    }


      public function change_password()
      {
        return view('admin.admin_change_password');
      }
      

      public function update_password(Request $request)
      {
        $validateDate = $request->validate([
          'oldpassword' => 'required',
          'newpassword' => 'required',
          'confirm_password' => 'required|same:newpassword',

        ]);

        $hashedPassword = Auth::user()->password;
        if(Hash::check($request->oldpassword, $hashedPassword)){
          $users = User::find(Auth::id());
          $users->password = bcrypt($request->newpassword);
          $users->save();

          session()->flash('message', 'Password updated successfully');
          return redirect()->back();

        }else{
          session()->flash('message', 'Old password does not match');
          return redirect()->back();

        }
      }

     
}
