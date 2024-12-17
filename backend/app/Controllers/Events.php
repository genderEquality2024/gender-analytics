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
        $data->eventDate = json_encode($data->eventDate);
        $data = json_decode(json_encode($data), true);
        
        $query = $this->eventModel->insert($data);

        if($query){

            $response = [
                'title' => 'Data Added',
                'message' => 'Event successfully added'
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

    public function getListEvents(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        
        $query = $this->eventModel->where([
            "month" => $data->month,
            "year" => $data->year,
        ])->get()->getResult();

        $list = [];

        foreach ($query as $key => $value){
            $list[$key] = $value;
        }

        if($list){
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($list));
        } else {
            $response = [
                'title' => 'Error',
                'message' => 'No Data Found'
            ];

            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
        
    }

}