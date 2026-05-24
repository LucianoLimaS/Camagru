# Passo 1: Estrutura MVC e Roteamento Customizado

Neste primeiro passo do desenvolvimento do Camagru, criamos a base arquitetural do projeto utilizando o padrão **MVC (Model-View-Controller)** de forma 100% nativa em PHP, em conformidade com as regras do projeto de não utilizar frameworks ou bibliotecas prontas.

## O que foi desenvolvido

### 1. Núcleo da Aplicação (`src/Core/`)
Criamos as classes fundamentais que dão suporte ao funcionamento do nosso "micro-framework" customizado:

*   **[Autoloader.php](file:///e:/42 rio/Camagru/src/Core/Autoloader.php)**: Um carregador automático de classes baseado no padrão PSR-4. Ele carrega os respectivos arquivos automaticamente dos diretórios `src/Core/` e `src/App/`.
*   **[Router.php](file:///e:/42 rio/Camagru/src/Core/Router.php)**: Roteador flexível baseado em expressões regulares. Ele mapeia rotas HTTP (GET/POST) e redireciona rotas inválidas para o tratamento de erro 404 personalizado.
*   **[Controller.php](file:///e:/42 rio/Camagru/src/Core/Controller.php)**: Classe abstrata base com métodos de apoio para renderização de views em um layout geral (`render`), redirecionamento e respostas JSON.
*   **[Database.php](file:///e:/42 rio/Camagru/src/Core/Database.php)**: Singleton de conexão PDO ativa com o MariaDB, tratando falhas críticas através da renderização da página de erro 500 personalizada.
*   **[Model.php](file:///e:/42 rio/Camagru/src/Core/Model.php)**: Classe base de modelo para injeção automática de banco de dados.
*   **[Env.php](file:///e:/42 rio/Camagru/src/Core/Env.php)**: Utilitário nativo para carregamento de variáveis de ambiente do arquivo `.env`.

### 2. Camada da Aplicação (`src/App/`)
Estruturamos as pastas do aplicativo para separar a lógica das telas e dados:

*   **Controllers**:
    *   **[HomeController.php](file:///e:/42 rio/Camagru/src/App/Controllers/HomeController.php)**: Lida com a homepage pública e de usuário do Camagru.
    *   **[ErrorController.php](file:///e:/42 rio/Camagru/src/App/Controllers/ErrorController.php)**: Lida centralizadamente com a geração e despacho dos cabeçalhos HTTP e templates visuais de erro.
*   **Views**:
    *   **[layout.php](file:///e:/42 rio/Camagru/src/App/Views/layout.php)**: Estrutura HTML5 padrão e estilização CSS global aplicada a todo o site.
    *   **views/home/index.php**: Página inicial estilizada do Camagru.
    *   **views/errors/**: Contém as views [400.php](file:///e:/42 rio/Camagru/src/App/Views/errors/400.php), [404.php](file:///e:/42 rio/Camagru/src/App/Views/errors/404.php) e [500.php](file:///e:/42 rio/Camagru/src/App/Views/errors/500.php) para erros de requisição, página não encontrada e falha no servidor, respectivamente.

### 3. Configurações de Roteamento Web (`src/.htaccess` e Apache)
*   **[index.php](file:///e:/42 rio/Camagru/src/index.php)**: Front Controller que inicializa a sessão, registra o autoloader, carrega o `.env`, define as rotas registradas e dispara o Roteador.
*   **[.htaccess](file:///e:/42 rio/Camagru/src/.htaccess)**: Redireciona requisições não físicas para o Front Controller.
*   **[Dockerfile](file:///e:/42 rio/Camagru/docker/web/Dockerfile)**: Atualizado para habilitar `AllowOverride All` no Apache para execução do `.htaccess`.

---

## Tratamento de Erros Personalizados (400, 404 e 500)
Para manter o design premium integrado e evitar mensagens genéricas ou vazamentos de logs de erros do PHP ao cliente, unificamos o fluxo de erros HTTP utilizando o `ErrorController`:

1.  **400 Bad Request**: Acionado nos controllers quando uma requisição falha nas validações básicas. Exibe o template vermelho/laranja com feedback.
2.  **404 Not Found**: Disparado pelo `Router.php` sempre que uma rota solicitada não coincide com as rotas registradas. Exibe o template roxo com botão de retorno para a home.
3.  **500 Internal Server Error**: Acionado pelo `Database.php` quando há falha de conexão com o banco ou exceções não capturadas. O erro é gerado enviando o código HTTP correspondente no cabeçalho e exibindo uma página de erro limpa para o cliente sem expor credenciais ou segredos técnicos do servidor.

---

## Fluxo de Funcionamento do MVC

Abaixo está o ciclo de vida completo de uma requisição HTTP dentro do nosso framework customizado, desde o momento em que o usuário digita a URL até o retorno do conteúdo para a tela dele.

### Diagrama de Sequência do Ciclo de Vida da Requisição

```text
           [ Usuário no Navegador ]
                      │
                      ▼ (Acessa URL: ex: /admin)
         [ Servidor Apache / .htaccess ]
                      │
                      ▼ (Redireciona URLs dinâmicas)
             [ src/index.php ]
                      │
        ┌─────────────┴─────────────┐
        ▼                           ▼
[ Carrega Autoloader ]      [ Instancia Router ]
                                    │
                                    ▼ (Registra e compara rotas)
                            [ Core\Router ]
                                    │
                    ┌───────────────┴───────────────┐
                    │ (Rota encontrada)             │ (Rota 404)
                    ▼                               ▼
         [ Controller::action ]            [ ErrorController::show ]
                    │                               │
        ┌───────────┴───────────┐                   │
        ▼                       ▼                   ▼
 [ Model / Database ]   [ Controller::render ]      │
        │                       │                   │
        └───────────┬───────────┘                   │
                    ▼                               ▼
            [ Renderiza View ]             [ Renderiza View 404 ]
                    │                               │
                    └───────────────┬───────────────┘
                                    ▼
                          [ Aplica Layout.php ]
                                    │
                                    ▼ (HTML Completo)
                         [ Retorno ao Navegador ]
```

### Explicação Passo a Passo

#### Passo 1: A Requisição do Cliente e o Apache (`.htaccess`)
Tudo começa quando o usuário digita um endereço no navegador, por exemplo: `http://localhost:8000/admin`. O Servidor Web Apache recebe esta chamada e lê o arquivo **[.htaccess](file:///e:/42 rio/Camagru/src/.htaccess)**.
*   O `.htaccess` possui regras (`RewriteCond`) que validam se a URL solicitada é um arquivo físico (como imagens, arquivos CSS ou JS em `/assets`) ou uma pasta real no disco.
*   Se o arquivo ou pasta **não existir** (como é o caso de `/admin`), o Apache reescreve internamente a URL para **`index.php`**, anexando o endereço original à query (`$_SERVER['REQUEST_URI']`).

#### Passo 2: O Front Controller (`src/index.php`)
Toda requisição dinâmica bate no **[index.php](file:///e:/42 rio/Camagru/src/index.php)**, que atua como o único portão de entrada da nossa aplicação. Nele:
1.  Iniciamos ou restauramos a sessão (`session_start`).
2.  Carregamos o **[Autoloader.php](file:///e:/42 rio/Camagru/src/Core/Autoloader.php)**, que cuida de dar `require` de forma dinâmica nos arquivos PHP correspondentes aos namespaces `Core\` ou `App\` conforme são chamados.
3.  Carregamos o **[Env.php](file:///e:/42 rio/Camagru/src/Core/Env.php)** para injetar as chaves e credenciais do arquivo `.env` para o ambiente PHP.
4.  Instanciamos a classe **[Router.php](file:///e:/42 rio/Camagru/src/Core/Router.php)** e cadastramos as rotas válidas chamando o método `$router->add()`. Cada chamada de `add` adiciona uma entrada de mapeamento no array interno `$routes` (armazenando o método HTTP, regex do path e o handler `"NomeController@nomeMetodo"`).
5.  Disparamos o roteamento chamando `$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'])`.

#### Passo 3: O Roteador (`src/Core/Router.php`)
O método `dispatch()` recebe o caminho e o tipo de requisição HTTP:
1.  Ele limpa a URL, removendo barras extras e query strings.
2.  Percorre a lista de rotas registradas executando testes de expressões regulares.
3.  **Se não encontrar** nenhuma rota válida, ele aciona o método `handleNotFound()`, que por sua vez instancia o `ErrorController` para renderizar e responder com erro HTTP 404.
4.  **Se encontrar** a rota, ele extrai e sanitiza os parâmetros que foram definidos dinamicamente na URL (como `{username}` na rota `/perfil/{username}`).
5.  Ele separa a string do handler (ex: `"AdminController@index"`) em nome da classe e do método, instancia a classe (`App\Controllers\AdminController`) e invoca o método de ação (`index`) passando os parâmetros coletados.

#### Passo 4: O Controller (`src/App/Controllers/AdminController.php`)
Dentro do respectivo Controller, a ação é executada:
1.  **Lógica de Negócio**: O controller verifica a requisição (lê dados de formulários, valida parâmetros).
2.  **Lógica de Dados (Model)**: Se a rota precisa ler ou salvar informações no banco de dados MariaDB, o controller instancia as classes correspondentes em `App\Models\`. Essas Models herdam de **[Model.php](file:///e:/42 rio/Camagru/src/Core/Model.php)** que consome automaticamente a instância PDO Singleton em **[Database.php](file:///e:/42 rio/Camagru/src/Core/Database.php)**. Se a conexão falhar neste ponto, o Singleton lança um erro que é tratado exibindo a página 500.
3.  **Encaminhamento para Renderização**: O controller reúne todos os dados e chama o método herdado `$this->render("nomeView", $dadosArray)`.

#### Passo 5: O Mecanismo de Renderização (`src/Core/Controller.php`)
O método `render()` executa a união dos arquivos de visualização com o esqueleto do site:
1.  **Extração de Variáveis**: O PHP executa `extract($data)`, fazendo com que chaves do array de entrada virem variáveis locais (por exemplo, `['db_connected' => true]` vira a variável `$db_connected`).
2.  **Buffer da View**: A função `ob_start()` é acionada. Ela interrompe o envio imediato de saída ao navegador. O arquivo da view específica (ex: `src/App/Views/admin/index.php`) é incluído (`require`). O PHP processa a view, resolve o HTML e guarda toda a saída no buffer de memória.
3.  **Captura do Conteúdo**: Executamos `$content = ob_get_clean()`. Isso limpa o buffer e joga todo o HTML que a view gerou para dentro da variável `$content`.
4.  **Layout Principal**: O layout global (`src/App/Views/layout.php`) é incluído (`require`). Dentro dele, na seção de corpo da página, imprimimos a variável com o conteúdo específico: `<?= $content ?>`.

#### Passo 6: Envio da Resposta
Com o HTML do layout mesclado ao HTML da view específica, o PHP termina seu script de execução. O servidor web Apache recebe este bloco completo de marcação HTML e o despacha de volta na conexão HTTP ao navegador do cliente. O navegador renderiza os estilos CSS e a página final é apresentada ao usuário.

---

## Estrutura de Arquivos Criada
A estrutura do projeto está assim organizada:
```text
Camagru/
├── documentação/
│   ├── 01 - mvc.md          <-- Este arquivo de documentação
│   └── 02 - homepage e admin.md
├── docker/
│   └── web/
│       └── Dockerfile
├── src/
│   ├── .htaccess
│   ├── index.php
│   ├── Core/
│   │   ├── Autoloader.php
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Env.php
│   │   ├── Model.php
│   │   └── Router.php
│   └── App/
│       ├── Controllers/
│       │   ├── AdminController.php
│       │   ├── HomeController.php
│       │   └── ErrorController.php
│       └── Views/
│           ├── layout.php
│           ├── admin/
│           ├── home/
│           └── errors/
│               ├── 400.php
│               ├── 404.php
│               └── 500.php
```
