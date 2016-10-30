<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Hiject\Model\ProjectModel;
use Hiject\Model\ColumnModel;
use Hiject\Model\ConfigModel;

class BoardTest extends Base
{
    public function testCreation()
    {
        $p = new ProjectModel($this->container);
        $columnModel = new ColumnModel($this->container);
        $c = new ConfigModel($this->container);

        // Default columns

        $this->assertEquals(1, $p->create(array('name' => 'UnitTest1')));
        $columns = $columnModel->getList(1);

        $this->assertTrue(is_array($columns));
        $this->assertEquals(4, count($columns));
        $this->assertEquals('Backlog', $columns[1]);
        $this->assertEquals('Ready', $columns[2]);
        $this->assertEquals('Work in progress', $columns[3]);
        $this->assertEquals('Done', $columns[4]);

        // Custom columns: spaces should be trimed and no empty columns
        $input = '   column #1  , column #2, ';

        $this->assertTrue($c->save(array('board_columns' => $input)));
        $this->container['memoryCache']->flush();
        $this->assertEquals($input, $c->get('board_columns'));

        $this->assertEquals(2, $p->create(array('name' => 'UnitTest2')));
        $columns = $columnModel->getList(2);

        $this->assertTrue(is_array($columns));
        $this->assertEquals(2, count($columns));
        $this->assertEquals('column #1', $columns[5]);
        $this->assertEquals('column #2', $columns[6]);
    }
}