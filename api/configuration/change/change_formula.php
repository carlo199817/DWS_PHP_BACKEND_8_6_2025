<?php


class change_formula{

            public function replaceKeyValue($value, $mapping) {
                list($key, $val) = explode('<', $value);
                $new_key = isset($mapping[$key]) ? $mapping[$key] : $key;
                $new_val = isset($mapping[$val]) ? $mapping[$val] : $val;
                return $new_key . '<' . $new_val;
            }

     public function setChangeformula($entityManager,$processDb,$field,$collect_data) {

            $collect_data = $collect_data;
            $field = $processDb->find(configuration_process\field::class,$field);
            $new_type = $entityManager->find(configuration_process\field_type::class, $field->getFieldtype());
            if($new_type){
             if($new_type->getDescription()==="MATH"){

                $selectanswer = json_decode($field->getFormula(), true);
               if($selectanswer){
            foreach($selectanswer['choices'] as $index => $divisor){
            $mapping = [];
            foreach ($collect_data as $item) {
                foreach ($item as $key => $value) {
                    $mapping[$key] = $value;
                }
            }
            $original_keys = explode(",", $selectanswer['choices'][$index]['value']);
            $new_values = [];
            foreach ($original_keys as $key) {
                if (isset($mapping[$key])) {
                    $new_values[] = $mapping[$key];
                } else {
                    $new_values[] = $key;
                }
            }
            $new_string = implode(",", $new_values);
            $selectanswer['choices'][$index]['value']=$new_string;
            $field->setFormula(json_encode($selectanswer));
            $processDb->flush();
            }
          }
        }else if($new_type->getDescription()==="GRADE"){

        $selectanswer = json_decode($field->getFormula(), true);
        $original_array = $selectanswer['choices'];
        $mapping = [];
        foreach ($collect_data as $item) {
            foreach ($item as $key => $value) {
                $mapping[$key] = $value;
            }
        }

            foreach ($original_array as &$item) {
             $item['value'] = $this->replaceKeyValue($item['value'], $mapping);
            }


            $selectanswer['choices']=$original_array;
            $field->setFormula(json_encode($selectanswer));
            $processDb->flush();

        }else if($new_type->getDescription()==="CONDITION"){
                $selectanswer = json_decode($field->getFormula(), true);
        if($selectanswer){
        foreach($selectanswer['choices'] as $index => $divisor){
            $mapping = [];
            foreach ($collect_data as $item) {
                foreach ($item as $key => $value) {
                    $mapping[$key] = $value;
                }
            }

            $original_keys = explode(",", $selectanswer['choices'][$index]['value']);
            $new_values = [];
            foreach ($original_keys as $key) {
                if (isset($mapping[$key])) {
                    $new_values[] = $mapping[$key];
                } else {
                    $new_values[] = $key;
                }
            }
              $new_string = implode(",", $new_values);
              $selectanswer['choices'][$index]['value']=$new_string;

              $original_keys = explode(",",json_decode($selectanswer['answer'],true)['value']);
              $new_values = [];

              foreach ($original_keys as $key) {
                if (isset($mapping[$key])) {
                    $new_values[] = $mapping[$key];
                } else {
                    $new_values[] = $key;
                }
              }

              $new_string = implode(",", $new_values);
              $answer_data = json_decode($selectanswer['answer'], true);
              $answer_data['value'] = $new_string;
              $selectanswer['answer'] = json_encode($answer_data);
              $field->setFormula(json_encode($selectanswer));
              $processDb->flush();
         }
       }
     }else if($new_type->getDescription()==="CONTAINER"){

        $selectanswer = json_decode($field->getFormula(), true);
        if($selectanswer){
            foreach($selectanswer['choices'] as $index => $divisor){
            $mapping = [];
            foreach ($collect_data as $item) {
                foreach ($item as $key => $value) {
                    $mapping[$key] = $value;
              }
            }
          }
        }

            $original_keys = explode(",", $selectanswer['choices'][$index]['value']);
            $new_values = [];
            foreach ($original_keys as $key) {
                if (isset($mapping[$key])) {
                    $new_values[] = $mapping[$key];
                } else {
                    $new_values[] = $key;
                }
            }
              $new_string = implode(",", $new_values);
              $selectanswer['choices'][$index]['value']=$new_string;
              $field->setFormula(json_encode($selectanswer));
              $processDb->flush();

     }else if($new_type->getDescription()==="DUPLICATE"){

        $selectanswer = json_decode($field->getFormula(), true);
        if($selectanswer){

        foreach($selectanswer['choices'] as $index => $divisor){
            $mapping = [];
            foreach ($collect_data as $item) {
                foreach ($item as $key => $value) {
                    $mapping[$key] = $value;
                }
              }
            }

            $original_keys = explode(",", $selectanswer['choices'][$index]['value']);
            $new_values = [];
            foreach ($original_keys as $key) {
                if (isset($mapping[$key])) {
                    $new_values[] = $mapping[$key];
                } else {
                    $new_values[] = $key;
                }
            }
              $new_string = implode(",", $new_values);
              $selectanswer['choices'][$index]['value']=$new_string;

            $original_keys = explode(",", $selectanswer['choices'][$index]['container_id']);
            $new_values = [];
            foreach ($original_keys as $key) {
                if (isset($mapping[$key])) {
                    $new_values[] = $mapping[$key];
                } else {
                    $new_values[] = $key;
                }
            }
              $new_string = implode(",", $new_values);
              $selectanswer['choices'][$index]['container_id']=$new_string;

              $field->setFormula(json_encode($selectanswer));
              $processDb->flush();
           }

      }
     }
   }
 }
