<?php namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;
use App\Models\AnalyticsModel;
use App\Models\DocumentModel;
use \Firebase\JWT\JWT;

class Analytics extends BaseController
{
    public function __construct(){
        //Models
        $this->analyticsModel = new AnalyticsModel();
        $this->documentModel = new DocumentModel();
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

    public function getGraphAnalyticOptions(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        
        $query = $data->reportType !== 'employee' ? $this->analyticsModel->getOptionsGraph([
            "schoolYear" => $data->schoolYear,
            "reportType" => $data->reportType,
        ]) : $this->analyticsModel->getOptionsEmployeeGraph([
            "schoolYear" => $data->schoolYear,
            "reportType" => $data->reportType,
        ]);

        $list = [];

        foreach ($query as $key => $value){
            $list[$key] = [
                "name" => $data->reportType !== 'employee' ? $value->course : $value->term,
                "value" => $data->reportType !== 'employee' ? $value->course : $value->term
            ];
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
            "schoolYear" => $data->schoolYear
        ]);
        $resources = $this->documentModel->get()->getResult();


        
        foreach ($query as $key => $value){
            if($value->reportType === "graduate"){
                $graduates += (int)$value->male;
                $graduates += (int)$value->female;

                // Series generate
                // $chart[$value->course][$key]
                $chart[$value->course][$key]["male"] = (int)$value->male;
                $chart[$value->course][$key]["female"] = (int)$value->female;
                $chart[$value->course][$key]["categories"] = $value->term;

            } else if($value->reportType === "enrollment"){
                $enrollment += (int)$value->male;
                $enrollment += (int)$value->female;

                // Series generate
                $enChart[$value->course][$key] = (object)[
                    "group" => (object)[
                        "title"=> $value->term,
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
            'resource' => sizeof($resources),
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

    public function getGraphAnalytics(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();

        $where = [
            "schoolYear" => $data->schoolYear,
            "reportType" => $data->reportType,
            "course" => $data->course,
        ];

        if($data->reportType === 'employee'){
            $where = [
                "schoolYear" => $data->schoolYear,
                "reportType" => $data->reportType,
                "term" => $data->course,
            ];
        }

        $query = $this->analyticsModel->getAllYearRangeData($where);
        $list = [];

        foreach ($query as $key => $value){
            if($data->reportType === 'enrollment'){
                $list[$key] = (object)[
                    "group" => (object)[
                        "title"=> $value->classYear ." - ". $value->term,
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
            } else if($data->reportType === 'employee'){
                // $list[$value->term] = 
                // $list[$key] = $value;
                $list[$key] = (object)[
                    "group" => $value->course,
                    "series" => (object)[
                        "name" =>  $value->course,
                        "data" =>  [(int)$value->male, (int)$value->female, (int)$value->vacant],
                    ]
                ];
            } else if($data->reportType === 'graduate'){
                $list[$key]["male"] = (int)$value->male;
                $list[$key]["female"] = (int)$value->female;
                $list[$key]["categories"] = $value->term;
            }
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