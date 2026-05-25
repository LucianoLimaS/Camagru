# Passo 2: Estrutura de Múltiplos Layouts e Responsividade

Neste segundo passo do desenvolvimento do Camagru, adaptamos o sistema para suportar **múltiplos layouts dinâmicos** acionados a partir do Controller e implementamos o design responsivo decente e moderno para telas pequenas de acordo com os mockups de referência fornecidos.

## O que foi desenvolvido

### 1. Sistema de Múltiplos Layouts (Core)
Nosso método `render` no [Controller.php](file:///e:/42 rio/Camagru/src/Core/Controller.php) já foi preparado originalmente para receber o nome do layout como terceiro parâmetro:
```php
protected function render($view, $data = [], $layout = 'layout')
```
Isso permite que, ao renderizar uma tela, o controller selecione qual invólucro HTML padrão deseja usar para encapsular a view.

### 2. Layouts Implementados (`src/App/Views/`)
Dividimos o design do sistema em dois invólucros principais:

*   **Layout Padrão ([layout.php](file:///e:/42 rio/Camagru/src/App/Views/layout.php))**:
    *   Baseado no mockup de galeria pública (`layout/camagru_galeria_p_blica/code.html`).
    *   Contém um **Cabeçalho Fixo (TopAppBar)** com navegação para a Galeria, Editor e Admin, além de botões dinâmicos de Login e Cadastro.
    *   Contém a seção principal flexível (`<main>`) que injeta a view corrente via `<?= $content ?>`.
    *   Contém um **Rodapé (Footer)** com links institucionais e direitos autorais.
    *   Carrega a biblioteca utilitária Tailwind CSS de forma responsiva.
*   **Layout de Autenticação ([auth_layout.php](file:///e:/42 rio/Camagru/src/App/Views/auth_layout.php))**:
    *   Baseado no mockup de login/cadastro (`layout/camagru_login_cadastro/code.html`).
    *   Suprime o cabeçalho e simplifica o rodapé para focar a intenção do usuário na ação transacional.
    *   Possui um grid de dois painéis: uma imagem hero artística com slogan no painel esquerdo (oculto no mobile) e uma área centralizada de formulário no painel direito.

### 3. Rotas & Controle (`src/App/Controllers/`)
*   **[AuthController.php](file:///e:/42 rio/Camagru/src/App/Controllers/AuthController.php)**: Criado para responder pelas rotas de autenticação. Ele aciona as views passando o layout secundário:
    *   `login()`: Renderiza a view `auth/index` usando o layout `'auth_layout'` e definindo a aba ativa como `'login'`.
    *   `register()`: Renderiza a view `auth/index` usando o layout `'auth_layout'` e definindo a aba ativa como `'register'`.
*   **[index.php](file:///e:/42 rio/Camagru/src/index.php)**: Registradas as novas rotas de acesso de visitantes:
    *   `GET /login` -> `AuthController@login`
    *   `GET /register` -> `AuthController@register`

### 4. Views Responsivas
*   **[auth/index.php](file:///e:/42 rio/Camagru/src/App/Views/auth/index.php)**: Renderiza os formulários dinâmicos de Login e Cadastro em um único painel.
    *   **Alternador de Abas Dinâmico**: O Javascript detecta a intenção e transiciona suavemente os formulários (Login / Sign Up), atualizando a URL da barra de endereços do navegador (via `history.pushState`) sem precisar recarregar a página física.
    *   **Password Strength Indicator**: Um medidor visual de força de senha baseado no tamanho e na presença de letras maiúsculas/números.
    *   **Toggle de Senha Oculta/Visível**: Botões integrados aos campos de senha para alternar a visibilidade.
*   **[home/index.php](file:///e:/42 rio/Camagru/src/App/Views/home/index.php)**: Interface da Galeria Pública com título grande, aviso flutuante sobre restrições de likes/comentários de visitantes, listagem de cartões de fotos com efeitos hover (zoom e bordas brilhantes) e paginação.

---

## Ciclo de Renderização de Múltiplos Layouts

```text
               [ Execução do Controller ]
                           │
       (Seleciona view e define o arquivo de layout)
                           │
                           ▼
          [ Controller::render($view, $data, $layout) ]
                           │
      ┌────────────────────┴────────────────────┐
      ▼                                         ▼
 (Se $layout === 'layout')           (Se $layout === 'auth_layout')
      │                                         │
      ▼                                         ▼
[ Carrega layout.php ]                 [ Carrega auth_layout.php ]
- Inclui TopAppBar                     - Suprime TopAppBar
- Main max-w-7xl                       - Painel Centralizado Split Grid
- Footer completo                      - Footer simplificado
      │                                         │
      └────────────────────┬────────────────────┘
                           ▼
             [ Captura ob_get_clean() ]
             - Injeta a View específica (<?= $content ?>)
                           │
                           ▼
             [ Envia HTML final ao cliente ]
```
