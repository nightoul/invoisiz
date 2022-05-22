<?php

namespace App\Model;

use Nette\Security\AuthenticationException,
	Nette\Security\Identity,
	Nette\Security\Passwords,
    Nette\Security\User,
    Nette\Database\Context;

class Authenticator
{
	/** @var Context  */
	private Context $database;

	/** @var User  */
	private User $user;

	/** @var Passwords  */
	private Passwords $passwords;


	/**
	 * @param Context $database
	 * @param User $user
	 * @param Passwords $passwords
	 */
	public function __construct(Context $database, User $user, Passwords $passwords)
    {
		$this->database = $database;
		$this->user = $user;
		$this->passwords = $passwords;
	}

	/**
	 * @param string $email
	 * @param string $password
	 * @throws AuthenticationException
	 */
	public function login(string $email, string $password): void
    {
		$contractor = $this->database->table('contractor')
			->where('email', $email)
			->where('is_deleted', false)
			->fetch();

        if (!$contractor) {
            throw new AuthenticationException('Invalid username');
        }

        if (!$this->passwords->verify($password, $contractor->password)) {
            throw new AuthenticationException('Invalid password');
        }

		$this->user->login(new Identity($contractor->id));
	}
}
