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

---

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
---

### Cypress

