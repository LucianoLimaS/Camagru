# Passo 4: Camada de Modelos e ORM customizado (Estilo CakePHP)

Para gerenciar a lógica de dados e a comunicação com o banco de dados MariaDB sem ferir as regras do projeto (que proíbem o uso de frameworks externos no PHP), desenvolvemos um **ORM (Object-Relational Mapping) leve e customizado** inspirado diretamente na arquitetura do **CakePHP**. 

Essa estrutura separa as responsabilidades em duas partes principais:
1. **Tables (Tabelas/Repositórios)**: Representam as tabelas do banco de dados, configuram associações e lidam com consultas (`SELECT`), salvamento (`INSERT`/`UPDATE`) e remoção (`DELETE`).
2. **Entities (Entidades/Registros)**: Representam uma linha individual da tabela, lidam com a manipulação de propriedades, campos virtuais, modificadores (*mutators/accessors*) e serialização de dados.

---

## Arquitetura do ORM

A base do ORM foi implementada no núcleo da aplicação (`src/Core/`) através de quatro classes principais:

1. **[Entity.php](file:///e:/42 rio/Camagru/src/Core/Entity.php)**:
   - Classe base para todos os registros individuais.
   - Implementa métodos mágicos (`__get`, `__set`, `__isset`, `__unset`) para acesso fluído aos campos.
   - Suporta *mutators* (métodos `_setFieldName`) executados automaticamente ao definir uma propriedade, e *accessors* (métodos `_getFieldName`) executados ao obter uma propriedade.
   - Rastreia quais propriedades foram modificadas (*dirty fields*) para que comandos de `UPDATE` alterem apenas o que é necessário.
   - Implementa a interface `JsonSerializable` para conversão nativa em JSON via `json_encode()`.

2. **[Table.php](file:///e:/42 rio/Camagru/src/Core/Table.php)**:
   - Repositório central da tabela. Resolve automaticamente o nome da tabela física no banco, a chave primária e a classe de entidade associada com base em convenções.
   - Oferece métodos CRUD de conveniência: `find()`, `get()`, `save()` e `delete()`.
   - Gerencia relacionamentos `belongsTo` e `hasMany` de forma declarativa no método `initialize()`.
   - Injeta e atualiza timestamps (`created` e `updated`) automaticamente nos momentos de salvamento.

3. **[Query.php](file:///e:/42 rio/Camagru/src/Core/Query.php)**:
   - Construtor de consultas fluente. Permite encadeamento de métodos: `select()`, `where()`, `order()`, `limit()` e `offset()`.
   - **Segurança Nativa**: Utiliza prepared statements do PDO e gerencia a vinculação de parâmetros (*param binding*) de forma automatizada para prevenir vulnerabilidades de **SQL Injection**.
   - **Eager Loading**: Implementa o método `contain()`. Quando solicitado, carrega os registros relacionados de todas as entidades resultantes usando consultas otimizadas em lote (evitando o gargalo de performance N+1).

4. **[TableRegistry.php](file:///e:/42 rio/Camagru/src/Core/TableRegistry.php)**:
   - Registro global e localizador estático que gerencia a instanciação sob demanda (*lazy instantiation*) e o cache de instâncias das tabelas (Padrão Singleton).

---

## Estrutura das Models da Aplicação (`src/App/Models/`)

Implementamos os modelos correspondentes às 4 tabelas do banco de dados:

### 1. Usuários (`users`)
* **[User.php (Entity)](file:///e:/42 rio/Camagru/src/App/Models/Entity/User.php)**:
  - Possui o mutator `_setPassword` que criptografa automaticamente as senhas com `password_hash()` (Bcrypt) ao serem definidas.
  - Possui o campo virtual `is_active` que retorna um booleano simples.
* **[UsersTable.php (Table)](file:///e:/42 rio/Camagru/src/App/Models/Table/UsersTable.php)**:
  - Mapeia a tabela `users`.
  - Associações: `hasMany` com `Images`, `Likes` e `Comments`.

### 2. Imagens (`images`)
* **[Image.php (Entity)](file:///e:/42 rio/Camagru/src/App/Models/Entity/Image.php)**: Entidade básica da imagem.
* **[ImagesTable.php (Table)](file:///e:/42 rio/Camagru/src/App/Models/Table/ImagesTable.php)**:
  - Mapeia a tabela `images`.
  - Associações: `belongsTo` com `Users`, e `hasMany` com `Likes` e `Comments`.

### 3. Likes (`likes`)
* **[Like.php (Entity)](file:///e:/42 rio/Camagru/src/App/Models/Entity/Like.php)**: Entidade básica do like.
* **[LikesTable.php (Table)](file:///e:/42 rio/Camagru/src/App/Models/Table/LikesTable.php)**:
  - Mapeia a tabela `likes`.
  - Associações: `belongsTo` com `Users` e `Images`.

### 4. Comentários (`comments`)
* **[Comment.php (Entity)](file:///e:/42 rio/Camagru/src/App/Models/Entity/Comment.php)**: Entidade básica de comentários.
* **[CommentsTable.php (Table)](file:///e:/42 rio/Camagru/src/App/Models/Table/CommentsTable.php)**:
  - Mapeia a tabela `comments`.
  - Associações: `belongsTo` com `Users` e `Images`.

---

## Exemplos Práticos de Uso

### 1. Inicializando as Tabelas
A obtenção das tabelas deve ser feita exclusivamente através do `TableRegistry`:
```php
use Core\TableRegistry;

$usersTable = TableRegistry::get('Users');
$imagesTable = TableRegistry::get('Images');
```

### 2. Consultas Básicas
Buscando todos os registros ou filtrando por condições seguras contra injeção SQL:
```php
// Buscar todos os usuários ativos com limite e ordenação
$activeUsers = $usersTable->find()
    ->where(['active' => 1])
    ->order(['created DESC'])
    ->limit(10)
    ->all(); // Retorna array de entidades App\Models\Entity\User

// Buscar apenas o primeiro usuário ativo correspondente
$user = $usersTable->find()
    ->where(['username' => 'luciano'])
    ->first(); // Retorna a entidade ou null

// Contar o total de registros ativos
$totalActive = $usersTable->find()
    ->where(['active' => 1])
    ->count(); // Retorna int
```

### 3. Buscando por Chave Primária
```php
try {
    $user = $usersTable->get(5); // Retorna a entidade com ID 5 ou lança exceção
} catch (\Exception $e) {
    // Registro não existe
}
```

### 4. Consultas Relacionadas (Eager Loading / `contain`)
Evite consultas extras carregando dados de tabelas relacionadas em lote:
```php
// Carregar um usuário trazendo suas fotos, likes e comentários de uma só vez
$user = $usersTable->find()
    ->where(['id' => 1])
    ->contain(['Images', 'Likes', 'Comments'])
    ->first();

// Acesso direto aos registros relacionados (convertidos em arrays de Entidades):
foreach ($user->images as $image) {
    echo $image->path; // Acessa propriedades da imagem
}

// Carregar uma imagem trazendo o autor (User) associado
$image = $imagesTable->find()
    ->where(['id' => 10])
    ->contain(['Users'])
    ->first();

echo $image->user->username; // Lucianolima
```

### 5. Inserindo Registros
Criando uma nova entidade e persistindo no banco de dados. Os timestamps `created` (e `updated` se houver) são definidos automaticamente.
```php
// Cria a entidade preenchida
$newUser = $usersTable->newEntity([
    'uuid' => 'algum-uuid-unico',
    'username' => 'antigravity',
    'email' => 'agent@google.com',
    'password' => 'senhaSuperSegura', // Passará pelo hash do Bcrypt automaticamente
    'active' => 1
]);

// Salva no banco de dados
if ($usersTable->save($newUser)) {
    echo "Usuário salvo com ID: " . $newUser->id;
    echo "Data de criação: " . $newUser->created; // Gerado no PHP
}
```

### 6. Atualizando Registros (Dirty Tracking)
Ao atualizar, o ORM analisa apenas as propriedades que sofreram alteração física no objeto, otimizando o comando SQL de atualização e alterando o campo `updated` automaticamente:
```php
// 1. Obtém o usuário
$user = $usersTable->get(1);

// 2. Modifica um campo
$user->email = 'novoemail@example.com'; 

// 3. Salva a alteração
// O ORM executará um UPDATE apenas no campo email e updated
$usersTable->save($user);
```

### 7. Deletando Registros
```php
$user = $usersTable->get(1);

// Deleta o registro do banco de dados.
// Por causa do ON DELETE CASCADE configurado no banco, as fotos, likes e comentários 
// vinculados a este usuário serão removidos automaticamente pelo MariaDB.
if ($usersTable->delete($user)) {
    echo "Usuário removido com sucesso.";
}
```
