<?php

namespace App\Controllers;

use App\Models\UserModel;

class Main extends BaseController
{
    protected UserModel $userModel;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->userModel = model(UserModel::class);
    }

    public function login()
    {
        if (session()->get('authenticated')) {
            return redirect()->to(site_url('products/index'));
        }

        $data = [
            'title'        => 'Login',
            'main_content' => ['login-view'],
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email'    => 'trim|required',
                'password' => 'required',
            ];

            if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;

                return view('templates/main_template', $data);
            }

            $email    = (string) $this->request->getPost('email');
            $password = (string) $this->request->getPost('password');
            $user     = $this->userModel->login($email, $password);

            if ($user) {
                session()->set([
                    'id'              => $user->id,
                    'first_name'      => $user->first_name,
                    'last_name'       => $user->last_name,
                    'authenticated'   => true,
                ]);

                return redirect()->to(site_url('products/index'));
            }

            session()->setFlashdata('message', 'Invalid email or password');

            return redirect()->to(site_url('login'));
        }

        return view('templates/main_template', $data);
    }

    public function logout()
    {
        session()->remove('authenticated');
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}
