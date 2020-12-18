<?php

//model referente à tabela users do banco de dados
class User extends Model {

    //nome da tabela no banco de dados
    protected static $tableName = 'users';

    //lista de atributos da tabela
    protected static $columns = [
        'id',
        'name',
        'password',
        'email',
        'start_date',
        'end_date',
        'is_admin'
    ];

    //função que retorna a quantidade de usuários ativos
    public static function getActiveUsersCount() {
        return static::getCount(['raw' => 'end_date IS NULL']);
    }

    //função que insere um registro no banco de dados
    public function insert() {
        
        //validando os dados
        $this->validate();
        
        //populando o atributo is_admin com 1 ou 0 para adequar ao banco (tinyint(1))
        $this->is_admin = $this->is_admin ? 1 : 0;
        
        //se o id estiver vazio, preenche id com null para adequar ao banco (auto_increment)
        if(!$this->id) $this->id = null;
        
        //se o end_date estiver vazio, preenche com null para adequar ao banco (date)
        if(!$this->end_date) $this->end_date = null;
        
        //criptografando a senha
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
                
        //executando a função genérica de inserção
        return parent::insert();
    }

    //função que altera um registro no banco de dados
    public function update() {
        
        //validando os dados
        $this->validate();
        
        //populando o atributo is_admin com 1 ou 0 para adequar ao banco (tinyint(1))
        $this->is_admin = $this->is_admin ? 1 : 0;
        
        //se o end_date estiver vazio, preenche com null para adequar ao banco (date)
        if(!$this->end_date) $this->end_date = null;
        
        //criptografando a senha
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        //executando a função genérica de atualização
        return parent::update();
    }

    //função que realiza a validação dos dados recebinos pelo formulário de save_users
    private function validate() {
        
        //inicializando o array de erros
        $errors = [];

        //se o nome não estiver digitado:
        if(!$this->name) {
            
            //preenche o atributo name com a mensagem de erro
            $errors['name'] = 'Nome é um campo obrigatório.';
        }

        //se o e-mail não estiver digitado:
        if(!$this->email) {
            
            //preenche o atributo email com a mensagem de erro
            $errors['email'] = 'Email é um campo abrigatório.';
        }
        
        //se o e-mail não for válido:
        elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            
            //preenche o atributo email com a mensagem de erro
            $errors['email'] = 'Email inválido.';
        }

        //se a data de admissão não estiver digitada:
        if(!$this->start_date) {
            
            //preenche o atributo start_date com a mensagem de erro
            $errors['start_date'] = 'Data de Admissão é um campo abrigatório.';
        }
        
        //se a data de admissão não estiver no padrão dd/mm/aaaa
        elseif(!DateTime::createFromFormat('Y-m-d', $this->start_date)) {
            
            //preenche o atributo start_date com a mensagem de erro
            $errors['start_date'] = 'Data de Admissão deve seguir o padrão dd/mm/aaaa.';
        }

        //se a data de desligamento estiver preenchida e não estiver no padrão dd/mm/aaaa
        if($this->end_date && !DateTime::createFromFormat('Y-m-d', $this->end_date)) {
            
            //preenche o atributo end_date com a mensagem de erro
            $errors['end_date'] = 'Data de Desligamento deve seguir o padrão dd/mm/aaaa.';
        }

        //se a senha não estiver digitada:
        if(!$this->password) {
            
            //preenche o atributo password com a mensagem de erro
            $errors['password'] = 'Senha é um campo obrigatório.';
        }

        //se a confirmação de senha não estiver digitada:
        if(!$this->confirm_password) {
            
            //preenche o atributo confirm_password com a mensagem de erro
            $errors['confirm_password'] = 'Confirmação de Senha é um campo abrigatório.';
        }

        //se a confirmação de senha não coincidir com a senha:
        if($this->password && $this->confirm_password 
            && $this->password !== $this->confirm_password) {
                
            //preenche os atributos password e confirm_password com a mensagem de erro
            $errors['password'] = 'As senhas não são iguais.';
            $errors['confirm_password'] = 'As senhas não são iguais.';
        }

        //se existirem erros de validação:        
        if(count($errors) > 0) {
            
            //lança uma exceção
            throw new ValidationException($errors);
        }
    }
}
