<?php 

namespace App\Traits;

use App\Models\Task;
use App\Models\Visit;
use App\Models\VisitTask;

trait VisitsTrait
{
    protected function createVisit($data)
    {
        $check_conditions = [
            ['date','=', $data['date']],
            ['type','=', $data['type']],
            ['employee_id','=', $data['employee_id']],
            ['store_id', '=', $data['store_id']]
        ];
        $visit_exist = Visit::where($check_conditions)->first();
        if (!$visit_exist) {
            $visit = new Visit();
            $visit->day = date('D', strtotime($data['date']));
            $visit->date = $data['date'];
            $visit->type =  $data['type'];
            $visit->employee_id = $data['employee_id'];
            $visit->store_id = $data['store_id'];
            if (isset($data['visit_schedule_id'])) {
                $visit->visit_schedule_id = $data['visit_schedule_id'];
            }
            $visit->save();
            $visit->serial =  $this->generateSerialNumber($visit->id);
            $visit->save();
            if (!empty($data['tasks'])) {
                $this->createVisitTasks($visit->id, $data['tasks']);
            }
        }
        
       
    }

    protected function createVisitTasks($visit_id, $tasks)
    {
        $visit_tasks = array();
        foreach ($tasks as $task) {
            $visit_tasks[] = [
                'task_type' => Task::$types[$task],
                'visit_id' => $visit_id
            ];
        }

        VisitTask::insert($visit_tasks);
    }

    protected function generateSerialNumber($id)
    {
        $start = 703; // 0 = A, 702 or 703 = AAA, adjust accordingly
        $letters_value = $start + (ceil($id / 999) - 1);
        $numbers = ($id % 999 === 0) ? 999 : $id % 999;

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($characters);
        $letters = '';

        // while there are still positive integers to divide
        while ($letters_value) {
            $current = $letters_value % $base;
            $letters = $characters[$current] . $letters;
            $letters_value = floor($letters_value / $base);
        }

        return $letters . '-' . sprintf('%05d', $numbers);
    }
}

