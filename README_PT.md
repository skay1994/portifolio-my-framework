[English Version](https://github.com/skay1994/portifolio-my-framework)

# Meu Framework

Um projeto criado para estudo e portfolio, baseado em outros frameworks PHP e suas estrategias e recursos.

## Objetivo

Esse é um projeto criado para estudo, para que eu possa visualizar, analisar e reconstruir 
funcionalidades de outros frameworks. 

Aprimorar o meu entendimento de como a linguagem PHP funciona e no caminho melhorar os produtos, sistemas que desenvolvo.

Estou usando o Github Projects para gerenciamento das tarefas: [Projeto](https://github.com/users/skay1994/projects/1/views/1)

## Influencias

A ideia inicial é uma replica do Laravel que é o framework que tenho maior compreensão e domínio. 

Porem não exclui a possibilidade de incluir recursos de outros frameworks existentes.

## Requisitos

O sistema está sendo construído com PHP 8.3. Vou subindo a versão minima com base nos novos lançamentos do PHP

## Recursos

Os recursos estão sendo criados com o tempo.

 - [x] Service Container: O service container ja está funcional e testado, está em estado inicial com os seguintes recursos:
   - [x] Class Resolver: O container vai criar uma instancia da classe e devolver a instancia.
   - [x] Constructor Parameter: Caso a classe possua parametros no construtor, eles serão resolvidos/injetados, 
caso seja outras classes, o container vai repetir o processo para retornar a instancia para o construtor da classe inicial.
   - [x] Bindings: Cria um vinculo a uma string a uma instancia de classe/função/outros, para facil recuperação em qualquer local da aplicação

 - [x] Facades: São classes para facilitar o acesso a outras classes, como atalhos.
 - [x] Rotas: Um sistema de rotas usando os atributos do PHP 8
   - [x] Rotas Exatas
   - [x] Rotas com parametros
   - [ ] Nome de Rotas
   - [x] Rotas com multiplos metodos HTTP
   - [ ] Grupo de Rotas

 - [ ] Database
   - [ ] QueryBuilder
   - [ ] ORM

## Tests

O código está sendo feito com testes via PestPHP.

##  Aplicativos

Aplicativos com esse framework:

 - Aplicação Base: [my-application](https://github.com/skay1994/portifolio-my-application/blob/master/README_PT.md)