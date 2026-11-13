<?php
require_once __DIR__ . '/Model.php';
class GradeModel extends Model {
    protected $table = 'grades';
    protected $useSoftDeletes = true;
}