<?php

namespace App\Repositories\Backend\Admin;

use Auth;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseRepositoryInterface;

class AdminRepository extends BaseRepository implements BaseRepositoryInterface,AdminRepositoryInterface{

   private $admin;

   public function __construct(Admin $admin)
   {
       parent::__construct();
       $this->admin =  $admin;
   }

   public function find($id, array $conditions = [])
   {
      if (!empty($conditions)) {
         return $this->admin->where($conditions)->where('id', $id)->first();
      }
      return $this->admin->find($id);
   }

   public function create(Request $request)
   {
      $admin = new $this->admin;
      $admin->name = $request->input('name');
      $admin->email = $request->input('email');
      $admin->phone = $request->input('phone');
      $admin->password = bcrypt($request->input('password'));
      $admin->active = $request->input('active');
      $admin->group_id = $request->input('group_id');
      $admin->created_by = $this->user->id;
      $admin->image = 'default.png';
      $admin->save();

      return $admin;
   }

   public function update(Request $request, $id, $admin)
   {
      $admin->name = $request->input('name');
      $admin->email = $request->input('email');
      $admin->phone = $request->input('phone');
      if ($request->input('password')) {
         $admin->password = bcrypt($request->input('password'));
      }
      $admin->active = $request->input('active');
      $admin->group_id = $request->input('group_id');
      $admin->save();

      return $admin;
   }

   public function delete(Request $request, $id, $admin)
   {
      return $admin->delete();
   }

   public function dataTable(Request $request)
   {
      return $this->admin->join('groups', 'groups.id', '=', 'admins.group_id')
         ->where('admins.created_by', $this->user->id)
         ->select('admins.*', 'groups.name as group');
   }

   public function login($admin)
   {
      Auth::guard('admin')->login($admin);
   }

   public function logout()
   {
      Auth::guard('admin')->logout();
   }

   public function checkAuth(Request $request)
   {
      $admin = $this->admin->join('groups','groups.id','=','admins.group_id')
                           ->where('groups.active',true)
                           ->where('admins.active',true)
                           ->where('admins.email', $request->input('email'))
                           ->select('admins.*')
                           ->first();
      if ($admin) {
         if (password_verify($request->input('password'),$admin->password)) {
            return $admin;
         }
      }
      return false;
   }

   public function updateProfile(Request $request)
   {
      $this->user->name = $request->input('name');
      $this->user->email = $request->input('email');
      $this->user->phone = $request->input('phone');
      if ($request->file('image')) {
         if ($this->user->image != 'default.png') {
            $this->admin->deleteUploaded('admins', $this->user->image);
         }
         $this->user->image = $this->admin->upload($request->file('image'), 'admins');
      }
      if ($request->input('profile_avatar_remove')) {
         if ($this->user->image != 'default.png') {
            $this->admin->deleteUploaded('admins', $this->user->image);
         }
         $this->user->image = 'default.png';
      }
      $this->user->save();
   }

   public function checkCurrentPassword(Request $request)
   {
      if (password_verify($request->input('current_password'), $this->user->password)) {
         return true;
      }
      return false;
   }

   public function updatePassword(Request $request)
   {
      $this->user->password = bcrypt($request->input('new_password'));
      $this->user->save();
   }

}