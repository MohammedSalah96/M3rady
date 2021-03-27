<?php

namespace App\Repositories\Backend\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseRepositoryInterface;

class UserRepository extends BaseRepository implements BaseRepositoryInterface, UserRepositoryInterface{

   private $user;
   public $types;

   public function __construct(User $user)
   {
      parent::__construct();
      $this->user =  $user;
      $this->types =  $this->user->types;
   }

   public function find($id, array $conditions = [])
   {
      if (!empty($conditions)) {
         return $this->user->where($conditions)->where('id',$id)->first();
      }
      return $this->user->find($id);
   }

   public function create(Request $request)
   {
      $user = new $this->user;
      $user->name = $request->input('name') ?: "";
      $user->email = $request->input('email');
      $user->mobile = $request->input('mobile');
      $user->password = bcrypt($request->input('password'));
      $user->country_id = $request->input('country');
      $user->city_id = $request->input('city');
      $user->type = $request->input('type');
      if ($request->file('image')) {
         $user->image = $this->user->upload($request->file('image'), 'users');
      }else{
         $user->image = 'default.png';
      }
      $user->active = $request->input('active');
      $user->save();
      return $user;
   }

   public function update(Request $request, $id, $user)
   {
      $user->name = $request->input('name') ?: "";
      $user->email = $request->input('email');
      $user->mobile = $request->input('mobile');
      if ($request->input('password')) {
         $user->password = bcrypt($request->input('password'));
      }
      $user->country_id = $request->input('country');
      $user->city_id = $request->input('city');
      $user->type = $request->input('type');
      if ($request->file('image')) {
         if ($user->image != 'default.png') {
            $this->user->deleteUploaded('users', $user->image);
         }
         $user->image = $this->user->upload($request->file('image'),'users');
      }
      $user->active = $request->input('active');
      $user->save(); 
      return $user;
   }

   public function delete(Request $request, $id, $user)
   {
      return $user->delete();
   }

   public function dataTable(Request $request)
   {
      $type = $request->input('type');
      $columns = ['users.*', 'country_translations.name as country', 'city_translations.name as city'];
      $users =  $this->user->join('location_translations as country_translations', function ($query) {
                              $query->on('users.country_id', '=', 'country_translations.location_id')
                                 ->where('country_translations.locale', $this->langCode);
                              })
                              ->join('location_translations as city_translations', function ($query) {
                              $query->on('users.city_id', '=', 'city_translations.location_id')
                                 ->where('city_translations.locale', $this->langCode);
                              })->where('type',$type);
                              if ($type == 'company') {
                                 $users->join('company_details', 'users.id','=', 'company_details.user_id');
                                 $columns[]  = 'company_details.company_id';
                              }
      
      return $users = $users->select($columns);
   }
   

}