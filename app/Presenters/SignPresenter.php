<?php

namespace App\Presenters;

use App\BasePresenter;
use Nette;
use	Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;


class SignPresenter extends BasePresenter
{

    /**
     * @throws Nette\Application\AbortException
     */
    public function actionOut(): void
    {
		$this->user->logout(TRUE);
		$this->session->destroy();

		$this->flashMessage('Logout successful', 'success');
		$this->redirect('in');
	}

	/**
	 * @return Form
	 */
	public function createComponentLoginForm(): Form
    {
		$form = new Form;

		$form->addText('email', 'Email:')
			->setRequired('Type your email');

		$form->addPassword('password', 'Password:')
			->setRequired('Type your password');

		$form->addSubmit('submit', 'Login');
		$form->onSuccess[] = [$this, 'login'];

		return $form;
	}

    /**
     * @param Form $form
     * @param Nette\Utils\ArrayHash $data
     * @throws Nette\Application\AbortException
     */
	public function login(Form $form, Nette\Utils\ArrayHash $data): void
    {
        try {
            $this->authenticator->login($data->email, $data->password);

            $this->flashMessage('Login successful', 'success');
            $this->redirect('Homepage:');

        } catch (AuthenticationException $e) {
            $this->flashMessage($e->getMessage(), 'danger');
            $this->redirect('this');
        }
    }
}
