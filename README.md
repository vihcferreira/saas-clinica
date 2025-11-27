# SaaS Clínica

Bem-vindo ao projeto **SaaS Clínica**. Este é um sistema de gerenciamento de clínicas desenvolvido em Laravel.

## Sobre o Projeto

Este projeto visa fornecer uma plataforma para gerenciamento de múltiplas clínicas (SaaS), permitindo o cadastro de pacientes, agendamento de consultas e gestão de equipe médica.

## Tecnologias Utilizadas

-   **Laravel 12.x**
-   **PHP 8.2+**
-   **MySQL / SQLite**
-   **Vite**

## Credenciais de Acesso (Ambiente de Desenvolvimento)

O projeto já vem com usuários pré-configurados no `DatabaseSeeder` para facilitar os testes. A senha padrão para todos os usuários abaixo é `password`.

| Função                      | Nome              | E-mail               | Senha      |
| :-------------------------- | :---------------- | :------------------- | :--------- |
| **Dono (Owner)**            | Carlos Dono       | `dono@clinica.com`   | `password` |
| **Staff (Recepção)**        | Ana Recepcionista | `staff@clinica.com`  | `password` |
| **Médico**                  | Dr. Roberto       | `medico@clinica.com` | `password` |
| **Intruso (Outra Clínica)** | João Intruso      | `intruso@outra.com`  | `password` |

### Como Rodar o Projeto

1. Instale as dependências do PHP:

    ```bash
    composer install
    ```

2. Instale as dependências do Node.js:

    ```bash
    npm install
    ```

3. Configure o arquivo `.env`:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Rode as migrações e os seeders (para criar os usuários acima):

    ```bash
    php artisan migrate --seed
    ```

5. Inicie o servidor de desenvolvimento:

    ```bash
    php artisan serve
    ```

6. Em outro terminal, inicie o Vite (para assets):
    ```bash
    npm run dev
    ```
