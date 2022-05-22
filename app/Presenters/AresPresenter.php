<?php

declare(strict_types=1);

namespace App\Presenters;

use App\BasePresenter;
use App\Model\Client;
use Defr\Ares;
use JsonException;
use Nette\Application\AbortException;


final class AresPresenter extends BasePresenter
{
    /** @var Client @inject */
    public Client $client;

    /**
     * @throws Ares\AresException
     * @throws AbortException
     * @throws JsonException
     */
    public function actionDefault(): void
    {
        $ares = new Ares();
        $aresRecord = $ares->findByIdentificationNumber($this->getHttpRequest()->getUrl()->getQueryParameter('ico'));
        $this->client->addClient($aresRecord);
        $this->sendJson(json_encode($aresRecord, JSON_THROW_ON_ERROR));


//        try {
//            $ares = new Ares();
//            $aresRecord = $ares->findByIdentificationNumber($this->getHttpRequest()->getUrl()->getQueryParameter('ico'));
//            $this->client->addClient($aresRecord);
//            $this->sendJson($aresRecord);
//        } catch (\Exception $exception) {
//            $this->sendJson($exception->getMessage());
//        }

    }
}
