<?php

namespace App\Repositories\Backend\Notification;

use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationTranslation;
use App\Helpers\Fcm;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\Device\DeviceRepositoryInterface;
use DB;


class NotificationRepository extends BaseRepository implements  NotificationRepositoryInterface{

   private $notification;
   private $notificationTranslation;
   private $user;
   private $deviceRepository;

   public function __construct(
      Notification $notification,
      NotificationTranslation $notificationTranslation,
      User $user,
      DeviceRepositoryInterface $deviceRepository
   )
   {
      parent::__construct();
      $this->notification =  $notification;
      $this->notificationTranslation =  $notificationTranslation;
      $this->user =  $user;
      $this->deviceRepository =  $deviceRepository;
   }

   public function find($id){
      return $this->notification->find($id);
   }

   public function create(Request $request)
   {
      $notification = new $this->notification;
      $notification->type = $this->notification->types['general'];
      $notification->save();

      $this->createNotificationTranslations($notification, $request);
      $this->createUsersNotification($notification, $request);
      $this->sendFcmNotification($request);
   }


   public function dataTable(Request $request)
   {
      return $this->notification->join('notification_translations', function ($query) {
                              $query->on('notifications.id', '=', 'notification_translations.notification_id')
                                 ->where('notification_translations.locale', $this->langCode);
                              })
                              ->select('notifications.*', 'notification_translations.body');
   }

   public function getTranslations($notification)
   {
      return $this->notificationTranslation->where('notification_id', $notification->id)->get()->keyBy('locale');
   }
   

   private function createNotificationTranslations($notification, $request){

      $notificationTranslations = array();

      $body = $request->input('body');
      foreach ($body as $key => $value) {
         $notificationTranslations[] = array(
            'locale' => $key,
            'body' => $value,
            'notification_id' => $notification->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
         );
      }
      $this->notificationTranslation->insert($notificationTranslations);
   }

   private function createUsersNotification($notification, $request){
      $userType = $request->input('type');

      $usersNotifications = $this->user->where('active', true);
      if ($userType == $this->user->types['client'] || $userType == $this->user->types['company']) {
         $usersNotifications->where('type', $userType);
      }
      $usersNotifications = $usersNotifications->select(
         'users.id as user_id',
         DB::raw("" . $this->notification->types['general'] . " as type"),
         DB::raw("0 as status"),
         DB::raw("" . $notification->id . " as parent_id"),
         DB::raw("'".date('Y-m-d H:i:s')."' as created_at"),
         DB::raw("'".date('Y-m-d H:i:s')."' as updated_at")
      )
      ->get()
      ->toArray();

      $this->notification->insert($usersNotifications);
   }

   private function sendFcmNotification($request){
      $userType = $request->input('type');
      $Fcm = new Fcm;
      foreach ($this->languages as $lang) {
         $notification = array(
            'title' =>   'M3rady',
            'body' =>    $request->input('body.' . $lang),
            'type' => $this->notification->types['general']
         );
         if ($userType == $this->user->types['client'] || $userType == $this->user->types['company']) {
            $androidTokens = $this->deviceRepository->getTokens($this->deviceRepository->types['android'], $lang , $userType);
            $iosTokens = $this->deviceRepository->getTokens($this->deviceRepository->types['ios'], $lang, $userType);
         }else{
            $androidTokens = $this->deviceRepository->getTokens($this->deviceRepository->types['android'], $lang);
            $iosTokens = $this->deviceRepository->getTokens($this->deviceRepository->types['ios'], $lang);
         }
         $Fcm->send($androidTokens, $notification, 'and');
         $Fcm->send($iosTokens, $notification, 'ios');
      }
   }

  
   
   

}