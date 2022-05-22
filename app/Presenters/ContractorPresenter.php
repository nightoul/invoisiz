<?php

namespace App\Presenters;

use App\BasePresenter;
use JsonException;
use	Nette\Application\UI\Form;
use Nette\Application\AbortException;
use Exception;


class ContractorPresenter extends BasePresenter
{
    public function actionDefault(): void
    {
        $this->template->contractors = $this->contractorModel->getContractors();
    }


    /**
     * @throws AbortException
     * @throws JsonException
     */
    public function actionEdit($id): void
    {
        $contractor = $this->contractorModel->getContractor($id);
        if (!$contractor) {
            $this->flashMessage('This contractor does not exist. Redirecting.', 'danger');
            $this->redirect('default');
        }

        $this->template->permissionsIds = array_keys($this->permission->getPermissions($id));
        $this->template->permissionPresets = json_encode($this->permission->getPermissionPresets(), JSON_THROW_ON_ERROR);

        $this['contractorForm']->setDefaults($contractor->toArray());
    }


    /**
     * @return Form
     */
    public function createComponentContractorForm(): Form
    {
        $form = new Form;

        $form->addText('first_name', 'Name')
            ->setRequired('Type your name');

        $form->addText('last_name', 'Surname')
            ->setRequired('Type your surname');

        $form->addInteger('ico', 'IČO')
            ->setRequired('Type your IČO');

        $form->addText('email', 'Email')
            ->addRule(Form::EMAIL, 'Invalid email')
            ->setRequired('Type your email');

        if ($this->getAction() !== 'edit') {
            $form->addSelect('permission_presets', 'Permission presets:', array_keys($this->permission->getPermissionPresets()))
                ->setRequired()
                ->setPrompt('-');
        }

        if ($this->getAction() === 'edit') {
            $form->addPassword('password', 'Password:')
                ->setRequired('Type your password');
        }

        $form->addText('bank_account', 'Bank account')
            ->setRequired('Type your bank account');

        $form->addText('street', 'Street')
            ->setRequired('Type your street and number');

        $form->addInteger('zip_code', 'Zip code')
            ->setRequired('Type your bank account');

        $form->addText('town', 'Town')
            ->setRequired('Type your town');

        $form->addSubmit('submit', 'Save');

        $form->onSuccess[] = [$this, 'contractorFormSubmitted'];

        return $form;
    }

    /**
     * @param Form $form
     * @return void
     * @throws AbortException
     */
    public function contractorFormSubmitted(Form $form): void
    {
        $data = $form->getValues();
        try {
            if ($this->getAction() !== 'edit') {
                $contractor = $this->contractorModel->addContractor($data);
                $presets = $this->permission->getPermissionPresets();
                $this->permission->insertPermissions($contractor->id, $presets[array_keys($presets)[$data->permission_presets]]);
                $this->flashMessage('Contractor successfully created.', 'success');
            }

            if ($this->getAction() === 'edit') {
                $data->password = password_hash($data->password, PASSWORD_BCRYPT);
                $this->contractorModel->editContractor($this->getParameter('id'), $data);
                $this->flashMessage('Contractor successfully updated.', 'success');
            }
        }
        catch (Exception $e) {
//            $this->flashMessage('Something went wrong', "danger");
            $this->flashMessage($e->getMessage());
            $this->redirect('this');
        }
        finally {
            $this->redrawControl('flashMessages');
            $this->redirect('Contractor:');
        }
    }


    public function createComponentSetPermissionsForm(): Form
    {
        $form = new Form();

        $form->addSelect('permission_presets', 'Permission presets:', array_keys($this->permission->getPermissionPresets()))
                ->setPrompt('-');

        $allPermissionsSortedByParent = $this->permission->getAllPermissionsSortedByParent();

        foreach ($allPermissionsSortedByParent as $sectionName => $permissions) {
            $form->addGroup($sectionName);
            foreach ($permissions as $p) {
                $form->addCheckbox($p->permission_system_name.'__'.$p->permission_id, $p->permission_human_name)
                    ->setHtmlId($p->permission_id);
            }
        }

        $form->addSubmit('submit', 'Save');
        $form->onSuccess[] = [$this, 'setPermissionsSubmitted'];

        return $form;
    }


    /**
     * @throws AbortException
     */
    public function setPermissionsSubmitted(Form $form): void
    {
        try {
            $this->permission->updatePermissions((int) $this->getParameter('id'), $this->extractPermissionIds($form->getHttpData()));
            $this->flashMessage('Permissions successfully saved', 'success');
            $this->redrawControl('setPermissions');
        } catch (Exception $e) {
//            $this->flashMessage('Something went wrong', "danger");
            $this->flashMessage($e->getMessage());
            $this->redirect('this');
        }
        finally {
            $this->redrawControl('flashMessages');
        }
    }

    private function extractPermissionIds($selectedCheckboxes): array
    {
        $newPermissionIds = [];
        foreach (array_keys($selectedCheckboxes) as $checkbox) {
            $newPermissionIds[] = (int) filter_var($checkbox, FILTER_SANITIZE_NUMBER_INT);
        }

        return array_filter($newPermissionIds);
    }


    /**
     * @throws AbortException
     */
    public function handleDeleteContractor($id): void
    {
        try {
            $this->contractorModel->deleteItem($id, $this->contractorModel::TABLE_NAME);
            $this->permission->deletePermissions($id);
            $this->flashMessage('Contractor successfully deleted', 'success');
        } catch (Exception $e) {
//            $this->flashMessage("Something went wrong", "danger");
            $this->flashMessage($e->getMessage());
        }
        finally {
            $this->redrawControl('flashMessages');
            $this->redirect('this');
        }
	}

    public function handleSetPermissions(): void
    {
        $this->redrawControl('setPermissions');
    }
}



