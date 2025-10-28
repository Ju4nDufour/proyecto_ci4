<?php

namespace App\Controllers;

use App\Models\ProfesorModel;
use App\Models\UserModel;
use CodeIgniter\Shield\Models\UserIdentityModel;

class ProfesoresController extends BaseController
{
    public function index()
    {
        $model = new ProfesorModel();
        $data['profesores'] = $model->findAll();

        return view('profesores/index', $data);
    }

    public function store()
    {
        $model = new ProfesorModel();

        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'email'    => $this->request->getPost('email'),
            'contacto' => $this->request->getPost('contacto'),
            'DNI'      => $this->request->getPost('DNI'),
        ];

        if (! $model->save($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        return redirect()
            ->to('/profesores')
            ->with('ok', 'Profesor agregado con éxito');
    }

    public function update($id)
    {
        $model = new ProfesorModel();

        $profesor = $model->find($id);
        if (! $profesor) {
            return redirect()->to('/profesores')->with('errors', ['Profesor no encontrado.']);
        }

        $model->setValidationRule(
            'DNI',
            "required|regex_match[/^[0-9]{8}$/]|is_unique[profesor.DNI,id_profesor,{$id}]"
        );

        $data = [
            'id_profesor' => $id,
            'nombre'      => $this->request->getPost('nombre'),
            'email'       => $this->request->getPost('email'),
            'contacto'    => $this->request->getPost('contacto'),
            'DNI'         => $this->request->getPost('DNI'),
        ];

        if (! $model->save($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        if (! empty($profesor['user_id'])) {
            $this->syncLinkedUserEmail((int) $profesor['user_id'], (string) $data['email']);
        }

        return redirect()
            ->to('/profesores')
            ->with('ok', 'Profesor actualizado con éxito');
    }

    public function delete($id)
    {
        $model = new ProfesorModel();
        $model->delete($id);

        return redirect()
            ->to('/profesores')
            ->with('ok', 'Profesor eliminado con éxito');
    }

    protected function syncLinkedUserEmail(int $userId, string $email): void
    {
        $email = trim($email);

        if ($email === '') {
            return;
        }

        $userModel = new UserModel();
        $userModel->update($userId, ['email' => $email]);

        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);
        $identity      = $identityModel
            ->where('user_id', $userId)
            ->where('type', 'email_password')
            ->first();

        if ($identity) {
            $identityModel->save([
                'id'     => $identity->id,
                'secret' => $email,
            ]);
        }
    }

}
