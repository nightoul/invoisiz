<?php

namespace App;

use App\Model\Client;
use App\Model\Contractor;
use Nette;
use App\Model\Authenticator;
use App\Model\Permission;
use Nette\Database\Table\ActiveRow;


abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    /** @var Authenticator @inject */
    public Authenticator $authenticator;

    /** @var Client @inject */
    public Client $client;

    /** @var Permission @inject */
    public Permission $permission;

    /** @var Contractor @inject */
    public Contractor $contractorModel;

    public ActiveRow $contractor;
    public array $allPermissions = [];
    public array $currentPermissions = [];
    public array $clients = [];

    public function startup(): void
    {
		parent::startup();

        if ($this->user->id && $this->user->isLoggedIn()) {
            $this->template->contractor = $this->contractor = $this->contractorModel->getContractor($this->user->id);

            $this->allPermissions = $this->permission->getAllPermissions();
            $this->permission->setCurrentUserId($this->user->id);
            $this->currentPermissions = $this->permission->getPermissions($this->user->getId());

            if (($this->allPermissions === $this->currentPermissions) && !$this->contractor->is_superadmin) {
                $this->contractorModel->isSuperAdmin($this->user->id, true);
            }
            if ($this->contractor->is_superadmin && ($this->allPermissions !== $this->currentPermissions)) {
                $this->permission->updatePermissions($this->user->id, array_keys($this->allPermissions));
                $this->contractorModel->isSuperAdmin($this->user->id, false);
            }
        }

        $this->template->permission = $this->permission;
        $this->clients = $this->client->getClients();
	}


    public function hasPermission(string $permissionSystemName): bool
    {
        return in_array($permissionSystemName, $this->currentPermissions, true);
    }
}
