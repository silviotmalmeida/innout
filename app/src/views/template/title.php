<div class="content-title mb-4">
    <?php
        //se for passado o nome do ícone por parâmetro, o mesmo será exibido na tela
        if($icon) { ?>
        <i class="icon <?= $icon ?> mr-2"></i>
    <?php } ?>
    <div>
        <h1><?=
            //imprime a string de título passada por parâmetro
            $title ?></h1>
        <h2><?=
            //imprime a string de subtítulo passada por parâmetro
            $subtitle ?></h2>
    </div>
</div>