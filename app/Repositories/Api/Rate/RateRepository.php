<?php

namespace App\Repositories\Api\Rate;

use App\Models\Rate;
use Illuminate\Http\Request;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class RateRepository extends BaseRepository implements BaseRepositoryInterface, RateRepositoryInterface
{
    private $rate;

    public function __construct(Rate $rate)
    {
        Parent::__construct();
        $this->rate = $rate;
    }

    public function list(Request $request)
    {
        $user = $this->authUser();
        $columns = [
            'rates.*',
            'company_details.company_id',
            'users.image as user_image',
            'users.name',
            'users.type'
        ];
        if ($user) {
            $columns[] = \DB::raw('(CASE WHEN rates.user_id = ' . $user->id . ' THEN 1 ELSE 0 END) as is_mine');
        }
        $rates = $this->rate->join('users', 'rates.user_id', '=', 'users.id')
            ->leftJoin('company_details', 'users.id', '=', 'company_details.user_id')
            ->where('users.active', true)
            ->where('rates.status', true)
            ->where('rates.company_id',$request->input('company'))
            ->select($columns)
            ->paginate($this->limit);

        return $rates;
    }

    public function findForAuth($id)
    {
        return $this->rate->where('id',$id)->where('user_id',$this->authUser()->id)->first();
    }

    public function create(Request $request)
    {
        $rate = new $this->rate;
        $rate->user_id = $this->authUser()->id;
        $rate->company_id = $request->input('company');
        $rate->score = $request->input('score');
        $rate->comment = $request->input('comment') ?:"";
        $rate->save();
    }

    public function delete($rate)
    {
        $rate->delete();
    }
}
