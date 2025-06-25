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
         }else{
            $processDb->persist($new_form);
            $processDb->flush();
         }
         $insert_new_formula = new change_formula();
         foreach ($form->getFormtask() as $index => $task) {
              $task_loop = new task_loop();
              $new_task_id = $task_loop->setLooptask($index,$task->getId(),$entityManager,$processDb,$collect_data);
              $collect_data = $new_task_id['collect_data'];
              $new_task = $entityManager->find(configuration_process\task::class,$new_task_id['task_id']);
              $new_form->setFormtask($new_task);
              $entityManager->flush();
         }

         foreach ($new_form->getFormtask() as $new_task) {
                  foreach($new_task->getTaskfield() as $field){
                    $insert_new_formula->setChangeformula($entityManager,$field->getId(),$collect_data);
              }
         }

        return $new_form->getId();
    }
}
