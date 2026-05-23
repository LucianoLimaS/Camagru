# Camagru - Estrutura Docker & Ambiente de Desenvolvimento

Este repositório contém a infraestrutura Docker completa para o projeto **Camagru** da 42. O ambiente foi desenvolvido pensando em flexibilidade, facilidade de uso e sem conflitos com servidores locais pré-existentes (como o WampServer).

---

## 🚀 Serviços Inclusos e Portas

Para evitar conflitos com portas padrão usadas pelo WampServer (como `80` e `3306`), o ambiente expõe as seguintes portas para a máquina local (host):

| Serviço | Container Name | Porta Host | Descrição / Acesso |
| :--- | :--- | :--- | :--- |
| **Servidor Web** | `camagru_web` | `8000` | Site PHP rodando no Apache: [http://localhost:8000](http://localhost:8000) |
| **Database** | `camagru_db` | `33061` | MariaDB 10.11 (porta interna do Docker: `3306`) |
| **phpMyAdmin** | `camagru_phpmyadmin` | `8085` | Interface web para gerenciar o banco: [http://localhost:8085](http://localhost:8085) |
| **Mailpit Web** | `camagru_mailpit` | `8025` | Painel de visualização de e-mails: [http://localhost:8025](http://localhost:8025) |
| **Mailpit SMTP** | `camagru_mailpit` | `1025` | Porta SMTP usada pelo container PHP para enviar e-mails |

---

## 📂 Estrutura de Pastas

```text
Camagru/
├── docker-compose.yml   # Definição e orquestração dos containers
├── Makefile             # Comandos para facilitar o ciclo de vida do projeto
├── README.md            # Este arquivo de documentação
├── .env                 # Configurações de credenciais e portas
├── docker/
│   └── web/
│       └── Dockerfile   # Instalação do PHP, extensões (PDO, GD) e msmtp
└── src/
    └── index.php        # Código-fonte da aplicação (mapeado em tempo real)
```

---

## ⚡ Desenvolvimento em Tempo Real

A pasta `src/` está mapeada diretamente no container web em `/var/www/html`. 
- **Qualquer alteração que você fizer na pasta `src/` pelo lado de fora (no seu editor de código/IDE) refletirá imediatamente no navegador.**
- Não há necessidade de reconstruir os containers ou reiniciar o servidor ao alterar o código.

---

## 🛠️ Comandos Disponíveis (Makefile)

O `Makefile` automatiza os comandos do Docker Compose:

*   `make` ou `make up`: Inicia os containers em segundo plano e realiza o build das imagens (se necessário).
*   `make down`: Para os containers temporariamente sem apagar os dados do banco.
*   `make clean`: Para os containers e **apaga o volume do banco de dados**, limpando todas as tabelas e dados salvos.
*   `make fclean`: Para os containers, apaga os volumes do banco e **remove todas as imagens Docker** criadas para o projeto.
*   `make re`: Reconstrói e reinicia o ambiente do zero.
*   `make status`: Mostra o status atual dos containers.
*   `make logs`: Acompanha os logs em tempo real de todos os containers (pressione `Ctrl+C` para sair).

---

## 🔑 Credenciais do Banco de Dados

Dentro da rede do Docker, os dados de conexão que seu PHP deve usar são:

*   **Host (Servidor):** `mariadb`
*   **Database (Banco):** `camagru`
*   **User (Usuário):** `camagru_user`
*   **Password (Senha):** `camagru_pass`
*   **Porta:** `3306` (porta padrão do banco de dados dentro da rede isolada do Docker)

*Nota: Para acessar via phpMyAdmin no host ([http://localhost:8085](http://localhost:8085)), você deve usar o usuário `root` e a senha `camagru_root_pass` configurados no seu `.env`.*

---

## ✉️ Como funciona o Envio de E-mails

No `Dockerfile` do servidor web, instalamos e configuramos o `msmtp` como agente de envio de e-mails padrão. 
Toda vez que seu código PHP chamar a função nativa `mail()`:

```php
mail("usuario@email.com", "Assunto", "Mensagem");
```

O e-mail será interceptado e enviado para o **Mailpit** (rodando no container `mailpit` na porta `1025`). Você pode ler todas as mensagens disparadas acessando [http://localhost:8025](http://localhost:8025) no seu navegador. Isso garante que nenhum e-mail real seja disparado e que você possa testar o fluxo de registro e redefinição de senha localmente de forma simples.

---

## 🎨 Extensão GD (Manipulação de Imagens)

O container `web` já vem com a extensão **GD** instalada por padrão. Ela é fundamental para os requisitos do Camagru que envolvem a manipulação de imagens pelo lado do servidor (como mesclar a foto capturada da câmera com as molduras/stickers).
