<aside class="sidebar">
    <nav class="menu mt-3">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="day_records.php">
                    <i class="icofont-ui-check mr-2"></i>
                    Registrar Ponto
                </a>
            </li>
            <li class="nav-item">
                <a href="monthly_report.php">
                    <i class="icofont-ui-calendar mr-2"></i>
                    Relatório Mensal
                </a>
            </li>
            <?php if($user->is_admin): ?>
            <li class="nav-item">
                <a href="manager_report.php">
                    <i class="icofont-chart-histogram mr-2"></i>
                    Relatório Gerencial
                </a>
            </li>
            <li class="nav-item">
                <a href="users.php">
                    <i class="icofont-users mr-2"></i>
                    Usuários
                </a>
            </li>
            <?php endif ?>
        </ul>
    </nav>
    <div class="sidebar-widgets">
        <div class="sidebar-widget">
            <i class="icon icofont-hour-glass text-primary"></i>
            <div class="info">
                <span class="main text-primary"
                    <?=
                        //definindo a classe para permitir atualização automática do relógio
                        //variável recebida pelo loader.php
                        $activeClock === 'workedInterval' ? 'active-clock' : '' ?>>
                    <?=
                        //imprimindo o total de horas trabalhadas
                        //variável recebida pelo loader.php
                        $workedInterval ?>
                </span>
                <span class="label text-muted">Horas Trabalhadas</span>
            </div>
        </div>
        <div class="division my-3"></div>
        <div class="sidebar-widget">
            <i class="icon icofont-ui-alarm text-danger"></i>
            <div class="info">
                <span class="main text-danger"
                    <?=
                        //definindo a classe para permitir atualização automática do relógio
                        //variável recebida pelo loader.php
                        $activeClock === 'exitTime' ? 'active-clock' : '' ?>>
                    <?=
                        //imprimindo o horário mínimo para saída
                        //variável recebida pelo loader.php
                        $exitTime ?>
                </span>
                <span class="label text-muted">Hora de Saída</span>
            </div>
        </div>
    </div>
</aside>