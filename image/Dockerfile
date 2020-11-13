#usa a imagem base do ubuntu
FROM ubuntu:20.04

#atualizando os pacotes
RUN apt-get update

#instalando a ferramenta wget
RUN apt-get install wget -y

#baixando o instalador do xampp
RUN wget "https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/7.4.11/xampp-linux-x64-7.4.11-0-installer.run/download" -O xampp-installer.run 

#definindo permissões de execução para o instalador do xampp
RUN chmod +x xampp-installer.run

#instalando o xampp
RUN ./xampp-installer.run

#removendo o instalador
RUN rm -rf xampp-installer.run

#permitindo acesso externo ao phpmyadmin (opcional)
RUN sed -ri -e 's!Require local!Require all granted!g' /opt/lampp/etc/extra/httpd-xampp.conf

#criando a estrutura de pastas do projeto
RUN mkdir /opt/lampp/htdocs/innout
RUN mkdir /opt/lampp/htdocs/innout/app
RUN mkdir /opt/lampp/htdocs/innout/app/public

#alterando a pasta a ser servida pelo apache
ENV APACHE_DOCUMENT_ROOT=/opt/lampp/htdocs/innout/app/public
RUN sed -ri -e 's!/opt/lampp/htdocs!${APACHE_DOCUMENT_ROOT}!g' /opt/lampp/etc/httpd.conf

#desativando a exibicao de erros
RUN sed -ri -e 's!display_errors=On!display_errors=Off!g' /opt/lampp/etc/php.ini

#definindo permissoes na pasta do projeto
RUN chmod -R 777 ${APACHE_DOCUMENT_ROOT}

#definindo permissoes na pasta de sessao php
RUN chmod -R 777 /opt/lampp/temp

#definindo a pasta de trabalho
WORKDIR ${APACHE_DOCUMENT_ROOT}
