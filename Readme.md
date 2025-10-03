# 📅 Meu Calendário (PHP/MySQL)

Um aplicativo de calendário simples, baseado em conceitos de gerenciamento de compromissos diários, com foco em estabilidade e qualidade de software.

---

## ⚙️ 1. Configuração da Aplicação Principal (`calendar-project`)

Esta seção detalha o setup da aplicação principal (PHP/MySQL) em um ambiente local XAMPP.

### Pré-requisitos
* **Servidor Web:** XAMPP (Apache e MySQL)
* **Tecnologias:** PHP, MySQL, JavaScript, HTML e CSS

### Passos para Configuração Local
1.  **Inicie os Serviços:** Certifique-se de que o **Apache** e o **MySQL** estão em execução no painel de controle do XAMPP.
2.  **Copie a Aplicação:** Copie a pasta **`calendar-project`** para o diretório de documentos do servidor (`C:\xampp\htdocs`).
3.  **Acesse a Aplicação:** Abra o navegador e acesse: `http://localhost/calendar-project/index.php`

---

## 💻 2. Automação de Qualidade (QA) com Cypress

Devido à priorização de tempo e problemas de virtualização local, a automação de testes de API é executada no Node.js/Cypress, validando o backend rodando no XAMPP.

### Estrutura de Diretórios
Os testes estão isolados no diretório **`calendar-qa-tests`** para separar o código de produção do código de teste.

### Pré-requisitos para Testes
1.  **Node.js & npm** (Necessário para o Cypress).
2.  **Projeto Ativo:** O servidor XAMPP deve estar rodando o projeto.

### Como Rodar os Testes de API
1.  **Navegue para o Diretório de Testes:**
    ```bash
    cd calendar-qa-tests
    ```
2.  **Instale o Cypress** (Apenas na primeira vez):
    ```bash
    npm install cypress --save-dev
    ```
3.  **Abra o Cypress Test Runner:**
    ```bash
    npx cypress open
    ```
4.  **Execute a Suíte:** No Test Runner, selecione o arquivo **`api_test_suite.cy.js`** para iniciar a execução dos testes de API.

---

## ☁️ 3. DevOps e Infraestrutura (Próximos Passos)

O projeto está preparado para a transição para containers e cloud.

### Arquivos de Containerização
Os seguintes arquivos estão inclusos, mas ainda não estão sendo utilizados para a execução dos testes:
* **`Dockerfile`**: Configuração do container da aplicação.
* **`docker-compose.yml`**: Orquestração da aplicação com o serviço de banco de dados (Host: `db`).

**Observação de Conexão:**
Para rodar os testes no ambiente local (XAMPP), o arquivo `connection.php` deve usar **`$host = "localhost";`**. A alteração para `$host = "db";` será necessária ao migrar para o Docker-Compose.