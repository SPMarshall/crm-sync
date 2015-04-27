<?php namespace App\Services;

use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'email' => 'required|email|max:100|unique:users',
			'inn' => 'required|max:11|unique:users',
			'fio' => 'required|max:100',
			'company_name' => 'required|max:150',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		return User::create([
			'email' => $data['email'],
			'inn' => $data['inn'],
			'fio' => $data['fio'],
			'company_name' => $data['company_name'],
		]);
	}

}
