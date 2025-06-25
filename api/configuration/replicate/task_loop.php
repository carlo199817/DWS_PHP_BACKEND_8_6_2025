<?php
class task_loop {

        public function setLooptask($index,$task_id,$entityManager,$processDb,$collect_data){

                  $task = $entityManager->find(configuration_process\task::class,$task_id);
                  $new_task = new configuration_process\task;
                  $new_task->setTitle($task->getTitle());
                  $new_task->setDescription($task->getDescription());
                  $new_task->setStatus($task->getStatus());
                  $new_task->setStyle($task->getStyle());
                  $new_task->setRowset($task->getRowset());
                  $new_task->setColset($task->getColset());
                  $new_task->setSeries(0);
                  $entityManager->persist($new_task);
                  $entityManager->flush();
                  $collect_data[] = [$task->getId()=>$new_task->getId()];
                  foreach($task->getTaskvalidation() as $validation){
                    $new_validation = new configuration_process\validation;
                    $new_validation->setValid($validation->getValid() ? $validation->getValid() : null);
                    $new_validation->setCreatedby($validation->getCreatedby());
                    $new_validation->setUsertype( $validation->getUsertype());
                    $entityManager->persist($new_validation);
                    $entityManager->flush();
                    $new_task->setTaskvalidation($new_validation);
                    $entityManager->flush();
                  }

                  foreach($task->getTaskassign() as $assign){
                    $new_assign = new configuration_process\assign;
                    $new_assign->setValid($assign->getValid() ? $assign->getValid() : null);
                    $new_assign->setCreatedby($assign->getCreatedby());
                    $new_assign->setUsertype($assign->getUsertype());
                    $entityManager->persist($new_assign);
                    $entityManager->flush();
                    $new_task->setTaskassign($new_assign);
                    $entityManager->flush();
                  }

                  $field_loop = new field_loop();

                  foreach($task->getTaskfield() as $field){
                    $add_data = $field_loop->setLoopfield($entityManager,$processDb,$field,$new_task,true);
                    if($add_data){
                      $collect_data[] = $add_data;
                    }
                   }

                return ["task_id"=>$new_task->getId(),"collect_data"=>$collect_data];
            }

}
