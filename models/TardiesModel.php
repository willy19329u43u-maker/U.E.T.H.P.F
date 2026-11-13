<?php
require_once __DIR__ . '/Model.php';
class TardiesModel extends Model {
    protected $table = 'tardies';
    protected $useSoftDeletes = true;
}