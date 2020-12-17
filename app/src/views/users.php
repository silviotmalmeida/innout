<main class="content">
    <?php
    
        //renderizando a área do titulo
        renderTitle(
            'Cadastro de Usuários',
            'Mantenha os dados dos usuários atualizados',
            'icofont-users'
        );

        //inclui o template com mensagens
        //responsavel por popular o array $errors a partir da SESSION
        include(TEMPLATE_PATH . "/messages.php");
    ?>
    
    <!-- link para a ação de cadastrar -->
    <!-- redireciona para a página save_user -->
    <a class="btn btn-lg btn-primary mb-3"
        href="save_user.php">Novo Usuário</a>

    <table class="table table-bordered table-striped table-hover">
        <thead>
            <th>Nome</th>
            <th>Email</th>
            <th>Data de Admissão</th>
            <th>Data de Desligamento</th>
            <th>Ações</th>
        </thead>
        <tbody>
            <?php
                //iterando sobre o array
                //o array $users vem do controller
                foreach($users as $user):
            ?>
                <tr>
                    <!-- Preenchendo os dados na tabela -->
                    <td><?= $user->name ?></td>
                    <td><?= $user->email ?></td>
                    <td><?= $user->start_date ?></td>
                    <td><?= $user->end_date ?></td>
                    <td>
                        <!-- link para a ação de editar -->
                        <!-- redireciona para a página save_user passando o id do usuário pelo atributo update -->
                        <a href="save_user.php?update=<?= $user->id ?>" 
                            class="btn btn-warning rounded-circle mr-2">
                            <i class="icofont-edit"></i>
                        </a>
                        <!-- link para a ação de excluir -->
                        <!-- redireciona para a página atual(users) passando o id do usuário pelo atributo delete -->
                        <a href="?delete=<?= $user->id ?>"
                            class="btn btn-danger rounded-circle">
                            <i class="icofont-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php
                endforeach
            ?>
        </tbody>
    </table>
</main>