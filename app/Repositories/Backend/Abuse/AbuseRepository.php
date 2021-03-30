<?php

namespace App\Repositories\Backend\Abuse;

use App\Models\Abuse;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;

class AbuseRepository extends BaseRepository implements  AbuseRepositoryInterface{

   private $abuse;

   public function __construct(Abuse $abuse)
   {
       parent::__construct();
       $this->abuse =  $abuse;
   }

   public function find($id)
   {
      return $this->abuse->find($id);
   }

   public function delete(Request $request, $id, $abuse)
   {
      return $abuse->delete();
   }

   public function dataTable(Request $request)
   {
      $post = $request->input('post');
      return $this->abuse->join('users','abuses.user_id','=','users.id')
                           ->leftJoin('company_details', 'users.id', '=', 'company_details.user_id')
                           ->where('abuses.post_id',$post)
                           ->select('abuses.*','users.name','company_details.company_id');
       
   }
   

}