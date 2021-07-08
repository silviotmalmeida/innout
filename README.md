# Projeto INNOUT
## Projeto Final construído durante o curso "PHP 7 Completo - Curso do Desenvolvedor Web 2020 + Projetos" da COD3R.

Trata-se da implementação de sistema de ponto eletrônico.

O projeto encontra-se dockerizado para facilitar a implantação. As orientações para execução estão listadas abaixo:

* Executar o comando "sudo ./dockerBuild.sh" em um terminal na pasta do projeto/image;

* Executar o comando "sudo ./startContainers.sh" em um terminal na pasta do projeto;

* Carregar os usuários iniciais do sistema utilizando a URL "localhost/phpmyadmin", executando as instruções sql presentes no arquivo app/extras/db.sql;

* O sistema estará disponível na URL "localhost".

****
Os usuários iniciais criados são:
- 	login: admin@cod3r.com.br, password: a;
- 	login: chaves@cod3r.com.br, password: a;
- 	login: barriga@cod3r.com.br, password: a;
- 	login: madruga@cod3r.com.br, password: a;
- 	login: quico@cod3r.com.br, password: a;
***

* Para encerrar a execução utiliza-se o comando "sudo ./stopContainers.sh";

Foram incluídos diversos comentários para facilitar o entendimento do código.
