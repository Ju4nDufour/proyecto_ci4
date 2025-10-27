<?php

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\AlumnoModel;

class AlumnoTest extends CIUnitTestCase
{
    public function testModelCanBeInstantiated()
    {
        $model = new AlumnoModel();
        $this->assertInstanceOf(AlumnoModel::class, $model);
    }
}
