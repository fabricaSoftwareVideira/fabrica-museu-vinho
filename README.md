# Museu do Vinho - Portal Digital

Stack WordPress containerizada com MariaDB, WP-CLI e suporte a rede externa Docker.

---

## 📋 Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/) 24+
- [Docker Compose](https://docs.docker.com/compose/install/) v2+

```bash
docker --version
docker compose version
```

---

## 📁 Estrutura do Projeto

```
.
├── docker-compose.yml
├── .env                        # Variáveis de ambiente (não versionar)
├── .env.example                # Exemplo de variáveis
├── wordpress/
│   └── uploads.ini             # Configurações PHP para uploads
└── data/
    ├── db/                     # Dados do MariaDB (gerado automaticamente)
    └── wordpress/              # Arquivos do WordPress (gerado automaticamente)
```

---

## ⚙️ Configuração

### 1. Criar a rede externa

```bash
docker network create fabrica-network
```

### 2. Configurar variáveis de ambiente

Copie o arquivo de exemplo e preencha com seus valores:

```bash
cp .env.example .env
```

Edite o `.env`:

```env
MYSQL_ROOT_PASSWORD=senha_root_forte
MYSQL_DATABASE=wordpress
MYSQL_USER=wp_user
MYSQL_PASSWORD=senha_forte

WORDPRESS_DB_USER=wp_user
WORDPRESS_DB_PASSWORD=senha_forte
WORDPRESS_DB_NAME=wordpress
```

> ⚠️ **Nunca versione o arquivo `.env`**. Ele já está incluído no `.gitignore`.

### 3. Configurar uploads PHP

Crie o arquivo `wordpress/uploads.ini`:

```ini
file_uploads = On
memory_limit = 256M
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
```

---

## 🚀 Rodando o Projeto

### Subir os containers

```bash
docker compose up -d
```

### Acompanhar os logs

```bash
docker compose logs -f
```

### Acessar o WordPress

Abra no navegador: [http://localhost](http://localhost)

Na primeira inicialização, o WordPress exibirá o assistente de instalação.

---

## 🛠️ Comandos Úteis

### Gerenciamento dos containers

```bash
# Ver status dos containers
docker compose ps

# Parar os containers
docker compose down

# Reiniciar um serviço específico
docker compose restart wordpress

# Parar e remover volumes (⚠️ apaga todos os dados)
docker compose down -v
```

### WP-CLI

O WP-CLI está disponível como serviço separado com o profile `tools`:

```bash
# Listar plugins
docker compose run --rm wpcli wp plugin list

# Listar usuários
docker compose run --rm wpcli wp user list

# Limpar cache
docker compose run --rm wpcli wp cache flush

# Atualizar todos os plugins
docker compose run --rm wpcli wp plugin update --all

# Exportar banco de dados
docker compose run --rm wpcli wp db export backup.sql
```

---

## 🐛 Solução de Problemas

### Erro de permissão no WP-CLI

Se aparecer `Permission denied` ao rodar o WP-CLI, corrija as permissões dos arquivos:

```bash
sudo chown -R 33:33 ./data/wordpress
```

Verifique também se o serviço `wpcli` tem `user: "33:33"` no `docker-compose.yml`.

### WordPress não conecta ao banco

O `depends_on` com `healthcheck` garante que o MariaDB esteja pronto antes do WordPress iniciar. Se ainda assim falhar:

```bash
# Verifique os logs do banco
docker compose logs db

# Verifique se o healthcheck passou
docker compose ps
```

### Rede não encontrada

Se aparecer o erro `network fabrica-network declared as external, but could not be found`, crie a rede:

```bash
docker network create fabrica-network
```

---

## 🔒 Segurança

- Nunca exponha as portas do banco de dados publicamente.
- Use senhas fortes no `.env`.
- Em produção, utilize um proxy reverso (Nginx, Traefik) com certificado SSL.
- Faça backup regular do diretório `./data/` e dos arquivos do WordPress.

---

## 📦 Serviços

| Serviço     | Imagem                          | Descrição                       |
|-------------|----------------------------------|----------------------------------|
| `db`        | `mariadb:12`                  | Banco de dados MySQL-compatível  |
| `wordpress` | `wordpress:6.8.1-php8.3-apache` | Aplicação WordPress              |
| `wpcli`     | `wordpress:cli-php8.3`          | Interface de linha de comando    |

> O serviço `wpcli` usa o [profile](https://docs.docker.com/compose/profiles/) `tools` e não sobe automaticamente com `docker compose up`.