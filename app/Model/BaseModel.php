<?php

namespace App\Model;

use App\Helper\AresHelper;
use Nette;
use Nette\Database\Context;

class BaseModel
{
	/** @var Context  */
	protected Context $database;

    protected AresHelper $aresHelper;

    /**
     * @param Nette\Database\Context $database
     * @param AresHelper $aresHelper
     */
	public function __construct(Nette\Database\Context $database, AresHelper $aresHelper)
    {
		$this->database = $database;
        $this->aresHelper = $aresHelper;
	}

    /**
     * @param int|string $id
     * @param $tableName
     * @return void
     */
    public function deleteItem(int|string $id, $tableName): void
    {
        $this->database->table($tableName)->get($id)->update(['is_deleted' => true]);
    }
}
