<?php

declare(strict_types=1);

namespace App\Presenters;

use App\BasePresenter;
use Exception;
use Nette\Application\AbortException;


final class ClientPresenter extends BasePresenter
{

    public function actionDefault(): void
    {
        $this->template->clients = $this->clients;
    }

    public function actionDetail($id): void
    {
        $this->template->client = $this->client->getClient($id);
    }

    /**
     * @throws AbortException
     */
    public function actionEditwithajax(): void
    {
        $data = $this->getHttpRequest()->getPost();
        $this->client->editClient($data['clientId'], ['email' => $data['clientEmail']]);
        $this->redrawControl('clientsList');
        $this->terminate();
    }


    /**
     * @throws AbortException
     */
    public function handleDeleteClient($id): void
    {
        try {
            $this->client->deleteItem($id, $this->client::TABLE_NAME);
            $this->flashMessage('Client successfully deleted', 'success');
        } catch (Exception $e) {
//            $this->flashMessage("Something went wrong", "danger");
            $this->flashMessage($e->getMessage());
        }
        finally {
            $this->redrawControl('flashMessages');
            $this->redirect('this');
        }
    }
}
