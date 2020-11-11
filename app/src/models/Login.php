<?php

class Login extends Model {
    
    //função que verifica os dados de login fornecidos pelo formulário
    //em caso positivo, retorna um objeto user com os dados do usuário logado
    //em caso negativo, lança uma exceção
    public function checkLogin() {
        
        //validando os dados recebidos pelo formulário
        $this->validate();

        //função que pesquisa no banco o usuário pelo e-mail e cria um objeto coms os dados do usuário
        //esta informação de email foi recebida pelo formulário de login
        $user = User::getOne(['email' => $this->email]);
        
        //se o usuário informado existir, faz a comparação da senha
        if($user) {

            //verifica se é um usuário ativo
            if($user->end_date) {
                throw new AppException('Usuário está desligado da empresa.');
            }

            //comparação da senha passada pelo formulário de login e a senha no banco de dados
            if(password_verify($this->password, $user->password)) {
                return $user;
            }
        }
        throw new AppException('Usuário e Senha inválidos.');
    }

    //função auxiliar que valida os dados recebidos pelo formulário
    public function validate() {
        
        //array de detalhamento de erros. começa vazio
        $errors = [];

        //se o email não foi informado, popula o array de erros
        if(!$this->email) {
            $errors['email'] = 'E-mail é um campo obrigatório.';
        }

        //se a senha não foi informada, popula o array de erros
        if(!$this->password) {
            $errors['password'] = 'Por favor, informe a senha.';
        }

        //se existir algum erro, lança uma exceção
        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}