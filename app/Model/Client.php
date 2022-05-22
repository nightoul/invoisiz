<?php

namespace App\Model;

use Nette\Database\Table\ActiveRow;

class Client extends BaseModel
{
    public const TABLE_NAME = 'client';


	public function getClients(): array
    {
        return $this->database->table(self::TABLE_NAME)
            ->where('is_deleted', false)
            ->fetchAll();
	}

    public function getClientNames(): array
    {
        return $this->database->table(self::TABLE_NAME)
            ->where('is_deleted', false)
            ->fetchPairs('id', 'name');
	}

    /**
     * @param int|string|null $id
     * * @return ActiveRow|null
     * @return ActiveRow|null
     */
	public function getClient(int|string $id = null): ?ActiveRow
    {
		return $this->database->table(self::TABLE_NAME)
			->where('id', $id)
			->where('is_deleted', false)
			->fetch();
	}


    /**
     * @param $aresRecord
     * @return ActiveRow|null
     */
	public function addClient($aresRecord): ?ActiveRow
    {
        return $this->database->table(self::TABLE_NAME)
			->insert([
				'name' => $aresRecord->companyName,
				'ico' => $aresRecord->companyId,
                'street' => $this->aresHelper->getStreet($aresRecord),
                'zip_code' => $aresRecord->zip ?? null,
                'town' => $aresRecord->town ?? null
			]);
	}

    /**
     * @param int|string $id
     * @param $data
     * @return void
     */
	public function editClient(int|string $id, $data): void
    {
        $this->database->table(self::TABLE_NAME)->get($id)->update($data);
	}
}
