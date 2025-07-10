<?php
class task_loop {

        public function setLooptask($task_id,$entityManager,$processDb,$collect_data,$main_db){

                  $task = $entityManager->find(configuration_process\task::class,$task_id);
                  $new_task = new configuration_process\task;
                  $new_task->setTitle($task->getTitle());
                  $new_task->setDescription($task->getDescription());
                  $new_task->setStatus($task->getStatus());
                  $new_task->setStyle($task->getStyle());
                  $new_task->setRowset($task->getRowset());
                  $new_task->setColset($task->getColset());
                  $new_task->setSeries(0);

                 if($main_db){
                  $entityManager->persist($new_task);
                  $entityManager->flush();
                  $new_task = $entityManager->find(configuration_process\task::class, $new_task->getId());
                 }else{
                  $processDb->persist($new_task);
                  $processDb->flush();
                  $new_task = $processDb->find(configuration_process\task::class, $new_task->getId());
                 }
                  $collect_data[] = [$task->getId()=>$new_task->getId()];
                  foreach($task->getTaskvalidation() as $validation){
                    $new_validation = new configuration_process\validation;
                    $new_validation->setValid($validation->getValid() ? $validation->getValid() : null);
                    $new_validation->setCreatedby($validation->getCreatedby());
                    $new_validation->setUsertype( $validation->getUsertype());

                    if($main_db){
                     $entityManager->persist($new_validation);
                     $entityManager->flush();
                     $new_validation = $entityManager->find(configuration_process\validation::class, $new_validation->getId());
                    }else{
                     $processDb->persist($new_validation);
                     $processDb->flush();
                     $new_validation = $processDb->find(configuration_process\validation::class, $new_validation->getId());
                    }

                    $new_task->setTaskvalidation($new_validation);
                    $entityManager->flush();
                  }

                  foreach($task->getTaskassign() as $assign){
                    $new_assign = new configuration_process\assign;
                    $new_assign->setValid($assign->getValid() ? $assign->getValid() : null);
                    $new_assign->setCreatedby($assign->getCreatedby());
                    $new_assign->setUsertype($assign->getUsertype());

                    if($main_db){
                     $entityManager->persist($new_assign);
                     $entityManager->flush();
                     $new_assign = $entityManager->find(configuration_process\assign::class, $new_assign->getId());
                    }else{
                     $processDb->persist($new_assign);
                     $processDb->flush();
                     $new_assign = $processDb->find(configuration_process\assign::class, $new_assign->getId());
                    }

                    $new_task->setTaskassign($new_assign);
                    $entityManager->flush();
                  }

                  $field_loop = new field_loop();

                  foreach($task->getTaskfield() as $field){
                    $add_data = $field_loop->setLoopfield($entityManager,$processDb,$field,$new_task,$main_db);
                      if($add_data){
                      $collect_data[] = $add_data;
                    }
                  }

                return ["task_id"=>$new_task->getId(),"collect_data"=>$collect_data];
            }

}
