<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php';

$input = (array)json_decode(file_get_contents('php://input'), true);

$databaseName = 'main_db';
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) {

         if($input['database'] === 'process'){
            $databaseName = json_decode(getBearerToken(),true)['database'];
            $dbConnection = new DatabaseConnection($databaseName);
            $entityManager = $dbConnection->getEntityManager();

        }


        function isValidNumber($str) {
            return preg_match('/^-?\d*\.?\d+$/', $str);
         }
        function isValidOperator($value) {
            return $value === '+' || $value === '-' || $value === '*' || $value === '/';
        }
        function isValidExpression($str) {
        $pattern = '#^[+\-*/]\d+$#';
        return preg_match($pattern, $str);
        }
        function evaluateExpression($str) {
            eval('$result = ' . $str . ';');
            return $result;
        }

      $field = $entityManager->find(configuration_process\field::class,$input['field_id']);

    if($field){
        $result = 0;
        $operator = "";

            $select_answer = json_decode($field->getFormula(), true);
            $count = count($select_answer['selectoperation']);
                          for ($i = 0; $i < $count; $i++) {

                    if (isset($select_answer['selectoperation'][$i]['value'])) {

                             if($select_answer['selectoperation'][$i]['value']==="OVEN"){

                                $collect = 0;
                                $get=[];
                                $indicesArray = explode(',', $value['choices'][$i]['value']);
                                foreach ($indicesArray as $index) {
                                    $field = $entityManager->find(configuration_process\field::class,$index);
                                   if($field){
                                    $selectanswer = json_decode($field->getFormula(), true);
                                    array_push($get,$selectanswer[0]['formula']);
                                   }
                                }
                    if(count($get)>=1){
                    $result = $get[0];
                    }

             }else if($select_answer['selectoperation'][$i]['value']==="DISPLAY"){

                                $collect = 0;
                                $get=[];
                                $indicesArray = explode(',', $select_answer['choices'][$i]['value']);
                                foreach ($indicesArray as $index) {
                                    $field = $entityManager->find(configuration_process\field::class,$index);
                                   if($field){
                                    $selectanswer = json_decode($field->getFormula(), true);

                                    array_push($get,$field->getAnswer());
                                   }
                                }

        if(count($get)>=1){
        $result = $get[0];
        }
        }else if($select_answer['selectoperation'][$i]['value']==="DURATION"){

                                $collect = 0;
                                $time = [];
                                $indicesArray = explode(',', $select_answer['choices'][$i]['value']);
                                foreach ($indicesArray as $index) {
                                    $field = $entityManager->find(configuration_process\field::class,$index);
                                   if($field){
                                    $selectanswer = json_decode($field->getFormula(), true);
                                    array_push($time,$field->getAnswer());
                                   }
                                }
                  if (count($time) >= 2) {
                    $timezone = new DateTimeZone('America/New_York');
                    $startTime = new DateTime('today ' . $time[0], $timezone);
                    $endTime = new DateTime('today ' . $time[1], $timezone);
                    $interval = $startTime->diff($endTime);
                    $totalMinutes = ($interval->h * 60) + $interval->i;
                    $result = $totalMinutes."m";
                 }else{
                $result = "";
                }
                            }else if($select_answer['selectoperation'][$i]['value']==="SUM"){

                                $collect = 0;
                                $indicesArray = explode(',', $select_answer['choices'][$i]['value']);
                                foreach ($indicesArray as $index) {
                                    $field = $entityManager->find(configuration_process\field::class,$index);
                                    if($field){
                                    $selectanswer = json_decode($field->getFormula(), true);
                                    if (isValidNumber($selectanswer['answer'])) {
                                        $collect+=$selectanswer['answer'];
                                    }
                                  }
                                }
                            if($operator==="/"){
                                $result/=$collect;
                            }else if($operator==="+"){
                                $result+=$collect;
                            }else if($operator==="*"){
                                $result*=$collect;
                            }else if($operator==="-"){
                                $result-=$collect;
                            }else{
                                $result+=$collect;
                            }

                              }else if($select_answer['selectoperation'][$i]['value']==="SUBTRACT"){
                                $collect = 0;
                                $indicesArray = explode(',', $select_answer['choices'][$i]['value']);
                                $counter = 0;
                                foreach ($indicesArray as $index) {
                                    $field = $entityManager->find(configuration_process\field::class,$index);
                                    $selectanswer = json_decode($field->getFormula(), true);
                                    if (isValidNumber(json_decode($selectanswer['answer'], true))) {

                                           if ($counter == 0) {
                                          $collect-=json_decode($selectanswer['answer'], true);
                                           }else{
                                          $collect-=json_decode($selectanswer['answer'], true);
                                           }
                                       $counter++;
                                    }
                                }
                                if($operator==="/"){
                                    $result/=$collect;
                                }else if($operator==="+"){
                                    $result+=$collect;
                                }else if($operator==="*"){
                                    $result*=$collect;
                                }else if($operator==="-"){
                                    $result-=$collect;
                                }else{
                                    $result+=$collect;
                                }
                            }else if($select_answer['selectoperation'][$i]['value']==="MULTIPLY"){
                                  $collect = 1;
                                $indicesArray = explode(',', $select_answer['choices'][$i]['value']);
                                $counter = 0;
                                foreach ($indicesArray as $index) {
                                   $field = $entityManager->find(configuration_process\field::class,$index);
                                    $selectanswer = json_decode($field->getFormula(), true);
                                        if (isValidNumber(json_decode($selectanswer['answer'], true))) {
                                           if ($counter == 0) {
                                          $collect*=json_decode($selectanswer['answer'], true);
                                           }else{
                                          $collect*=json_decode($selectanswer['answer'], true);
                                           }
                                     $counter++;
                                    }
                                }
                                if($operator==="/"){
                                    $result/=$collect;
                                }else if($operator==="+"){
                                    $result+=$collect;
                                }else if($operator==="*"){
                                    $result*=$collect;
                                }else if($operator==="-"){
                                    $result-=$collect;
                                }else{
                                    $result+=$collect;
                                }
                            }else if($select_answer['selectoperation'][$i]['value']==="DIVIDE"){
                                try {
                                    $collect = 0;
                                    $indicesArray = explode(',', $select_answer['choices'][$i]['value']);
                                    $counter = 0;
                                    foreach ($indicesArray as $index) {
                                         $field = $entityManager->find(configuration_process\field::class,$index);
                                         $selectanswer = json_decode($field->getFormula(), true);
                                        if (isValidNumber(json_decode($selectanswer['answer'], true))) {
                                            if ($counter == 0) {
                                             $collect+=json_decode($selectanswer['answer'], true);
                                              }else{
                                             $collect/=json_decode($selectanswer['answer'], true);
                                            }
                                     $counter++;
                                     }
                                    }
                                    if($operator==="/"){
                                        $result/=$collect;
                                    }else if($operator==="+"){
                                        $result+=$collect;
                                    }else if($operator==="*"){
                                        $result*=$collect;
                                    }else if($operator==="-"){
                                        $result-=$collect;
                                    }else{
                                        $result+=$collect;
                                    }

                                } catch (DivisionByZeroError $e) {

                                }
                            }else if($select_answer['selectoperation'][$i]['value']==="NEUTRAL"){

                            if (!isValidOperator($select_answer['choices'][$i]['value'])) {

                                if (isValidExpression($select_answer['choices'][$i]['value'])) {
                                    try {
                                        $result = evaluateExpression($result.$select_answer['choices'][$i]['value']);
                                    } catch (Throwable $e) {
                                    }
                                 }

                            } else{
                                $operator=$selectanswer['choices'][$i]['value'];
                              }

                            }
                            else if($select_answer['selectoperation'][$i]['value']==="COUNT"){
                                $collect = 0;
                                $indicesArray = explode(',', $select_answer['choices'][$i]['value']);
                                foreach ($indicesArray as $index) {
                                    $field = $entityManager->find(configuration_process\field::class,$index);
                                              $selectanswer = json_decode($field->getFormula(), true);
                                    if (isValidNumber($selectanswer['answer'])) {
                                        $collect+=1;
                                    }
                                }
                                if($operator==="/"){
                                    $result/=$collect;
                                }else if($operator==="+"){
                                    $result+=$collect;
                                }else if($operator==="*"){
                                    $result*=$collect;
                                }else if($operator==="-"){
                                    $result-=$collect;
                                }else{
                                    $result+=$collect;
                                }

                            }
                            else if($select_answer['selectoperation'][$i]['value']==="HIGHEST"){
                                $collect = 0;
                                $comp = [];
                                $indicesArray = explode(',', $select_answer['choices'][$i]['value']);
                                foreach ($indicesArray as $index) {
                                  $field = $entityManager->find(configuration_process\field::class,$index);
                                              $selectanswer = json_decode($field->getFormula(), true);
                                    if (isValidNumber($field->getAnswer())) {
                                        array_push($comp,$field->getAnswer());
                                    }

                                }

                                $numeric_values = array_filter($comp, 'is_numeric');
                                if (!empty($numeric_values)) {
                                    $collect = max($numeric_values);
                                }
                                if($operator==="/"){
                                    $result/=$collect;
                                }else if($operator==="+"){
                                    $result+=$collect;
                                }else if($operator==="*"){
                                    $result*=$collect;
                                }else if($operator==="-"){
                                    $result-=$collect;
                                }else{
                                    $result+=$collect;
                                       }

                            }
                            else if($select_answer['selectoperation'][$i]['value']==="HIGHTOTAL"){
                                $collect = 0;
                                $indicesArray = explode(',', $select_answer['choices'][$i]['value']);
                                foreach ($indicesArray as $index) {
                                    $comp = [];
                                    $field = $entityManager->find(configuration_process\field::class,$index);
                                    $selectanswer = json_decode($field->getFormula(), true);
                                    if (isValidNumber($selectanswer['answer'])) {
                                          foreach ($selectanswer['choices'] as $index) {
                                            if (isValidNumber($index['value'])) {
                                                array_push($comp,$index['value']);
                                            }
                                        }
                                    }
                                       $numeric_values = array_filter($comp, 'is_numeric');
                                if (!empty($numeric_values)) {
                                    $collect += max($numeric_values);
                                }
                                $comp = [];

                                }

                                if($operator==="/"){
                                    $result/=$collect;
                                }else if($operator==="+"){
                                    $result+=$collect;
                                }else if($operator==="*"){
                                    $result*=$collect;
                                }else if($operator==="-"){
                                    $result-=$collect;
                                }else{
                                    $result+=$collect;
                                }
                              }


                  }
               }
            }



                       $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
                       $select_answer = json_decode($field->getFormula(), true);
                       $select_answer['answer'] = $result;
                       $field->setFormula(json_encode($select_answer));
                       $field->setAnswer($result);
                       $entityManager->flush();
                       echo json_encode($result);



    }



} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
?>
