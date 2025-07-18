<?php

class form_loop {

    public function setFormloop($entityManager, $processDb, $form_id,$main_db)
    {

        $field_loop = new field_loop();
        $collect_data = [];

        $form = $entityManager->find(configuration_process\form::class, $form_id);

        $new_form = new configuration_process\form;

        $new_form->setTitle($form->getTitle());
        $new_form->setParentform($form->getId());
        $new_form->setRemark($form->getRemark());
        $new_form->setCreatedby($form->getCreatedby());
        $timezone = new DateTimeZone('Asia/Manila');
        $date = new DateTime('now', $timezone);
        $new_form->setDatecreated($date);
        $new_form->setFormtype($form->getFormtype());
        $new_form->setVersion($form->getVersion());

         if($main_db){
            $entityManager->persist($new_form);
            $entityManager->flush();
            $new_form = $entityManager->find(configuration_process\form::class, $new_form->getId());
         }else{
            $processDb->persist($new_form);
            $processDb->flush();
            $new_form = $processDb->find(configuration_process\form::class, $new_form->getId());
         }

         $insert_new_formula = new change_formula();
         foreach ($form->getFormtask() as $index => $task) {
              $task_loop = new task_loop();
              $new_task_id = $task_loop->setLooptask($task->getId(),$entityManager,$processDb,$collect_data,$main_db);
              $collect_data = $new_task_id['collect_data'];

             if($main_db){
               $new_task = $entityManager->find(configuration_process\task::class,$new_task_id['task_id']);
               $new_form->setFormtask($new_task);
               $entityManager->flush();
              }else{
               $new_task = $processDb->find(configuration_process\task::class,$new_task_id['task_id']);
               $new_form->setFormtask($new_task);
               $processDb->flush();
              }
         }

          foreach ($new_form->getFormtask() as $new_task) {
                  foreach($new_task->getTaskfield() as $field){
                    if($main_db){
                    $insert_new_formula->setChangeformula($entityManager,$entityManager,$field->getId(),$collect_data);
                    }else{
                    $insert_new_formula->setChangeformula($entityManager,$processDb,$field->getId(),$collect_data);
                    }
            }
         }

        return $new_form->getId();
    }
}
