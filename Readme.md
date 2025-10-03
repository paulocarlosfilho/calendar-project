# Meu Calendário

<p>Um aplicativo de calendário simples para gerenciar eventos e compromissos diários, beseado nos da google.</p>

## Como rodar o projeto

### Pré-requisitos
- Servidor web **XAMPP**
- Navegador web moderno
- PHP, MySQL, JavaScript, HTML, and CSS

### Passos
1. Certifique-se de que o **Apache** está em execução no painel de controle do XAMPP.
2. Copie a pasta `caledar-project` para o diretório `C:\xampp\htdocs`.
3.  Crie o arquivo `index.php`
4. Abra seu navegador e acesse o seguinte endereço:
`http://localhost/caledar-project/index.php` (Esse ponto é facultativo)
5. Criar o arquivo `stylesheet.css`
6. Criar o arquivo `calendar.js`
7. Criar o arquivo `calendar.php`
8. Criar o arquivo `connection.php`
9. Criar o arquivo `fetch_events.php`

<p>Completo! </p>
<p>Agora que finalizamos o projeto, vamos por ele em um docker, e posteriormente, iniciaceremos a por o nosso projeto na AWS.</p>

<hr>

### Docker
<p>O primeiro passo é criar o Dockerfile (Para iniciar apenas esse projeto) e o docker-compose.yml (para iniciar esse projeto e por ele dentro de um compose)</p>

1. Criar o `Dockerfile`
2. Criar o `docker-compose.yml`

<strong>Nota:</strong> Para iniciar como docker e esquecer o XAAMP, é necessario atualizar o arquivo de `connection.php`

<p>$host = "db"; // <--- MUDANÇA AQUI! Não é mais 'localhost'</p>
<p>$username = "root"; </p>
<p>$password = ""; // Se você mudar a senha no docker-compose, mude aqui também</p>
<p>$dbname = "meu_calendario";</p>

<p>$conn = new mysqli($host, $username, $password, $dbname);</p>
---

<strong>Nota:</strong> O docker ta dando erro no meu pc, e eu não com tempo para trocar, formatar, etc... No lugar em trazer para o docker e fazer o terraform para por na aws, eu vou fazer os testes aqui. usarei o `Cypress` que funciona no `node`. Porém, deixarei os arquivos de Dockerfile e docker-compose.yml, pois pretendo contunuar em breve. Devido a esse fim, será necessario voltar o arquivo `connection.php`, removendo o `$host` e deixando como `localhost`.


<p>Em desenvolviomento...</p>

<hr>

### 💻Cypress (Automação de Q.A)

Devido a problemas de virtualização no ambiente local (conforme nota na seção Docker), priorizamos o objetivo principal de Qualidade de Software. A automação de testes de interface (UI) é executada usando o Cypress através do Node.js, validando o projeto rodando no XAMPP.

### Pré-requisitos para Testes
Para executar os testes de Q.A. e confirmar a funcionalidade do calendário:

1. Node.js & npm (Necessário para rodar o Cypress).
2. Servidor web XAMPP (Apache e MySQL devem estar ativos, rodando o projeto em `http://localhost/calendar-project/index.php`).

### Como Rodar os Testes

* Primeiramente, é necessario criar um diretório que será assim `calendar-qa-tests`. Isso é necessario para poder separar o produto da area de testes.

* Agora você entra na pesta criada: `cd calendar-qa-tests`

* Criar duas pastas, a `cypress` e a `e2e` e criar o arquivo `api_fetch_events.cy.js` de forma que fique assim:

`cypress\e2e\api_fetch_events.cy.js`

* Dentro dessa pasta você abre o cmd e digita `npm init -y` - Isso criar o package.json

* Agora voccê preciso instalar o Cypress no seu projeto, digita isso: `npm install cypress --save-dev`

* Em seguida so precisa pedir para abrir o Cypress: `npx cypress open`

Concluido!

<hr>