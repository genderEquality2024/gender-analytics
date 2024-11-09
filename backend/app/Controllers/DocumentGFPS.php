<?php namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;
use App\Models\DocumentModel;
use \Firebase\JWT\JWT;

class DocumentGFPS extends BaseController
{
    public function __construct(){
        //Models
        $this->documentModel = new DocumentModel();
    }

    public function addDocumentContent(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        $data = json_decode(json_encode($data), true);
        
        $query = $this->documentModel->insert($data);

        if($query){

            $response = [
                'title' => 'Data Added',
                'message' => 'Data successfully added to analytics data'
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