<?php namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;
use App\Models\EvaluationModel;
use \Firebase\JWT\JWT;

class Evaluation extends BaseController
{
    public function __construct(){
        //Models
        $this->evalModel = new EvaluationModel();
    }

    public function addEventQuestionaire(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        $data = json_decode(json_encode($data), true);
        
        $query = $this->evalModel->insert($data);

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

    public function getGraphAnalytics(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        
        
        // print_r($data);
        // exit();
        
    }

}