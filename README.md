## Tecnologias
- MySQL 8 (https://www.mysql.com/)
- PHP 7.4 (https://www.php.net/)
- Laravel 8.0 (https://lumen.laravel.com/)
- Docker (https://www.docker.com/)
- Nginx (https://www.nginx.com/)

## Instalação
Você pode rodar esse projeto usando o [Docker Compose](https://docs.docker.com/compose/install/).
```sh
$ docker-compose up  -d
```
A instalação das dependências, criação do .env, execução dos migrations e seed são feitas automaticamente. Você pode acompanhar pelo log se todos os passos foram executados corretamente digitando 

```sh
> $ docker logs desafio_laravel.php
```

Agora você deve ser capaz de visitar a página da aplicação http://localhost/ e começar a usar o sistema

## Objetivo

Está é uma API RESTFul que simula a transferência de dinheiro entre usuário e loja

Regras:

- Para ambos tipos de usuário, temos Nome Completo, CPF, e-mail e Senha. CPF/CNPJ e e-mails são únicos no sistema. Sendo assim, o sistema permite apenas um cadastro com o mesmo CPF ou endereço de e-mail.

- Usuários podem enviar dinheiro (efetuar transferência) para lojistas e entre usuários. 

- Lojistas **só recebem** transferências, não enviam dinheiro para ninguém.

- Antes de finalizar a transferência, consultamos um serviço autorizador externo, esse serviço nos informa se a transferência foi autorizada ou não
    ```json
    {
        "message" : "Autorizado"
    }
    ```
    Mock para simular o serviço (https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6).

- A operação de transferência é uma transação, ou seja, revertida em qualquer caso de inconsistência, retornando o dinheiro para a carteira do usuário que enviou. 

- No recebimento de pagamento, o usuário ou lojista recebe uma notificação enviada por um serviço de terceiro e eventualmente este serviço pode estar indisponível/instável, por isso, usamos um serviço de fila para enviar essa notificação. 
    ```json
    {
        "message" : "Enviado"
    }
    ```
    Mock para simular o envio (https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04). 

## Endpoints
- `GET users`, listar todos os usuários
- `GET users/{id}`, mostrar detalhe de usuário específico
- `POST users`, cadastrar usuário
- `PUT users/{id}`, atualizar usuário
- `DELETE users/{id}`, deletar usuário
- `POST transactions`, realizar uma transferência do saldo da conta de um usuário para outro
 
### Para mais detalhe visite a documentação completa [API Documentation](https://github.com/cmparrela/user-transaction-laravel-api/wiki/API-Documentation)