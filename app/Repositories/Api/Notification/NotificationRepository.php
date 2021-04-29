<?php

namespace App\Repositories\Api\Notification;

use App\Helpers\Fcm;
use App\Models\Device;
use App\Models\Notification;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class NotificationRepository extends BaseRepository implements BaseRepositoryInterface, NotificationRepositoryInterface
{

    private $notification;
    private $device;
    public $types;
   

    public function __construct(Notification $notification, Device $device)
    {
        Parent::__construct();
        $this->notification = $notification;
        $this->device = $device;
        $this->types = $this->notification->types;
    }

    public function send($to, $type, $entity = 0){

        $notification = new $this->notification;
        $notification->type = $type;
        $notification->user_id = $to;
        $notification->created_by = $this->authUser()->id;
        $notification->entity_id = $entity;
        $notification->save();

        $this->sendFcm($to, $type, $entity);
    }

    public function getForAuth()
    {
        $user = $this->authUser();
        $notifications = $this->notification->leftJoin('users','notifications.created_by','=','users.id')
        ->leftJoin('company_details','users.id','=', 'company_details.user_id')
        ->leftJoin('notification_translations', function ($query) {
            $query->on('notification_translations.notification_id', '=', 'notifications.parent_id')
                ->where('notification_translations.locale', $this->langCode)
                ->where('notifications.type', $this->types['general']);
        })
        ->where('notifications.user_id',$user->id)
        ->select('notifications.*','users.name', 'company_details.company_id', 'notification_translations.body')
        ->orderBy('notifications.created_at','desc')
        ->limit(60)
        ->paginate($this->limit);
        return $notifications;
    }

    public function updateStatusForAuth()
    {
        $this->notification->where('user_id', $this->authUser()->id)->update(['status' => true]);
    }

    private function sendFcm($to, $type, $entity)
    {
        $devices = $this->device->where('user_id', $to)->get();
        $user = $this->authUser();
        if ($companyDetails = $user->companyDetails) {
            $name = $companyDetails->company_id;
        }else{
            $name = $user->name;
        }
        $Fcm = new Fcm;
        foreach ($devices as $device) {
            $body = $name.' '.$this->notification->{array_search($type, $this->types) . "_messages"}[$device->lang];
            $notification = array(
                'title' =>  'M3rady',
                'body' =>    $body,
                'type' => $type
            );
            if ($type != $this->types['follow']) {
                $notification['entity_id'] =  $entity;
            }
            
            if ($device->device_type == $this->device->types['android']) {
                $Fcm->send($device->device_token, $notification, 'and');
            } else {
                $Fcm->send($device->device_token, $notification, 'ios');
            }
        }
    }

   
}
