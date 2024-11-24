<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalyticsModel extends Model
{
    protected $table      = 'tblanalytics';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'course', 
        'schoolYear', 
        'yearFrom', 
        'yearTo', 
        'reportType', 
        'term', 
        'classYear', 
        'male',
        'female',
        'vacant',
        'createdBy'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'createdDate';
    // protected $updatedField  = 'updatedDate';
    // protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getDashboardAnalytics($where){

        $query = $this->db->table($this->table)->where($where)->get();
        $results = $query->getResult();

        return $results;
    }
    public function getAllYearRangeData($where){

        $query = $this->db->table($this->table)->where($where)->get();
        $results = $query->getResult();

        return $results;
    }

    public function getOptionsGraph($where){

        $sql = "SELECT course FROM ".$this->table." WHERE schoolYear = :schoolYear: AND reportType = :reportType: GROUP BY course";
       
        $query = $this->db->query($sql, $where);
        $results = $query->getResult();

        return $results;
    }

}