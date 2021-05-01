<?php

namespace App\Repositories\Api\Notification;

interface NotificationRepositoryInterface
{
    public function send($to, $type, $entity = 0);
    public function getForAuth();
    public function findForAuth($id);
    public function delete($notification);
    public function updateStatusForAuth();
    
    
}
