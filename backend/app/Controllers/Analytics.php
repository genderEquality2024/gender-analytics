<?php namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;
use App\Models\AnalyticsModel;
use \Firebase\JWT\JWT;

class Analytics extends BaseController
{
    public function __construct(){
        //Models
        $this->analyticsModel = new AnalyticsModel();
    }

    public function addAnalyticsData(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        $data = json_decode(json_encode($data), true);
        
        $query = $this->analyticsModel->insert($data);

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

    public function getGraphAnalytics(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        
        $query = $this->analyticsModel->getAllYearRangeData([
            "yearFrom" => $data->yearFrom,
            "yearTo" => $data->yearTo,
            "term" => $data->term,
        ]);
        // print_r($data);
        // exit();
        
    }

    public function getAllAnalyticsData(){

        // $header = $this->request->getHeader("");
        
        $list = [];
        $list['list'] = $this->analyticsModel->get()->getResult();

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

    public function getDashboard(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        $list = [];
        $graduates = 0;
        $undergraduates = 0;
        $enrollment = 0;
        $employement = 0;
        $vacant = 0;
        $chart = [];
        $enChart = [];

        $query = $this->analyticsModel->getDashboardAnalytics([
            "yearFrom" => $data->yearFrom,
            "yearTo" => $data->yearTo
        ]);

        
        foreach ($query as $key => $value){
            if($value->reportType === "graduate"){
                $graduates += (int)$value->male;
                $graduates += (int)$value->female;
                $undergraduates += (int)$value->undergrad;

                // Series generate
                $chart[$value->course][$key] = (object)[
                    "group" => (object)[
                        "title"=> $value->term ." - ". $value->yearFrom ."-". $value->yearTo,
                        "cols"=> 2,
                    ],
                    "series" => [
                        (object)[
                            "x" =>  "Male",
                            "fillColor" =>  "#3b82f6",
                            "y" =>  (int)$value->male,
                        ],
                        (object)[
                            "x" =>  "Female",
                            "fillColor" =>  "#f43f5e",
                            "y" =>  (int)$value->female,
                        ],
                    ]
                ];
            } else if($value->reportType === "enrollment"){
                $enrollment += (int)$value->male;
                $enrollment += (int)$value->female;

                // Series generate
                $enChart[$value->course][$key] = (object)[
                    "group" => (object)[
                        "title"=> $value->term ." - ". $value->yearFrom ."-". $value->yearTo,
                        "cols"=> 2,
                    ],
                    "series" => [
                        (object)[
                            "x" =>  "Male",
                            "fillColor" =>  "#3b82f6",
                            "y" =>  (int)$value->male,
                        ],
                        (object)[
                            "x" =>  "Female",
                            "fillColor" =>  "#f43f5e",
                            "y" =>  (int)$value->female,
                        ],
                    ]
                ];
            } else if($value->reportType === "employee"){
                $employement += (int)$value->male;
                $employement += (int)$value->female;
                $vacant += (int)$value->vacant;
            }


            
        }    
        
        $list = [
            'graduates' => $graduates,
            'undergrads' => $undergraduates,
            'enrollment' => $enrollment,
            'employee' => $employement,
            'vacant' => $vacant,
            'dataAnalytics' => $chart,
            'enrollAnalytics' => $enChart,
        ];

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