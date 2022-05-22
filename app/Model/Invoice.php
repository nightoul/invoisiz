<?php

namespace App\Model;

use Nette\Database\Row;

class Invoice extends BaseModel
{
    public const TABLE_NAME = 'invoice';
    public const REL_TABLE_NAME = 'invoice_item';


	public function getInvoices(): array
    {
        return $this->database->table(self::TABLE_NAME)->fetchAll();
	}


    /**
     * @param int|string|null $id
     * @return Row
     */
    public function getInvoice(int|string $id = null): Row
    {
        return $this->database->query('SELECT title, client_id, due_date, issue_date, var_symbol, description, charge_per_hour, hours_sum
                FROM invoice INNER JOIN invoice_item WHERE invoice.id = ?', $id)->fetch();
    }


    /**
     * @param $contractorId
     * @param $data
     * @return void
     */
	public function addInvoice($contractorId, $data): void
    {
        $newInvoice = $this->database->table(self::TABLE_NAME)
			->insert([
				'title' => $data->title,
				'contractor_id' => $contractorId,
				'client_id' => $data->client,
				'issue_date' => $data->issue_date,
				'due_date' => $data->due_date,
				'var_symbol' => $data->var_symbol,
			]);

        $this->database->table(self::REL_TABLE_NAME)->insert([
            'invoice_id' => $newInvoice->id,
            'description' => $data->description,
            'hours_sum' => $data->hours_sum,
            'charge_per_hour' => $data->charge_per_hour,
        ]);
	}

    /**
     * @param int|string $id
     * @param $data
     * @return void
     */
    public function editInvoice(int|string $id, $data): void
    {
        $this->database->table(self::TABLE_NAME)->get($id)->update([
            'title' => $data->title,
            'contractor_id' => $id,
            'client_id' => $data->client,
            'issue_date' => $data->issue_date,
            'due_date' => $data->due_date,
            'var_symbol' => $data->var_symbol,
        ]);

        $this->database->table(self::REL_TABLE_NAME)->where('invoice_id', $id)->update([
            'description' => $data->description,
            'hours_sum' => $data->hours_sum,
            'charge_per_hour' => $data->charge_per_hour
        ]);
    }

    public function deleteInvoice($invoiceId): void
    {
        $this->database->table(self::TABLE_NAME)->where('id', $invoiceId)->delete();
        $this->database->table(self::REL_TABLE_NAME)->where('invoice_id', $invoiceId)->delete();
    }
}
