<?php
namespace App\Mailer;
 
use Cake\Mailer\Mailer;
 
class UserMailer extends Mailer
{
	public function register($user)
	{
		$this->setProfile('default')
		    ->setTo($user->email)
			->setSubject('MyKiji登録完了')
            ->setviewVars(['user' => $user]);

        $this->viewBuilder()->setTemplate('register_mail');
	}

	public function withdrawal($user)
	{
		$this->setProfile('default')
		    ->setTo($user->email)
			->setSubject('MyKiji退会完了')
            ->setviewVars(['user' => $user]);

        $this->viewBuilder()->setTemplate('withdraw_mail');
	}

	public function reissue_password($user, $url)
	{
		$this->setProfile('default')
		    ->setTo($user->email)
			->setSubject('MyKijiパスワード再発行')
            ->setviewVars([
				'user' => $user,
				'url' => $url
			]);

        $this->viewBuilder()->setTemplate('reissue_password_mail');
	}
}