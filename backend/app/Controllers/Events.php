<?php namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;
use App\Models\EventModel;
use \Firebase\JWT\JWT;

class Events extends BaseController
{
    public function __construct(){
        //Models
        $this->eventModel = new EventModel();
    }

    public function addEventCalendar(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        $data = json_decode(json_encode($data), true);
        
        $query = $this->eventModel->insert($data);

        if($query){

            $response = [
                'title' => 'Data Added',
                'message' => 'Data successfully added to event evaluation questionaire'
            ];
 
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
            
        } else {
            $response = [
                'error' => 400,
                'title' => 'Registration Failed!',
                'message' => 'Please check your data.'
            ];
 
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
        // print_r($data);
        // exit();
        
    }

}