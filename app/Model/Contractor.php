<?php

namespace App\Model;

use Nette\Database\Table\ActiveRow;

class Contractor extends BaseModel
{
    public const TABLE_NAME = 'contractor';


	public function getContractors(): array
    {
        return $this->database->table(self::TABLE_NAME)
			->select( 'id, CONCAT( first_name, " ", last_name ) AS name, email' )
            ->where('is_deleted', false)
            ->fetchAll();
	}

    /**
     * @param int|null $contractor_id
     * * @return ActiveRow|null
     */
	public function getContractor(int $contractor_id = null): ?ActiveRow
    {
		return $this->database->table(self::TABLE_NAME)
			->where('id', $contractor_id)
			->where('is_deleted', false)
			->fetch();
	}


    /**
     * @param $data
     * @return ActiveRow|null
     */
	public function addContractor($data): ?ActiveRow
    {
        return $this->database->table(self::TABLE_NAME)
			->insert([
				'first_name' => $data->first_name,
				'last_name' => $data->last_name,
				'ico' => $data->ico,
				'email' => $data->email,
				'bank_account' => $data->bank_account,
				'street' => $data->street,
				'zip_code' => $data->zip_code,
				'town' => $data->town,
			]);
	}

    /**
     * @param int|string $id
     * @param $data
     * @return void
     */
	public function editContractor(int|string $id, $data): void
    {
        $this->database->table(self::TABLE_NAME)->get($id)->update($data);
	}


    public function isSuperAdmin(int $id, bool $isSuperAdmin): void
    {
        $this->database->table(self::TABLE_NAME)->where('id', $id)->update(['is_superadmin' => $isSuperAdmin]);
    }
}
