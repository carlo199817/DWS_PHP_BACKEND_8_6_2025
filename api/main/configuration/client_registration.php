<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Headers:Content-Type, Authorization");

require_once __DIR__ . '/../../../database.php'; 

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);


if($_SERVER['REQUEST_METHOD']==="POST"){ 
  

    if(getBearerToken()){


       $client_repository = $entityManager->getRepository(MainDb\Configuration\client::class);
       $existing_domain = $client_repository->findOneBy(['domain' => $input['domain']]);
       if ($existing_domain) {

        header('HTTP/1.1 409 Conflict');   
        echo json_encode(["Message"=>"Domain already exists"]);

       }else{
       $new_client = new MainDb\Configuration\client;  
       $new_client->setCustomername($input['customer_name']);
       $new_client->setWebsite($input['website']);
       $new_client->setCourtname($input['court_name']); 
       $new_client->setTitle($input['title']);
       $new_client->setDescription($input['description']);
       $new_client->setContactperson($input['contact_person']);
       $new_client->setMobile($input['mobile']);
       $new_client->setEmail($input['email']);
       $new_client->setLogo($input['logo']);
       $new_client->setFavicon($input['favicon']);
       $new_client->setCourts($input['courts']);
       $new_client->setDatabasename($input['database_name']);
       $new_client->setThemecolor($input['theme_color']);
       $new_client->setDomain($input['domain']);
       $new_client->setAddbookingfee($input['add_booking_fee_as_additional_line']);
       $new_client->setProduction($input['production']);    
        $timezone = new DateTimeZone('Asia/Manila');
        $date_created = new DateTime('now', $timezone);
       $new_client->setDatecreated($date_created); 
        $get_user = $entityManager->find(MainDb\Configuration\user::class,json_decode(getBearerToken(),true)['user_id']);
       $new_client->setCreatedby($get_user);   
         $new_membership = new MainDb\Configuration\membership;  
          $new_membership->setMember($input['membership']['will_have_membership']);  
          $new_membership->setOnlymember($input['membership']['only_member_can_book']);
          $new_membership->setMembershipfee($input['membership']['membership_fee']); 
          $new_membership->setCollectmembershipfirstuse($input['membership']['collect_membership_on_first_use']);
          $new_membership->setCollectmembershipendterm($input['membership']['collect_membership_at_the_end_of_term']);
          $new_membership->setAcceptlifetimemembership($input['membership']['accept_lifetime_membership']); 
          $new_membership->setAllownonduespayingmembership($input['membership']['allow_non_dues_paying_membership']);
          $new_membership->setCategoryofnonduepayingmember($input['membership']['category_of_dues_paying_member']);        
        $entityManager->persist($new_membership); 
        $entityManager->flush();  
        $new_client->setMembership($new_membership);   
         $new_rental = new MainDb\Configuration\rental;   
              $new_rates = new MainDb\Configuration\rates;   
               $new_rates->setWeekdayrate($input['court_rental_rates']['for_member']['week_day_rate']);
               $new_rates->setWeeknightrate($input['court_rental_rates']['for_member']['week_night_rate']);
               $new_rates->setWeekholidayrate($input['court_rental_rates']['for_member']['weekend_holiday_day_rate']); 
               $new_rates->setWeekholidaynightrate($input['court_rental_rates']['for_member']['weekend_holiday_night_rate']);
                $entityManager->persist($new_rates); 
                $entityManager->flush();  
          $new_rental->setMember($new_rates);    
               $new_rates = new MainDb\Configuration\rates;   
                $new_rates->setWeekdayrate($input['court_rental_rates']['for_non_member']['week_day_rate']);
                $new_rates->setWeeknightrate($input['court_rental_rates']['for_non_member']['week_night_rate']);
                $new_rates->setWeekholidayrate($input['court_rental_rates']['for_non_member']['weekend_holiday_day_rate']); 
                $new_rates->setWeekholidaynightrate($input['court_rental_rates']['for_non_member']['weekend_holiday_night_rate']);
                 $entityManager->persist($new_rates); 
                 $entityManager->flush();  
          $new_rental->setNonmember($new_rates);   
                 $new_entrance_fees = new MainDb\Configuration\entrance_fees;   
                 $new_entrance_fees->setApplytomember($input['court_rental_rates']['entrance_fee']['apply_to_member']); 
                 $new_entrance_fees->setApplytononmember($input['court_rental_rates']['entrance_fee']['apply_to_non_member']);
                    $new_rates = new MainDb\Configuration\rates;    
                     $new_rates->setWeekdayrate($input['court_rental_rates']['entrance_fee']['for_non_member']['week_day_rate']);
                     $new_rates->setWeeknightrate($input['court_rental_rates']['entrance_fee']['for_non_member']['week_night_rate']);
                     $new_rates->setWeekholidayrate($input['court_rental_rates']['entrance_fee']['for_non_member']['weekend_holiday_day_rate']); 
                     $new_rates->setWeekholidaynightrate($input['court_rental_rates']['entrance_fee']['for_non_member']['weekend_holiday_night_rate']);
                 $new_entrance_fees->setMember($new_rates); 
                 $entityManager->persist($new_rates);  
                 $entityManager->flush();  
                     $new_rates = new MainDb\Configuration\rates;    
                      $new_rates->setWeekdayrate($input['court_rental_rates']['entrance_fee']['for_non_member']['week_day_rate']);
                      $new_rates->setWeeknightrate($input['court_rental_rates']['entrance_fee']['for_non_member']['week_night_rate']);
                      $new_rates->setWeekholidayrate($input['court_rental_rates']['entrance_fee']['for_non_member']['weekend_holiday_day_rate']); 
                      $new_rates->setWeekholidaynightrate($input['court_rental_rates']['entrance_fee']['for_non_member']['weekend_holiday_night_rate']);
                 $new_entrance_fees->setNonmember($new_rates); 
                 $entityManager->persist($new_rates);  
                 $entityManager->flush();  
           $entityManager->persist($new_rental);  
           $entityManager->flush(); 
           $new_client->setRental($new_rental);     
             $new_open_play = new MainDb\Configuration\open_play;  
             $new_open_play->setAllowonepersontobook($input['open_play']['allow_one_person_to_book_multiple_players']);
             $new_open_play->setCustomrate($input['open_play']['custom_rate']); 
              $new_default_rate = new MainDb\Configuration\default_rate; 
               $new_default_rate->setFormember($input['open_play']['default_rate']['member']);
               $new_default_rate->setFornonmember($input['open_play']['default_rate']['non_member']);
                $entityManager->persist($new_default_rate);   
                $entityManager->flush();   
             $new_open_play->setDefaultrate($new_default_rate);
           $entityManager->persist($new_open_play);  
           $entityManager->flush(); 
           $new_client->setOpenplay($new_open_play); 
            foreach($input['payment_method'] as $id){
                $get_payment_method = $entityManager->find(MainDb\Configuration\payment_method::class,$id);
                $new_client->setClientpaymentmethod($get_payment_method);
            }
            foreach($input['timeslot'] as $slot){
                $new_slot = new MainDb\Configuration\slot;  
                 $new_slot->setSlotno($slot['slot_no']);
                 $new_slot->setDescription($slot['description']);
                    $entityManager->persist($new_slot);  
                    $entityManager->flush();  
                $new_client->setSlot($new_slot); 
            }    
 
            $entityManager->persist($new_client);   
            $entityManager->flush();   
            header('HTTP/1.1 201 Created');   
            echo json_encode(["Message"=>"New court ".$input['court_name']." created!"]);
        }
    }
    


}else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}


 