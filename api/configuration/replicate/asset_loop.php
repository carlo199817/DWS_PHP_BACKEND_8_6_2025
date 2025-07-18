<?php
class asset_loop {

        public function setAssetloop($entityManager,$processDb){

                $asset_repository = $entityManager->getRepository(configuration_process\asset::class)->findAll();

                $asset_id_list = [];
                 foreach($asset_repository as $asset){
                  if(!$asset->getRemove()){
                     $new_asset = new configuration_process\asset;
                     $new_asset->setDescription($asset->getDescription());
                     $processDb->persist($new_asset);

                        foreach($asset->getAssetequipment() as $equipment){
                          if(!$equipment->getRemove()){
                         $new_equipment = new configuration_process\equipment;
                         $new_equipment->setDescription($equipment->getDescription());
                         $processDb->persist($new_equipment);
                         $processDb->flush();
                         $new_asset->setAssetequipment($new_equipment);
                           foreach($equipment->getEquipmentpart() as $part){
                              if(!$part->getRemove()){
                             $new_part = new configuration_process\part;
                             $new_part->setQuestion($part->getQuestion());
                             $new_part->setDescription($part->getDescription());
                             $processDb->persist($new_part);
                             $processDb->flush();
                             $new_equipment->setEquipmentpart($new_part);
                           }
                         }
                        }
                      }
                     $processDb->flush();
                     array_push($asset_id_list,$new_asset->getId());
                    }
                 }

                return $asset_id_list;
            }

}
