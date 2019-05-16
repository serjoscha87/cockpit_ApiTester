<?php

namespace Cockpit\Controller;

class ApiTester extends \Cockpit\AuthController {

   public function index() {

      if (!$this->module('cockpit')->hasaccess('cockpit', 'apitester')) {
         return $this->helper('admin')->denyRequest();
      }

      // add acount data in order to be able to read the current user's api token
      $account = $this->app->storage->findOne('cockpit/accounts', ['_id' => $this->user['_id']]);
      unset($account["password"]);
      
      $querylog = $this->storage->find("cockpit/querylog", [
         'sort' => ['_created' => -1]
      ])->toArray(); // queries from db
      
      return $this->render('apitester:views/index.php', compact('account', 'querylog'));
   }

   public function savequery() {
      if ($data = $this->param("query_data", false)) {

         $data["_modified"] = time();

         if (!isset($data['_id'])) {
            $data["_created"] = $data["_modified"];
         }

         $this->app->storage->save("cockpit/querylog", $data);

         return json_encode($data);
      }

      return false;
   }
   
   public function removequery() {

      $entry = $this->param("entry", false);
   
      if ($entry === "-1") { // remove all log entries
         $this->app->storage->remove("cockpit/querylog");
         return [];
      }
      else {
         // remove only one entry defined by id
         $this->app->storage->remove("cockpit/querylog", ["_id" => $entry]);
         
         return $querylog = $this->storage->find("cockpit/querylog", [
            'sort' => ['_created' => -1]
         ])->toArray();
      }

      return false;
   }

}
