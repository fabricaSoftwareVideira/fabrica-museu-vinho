# 🏛 Museu WordPress – Ambiente Docker (DEV & PROD)

Projeto WordPress containerizado com:

* MariaDB
* WordPress
* Nginx (reverse proxy)
* WP-CLI bootstrap automático
* Separação entre DEV e PRODUCTION
* HTTPS local com mkcert

# 🧪 Ambiente de Desenvolvimento (DEV)

## 1️⃣ Pré-requisitos

* Docker
* Docker Compose
* mkcert (para HTTPS local)

Instalar mkcert (Linux/Debian):

```bash
sudo apt install mkcert
```

Instalar mkcert (Linux/Manjaro):

```bash
sudo pacman -S mkcert
```

Instalar autoridade local:

```bash
mkcert -install
```

---

## 2️⃣ Criar domínio local

Editar `/etc/hosts`:

```bash
sudo nano /etc/hosts
```

Adicionar:

```
127.0.0.1 museu.local
```

Testar:

```bash
ping museu.local
```

---

## 3️⃣ Criar certificados locais

```bash
mkdir nginx/certs
mkcert -key-file nginx/certs/museu.local-key.pem \
       -cert-file nginx/certs/museu.local.pem \
       museu.local
```

---

## 4️⃣ Subir ambiente DEV

```bash
docker compose \
  --env-file .env \
  -f docker-compose.yml \
  up -d
```

---

## 🌐 Acessar

```
http://museu.local
```

---

## 🔁 Resetar ambiente DEV

Apagar banco e WordPress:

```bash
docker compose down -v
rm -rf data
docker compose up -d
```

---

# 🔧 Comandos Úteis

### Verificar se WP está instalado

```bash
docker compose run --rm wpcli wp core is-installed
```

---

### Atualizar URL manualmente

```bash
docker compose run --rm wpcli wp option update home http://museu.local
docker compose run --rm wpcli wp option update siteurl http://museu.local
```

---

### Acessar banco

```bash
docker exec -it museu-db mysql -u root -p
```

---

### Logs

```bash
docker compose logs -f
```