<main class="content">
	<?php
		
        //renderizando a área do titulo
        renderTitle(
        	'Relatório Mensal',
        	'Acompanhe seu saldo de horas',
        	'icofont-ui-calendar'
        );
	?>
	<div>
		<form class="mb-4" action="#" method="post">
			<div class="input-group">
				<?php
    				//se o usuário logado for administrador:
    				if($user->is_admin):
    			?>
    				<!--Renderiza um combobox com todos os usuários cadastrados-->
					<select name="user" class="form-control mr-2" placeholder="Selecione o usuário...">
						<option value="">Selecione o usuário</option>
						<?php
                            //iterando sobre todos os usuários cadastrados
                            //o array $users vem do controller
							foreach($users as $user) {
							    
							    //caso o usuário da iteração atual seja o usuário logado, provê atributo selected
								$selected = $user->id === $selectedUserId ? 'selected' : '';
								
								//populando o combobox com os usuários cadastrados
								echo "<option value='{$user->id}' {$selected}>{$user->name}</option>";
							}
						?>
					</select>
				<?php
                    endif
                ?>
				<select name="period" class="form-control" placeholder="Selecione o período...">
					<?php
					
                        //iterando sobre os meses dos últimos dois anos
                        //o array $periods vem do controller
						foreach($periods as $key => $month) {
						    
						    //caso o mês da iteração atual seja o $selectedPeriod, provê atributo selected
						    //a variável $selectedPeriod vem do controller
							$selected = $key === $selectedPeriod ? 'selected' : '';
							
							//populando o combobox com os meses dos últimos dois anos
							echo "<option value='{$key}' {$selected}>{$month}</option>";
						}
					?>
				</select>
				<button class="btn btn-primary ml-2">
					<i class="icofont-search"></i>
				</button>
			</div>
		</form>

		<table class="table table-bordered table-striped table-hover">
			<thead>
				<th>Dia</th>
				<th>Entrada 1</th>
				<th>Saída 1</th>
				<th>Entrada 2</th>
				<th>Saída 2</th>
				<th>Saldo</th>
			</thead>
			<tbody>
				<?php
				    
				    //iterando sobre os registros de marcação e populando a tabela
				    //o array $report vem do controller
				    foreach($report as $registry):
				?>
					<tr>
						<td><?= formatDateWithLocale($registry->work_date, '%A, %d de %B de %Y') ?></td>
						<td><?= $registry->time1 ?></td>
						<td><?= $registry->time2 ?></td>
						<td><?= $registry->time3 ?></td>
						<td><?= $registry->time4 ?></td>
						<td><?= $registry->getBalance() ?></td>
					</tr>
				<?php
				    endforeach
			    ?>
				<tr class="bg-primary text-white">
					<td>Horas Trabalhadas</td>
					<td colspan="3"><?=
					                   //imprimindo o total de horas trabalhadas no mês   
					                   //a variável $sumOfWorkedTime vem do controller
					                   $sumOfWorkedTime
					                ?></td>
					<td>Saldo Mensal</td>
					<td><?=
                            //imprimindo o saldo de horas em relação ao esperado
                            //a variável $balance vem do controller
                            $balance
                        ?></td>
				</tr>
			</tbody>	
		</table>
	</div>
</main>