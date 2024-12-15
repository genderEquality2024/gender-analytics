<?php namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;
use App\Models\AuthModel;
use CodeIgniter\RESTful\ResourceController;

class BackupController extends BaseController
{
    public function __construct(){
        //Models
        $this->authModel = new AuthModel();
        
        helper('download');
    }

    public function backupDatabase(){
        //Get API Request Data from NuxtJs
        $dbutil = \Config\Database::utils();
        $prefs = array(
            'tables'        => array('tbluser'),   // Array of tables to backup.
            'ignore'        => array(),                     // List of tables to omit from the backup
            'format'        => 'gzip',                       // gzip, zip, txt
            'filename'      => 'mybackup.sql',              // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
            'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
            'newline'       => "\n"                         // Newline character used in backup file
        );
        $dbname = 'backup-on-' . date('Y-m-d') . '.txt';
        $backup = $dbutil->backup($prefs);
        force_download($dbname, $backup);
        // $data = $this->request->getJSON(); 
        // $hasPass = sha1($data->password);

        // //Select Query for finding User Information
        // $user = $this->authModel->where(['id' => $data->id, 'password' => $hasPass])->get()->getRow();
        
        // //Set Api Response return to the FE
        // if($user){
        //     $dbname = 'backup-on-' . date('Y-m-d') . '.zip';
        //     $backup = $this->dbutil->backup();
        //     force_download($dbname, $backup);
        // } else {
        //     $response = [
        //         'error' => 401,
        //         'title' => 'Backup Denied',
        //         'message' => 'Unauthoried user is trying to backup'
        //     ];

        //     return $this->response
        //             ->setStatusCode(401)
        //             ->setContentType('application/json')
        //             ->setBody(json_encode($response));
        // }
    }
    

}