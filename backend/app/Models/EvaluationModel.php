<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluationModel extends Model
{
    protected $table      = 'tblevaluation_templates';
    protected $responseTable      = 'tblevaluation_response';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'order', 
        'title', 
        'questionaire', 
        'noScore', 
        'partlyScore', 
        'yesScore', 
        'toolTip', 
        'eventId', 
        'createdBy',
        'isCounted'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'createdDate';
    // protected $updatedField  = 'updatedDate';
    // protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}