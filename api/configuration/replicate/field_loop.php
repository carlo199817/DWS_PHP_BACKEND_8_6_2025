<?php
class field_loop {

    public function setLoopfield($entityManager,$processDb,$field,$task,$main_db){

        $new_field = new configuration_process\field;
        $field = $entityManager->find(configuration_process\field::class,$field->getId());

            $type = $entityManager->find(configuration_process\field_type::class,$field->getFieldtype());
            $new_field->setQuestion($field->getQuestion());
            $new_field->setAnswer($field->getAnswer());
            $new_field->setFormula($field->getFormula());
            $new_field->setRowno($field->getRowno());
            $new_field->setColno($field->getColno());
            $new_field->setRowoccupied($field->getRowoccupied());
            $new_field->setColoccupied($field->getColoccupied());
            $new_field->setActivatestyle($field->getActivatestyle());
            $new_field->setStyle($field->getStyle());
            $new_field->setFieldtype($type->getId());
            $new_field->setUsertype($field->getUsertype());

            if($main_db){
		$check_math = new change_math();
                $entityManager->persist($new_field);
                $entityManager->flush();
                $check_math->change_math($new_field->getId(),$entityManager);
                $entityManager->flush();
                $task->setTaskfield($new_field);
                $entityManager->flush();
            }else{
                $check_math = new change_math();
                $task = $processDb->find(configuration_process\task::class,$task->getId());
                $processDb->persist($new_field);
                $processDb->flush();
                $check_math->change_math($new_field->getId(),$processDb);
                $processDb->flush();
                $task->setTaskfield($new_field);
                $processDb->flush();
            }

            $new_type = $entityManager->find(configuration_process\field_type::class, $new_field->getFieldtype());
            if($new_type->getDescription()==="CHOICE" ||$new_type->getDescription()==="MATH" ||
                $new_type->getDescription() ==="INPUT"||$new_type->getDescription()==="CONDITION"||
                $new_type->getDescription()==="CONTAINER" ){
             return [ $field->getId() => $new_field->getId()];
        }
    }
}
