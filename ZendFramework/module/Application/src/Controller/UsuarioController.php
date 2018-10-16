<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsuarioController extends AbstractActionController
{
    private $table;

    public function __construct($gateway)
    {
        $this->table = $gateway;
    }

    public function indexAction()
    {
        return new ViewModel(['email' => 'veniciusmmb@gmail.com']);
    }

    public function visualizarAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        if ($id == 0) {
            return new ViewModel([
                'dados' => $this->table->listar()
            ]);
        } else {
            $model = $this->table->find($id);
            
            return new ViewModel([
                'dados' => $this->table->visualizar($model),
            ]);
        }
    }

    public function cadastrarAction()
    {
        $req = $this->getRequest();

        if ($req->isPost()) {
            $dados = $req->getPost();

            $model = new \Application\Model\Usuario();
            $model->exchangeArray(['email' => $dados['email'], 'senha' => $dados['senha']]);

            $this->table->persistir($model);
        }

        return new ViewModel([
            'teste' =>  isset($dados['email']) ? $dados['email'] : '',
        ]);
    }

    public function excluirAction()
    {
        
        $id = $this->params()->fromRoute('id');
        $model = $this->table->find($id);
        $this->table->excluir($model);

        return $this->redirect()->toRoute('usuario_perfil');
    }

    public function atualizarAction()
    {
        $req = $this->getRequest();
        $id = $this->params()->fromRoute('id');
        $form = new \Application\Form\UsuarioForm();
        if ($req->isPost()) {
            $dados = $req->getPost();
            $form->setData($dados);
            if (!$form->isValid()) {
                die("Preencha o formulário corretamente");
            }
            $model = new \Application\Model\Usuario();
            $model->exchangeArray($form->getData());
            $this->table->atualizar($model);
            return $this->redirect()->toRoute('usuario_perfil');
        } else {
            $model = $this->table->find($id);
            $dados = $this->table->visualizar($model);
            $form->bind($dados);
        }
        return new ViewModel([
            'form' => $form,
        ]);

    }
}













