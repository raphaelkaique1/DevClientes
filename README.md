# DevClientes
Projeto de estudos para aprendizado prático de **HTMX**, trata-se de um CRUD simples de "clientes" com renderização de HTML no servidor e atualizações de interface sem JavaScript de UI.
O foco central deste estudo é exercitar os conceitos de HTMX (hypermedia como motor do estado da aplicação) integrados a um backend PHP minimalista (escolhido devido à integração óbvia entre seu cerne e o modo como o HTMX trabalha).

## 🎯 Objetivo do projeto
Entender, na prática, como o **HTMX** muda a forma de construir interfaces web:

- O servidor responde com **fragmentos de HTML**, e o HTMX os injeta diretamente no DOM.
- A lógica de UI (qual elemento atualizar, quando disparar a requisição, onde exibir mensagens de erro/sucesso) é declarada via **atributos `hx-*`** no próprio HTML.
- O backend PHP funciona como uma **API hypermedia**, organizada em camadas (Controller → Service → Repository → Model), retornando HTML pronto para ser exibido.

## 🏁 Metas de aprendizado
- [x] Disparar requisições via `hx-get` / `hx-post` sem JavaScript de UI.
- [x] Carregar dados automaticamente com `hx-trigger="load"`.
- [x] Rotear notificações por **código de status HTTP** usando a extensão `response-targets` (`hx-target-2*`, `hx-target-4*`, `hx-target-5*`).
- [x] Renderizar fragmentos de HTML no servidor (camada de _Views_).
- [ ] Implementar edição (`PUT`/`PATCH`) e exclusão (`DELETE`) de clientes (botões já presentes nos cards, ações ainda pendentes).
- [ ] Atualizar a lista após edições sem recarregar tudo.

---

## 🛠️ Tecnologias usadas
| Tecnologia                                                                 | Versão          | Uso no projeto                                                                              |
|----------------------------------------------------------------------------|-----------------|---------------------------------------------------------------------------------------------|
| [PHP](https://www.php.net/)                                                | **8.5.7** (CLI) | Backend / API hypermedia (sem framework)                                                    |
| [HTMX](https://htmx.org/)                                                  | **2.0.10**      | Interatividade declarativa via atributos `hx-*`                                             |
| [htmx-ext-response-targets](https://htmx.org/extensions/response-targets/) | **2.0.4**       | Define o alvo do swap conforme o status HTTP da resposta                                    |
| [Tailwind CSS](https://tailwindcss.com/) (`@tailwindcss/browser`)          | **4.3.1**       | Estilização utilitária (CDN, sem build)                                                     |
| [SQLite](https://www.sqlite.org/)                                          | **3.45.1**      | Banco de dados (via PDO `pdo_sqlite`)                                                       |
| JavaScript inline (`hx-on::`)                                              | —               | Reações no cliente via atributos HTMX (limpar _toast_, resetar o form e recarregar a lista) |

> As dependências de front-end (HTMX, extensão e Tailwind) são carregadas via **CDN** diretamente no `public/index.html`, sem etapa de build.
> Estão fixadas no arquivo `public/index.html` pelos ranges `@2` e `@4`, que resolvem para as versões exatas da tabela acima.
> Para travar de fato uma versão (reprodutibilidade), troque o range pela versão completa na URL, por exemplo:
> `htmx.org@2.0.10`, `htmx-ext-response-targets@2.0.4`, `@tailwindcss/browser@4.3.1`.

### Recursos modernos do PHP utilizados
O código explora propositalmente novidades recentes da linguagem (para estudo e conhecimento):

- **Pipe operator `|>`** (PHP 8.5): em `api/src/Views/Customers/list.php`.
- **`array_any()`** (PHP 8.4): validação de campos no `CustomerController`.
- Expressões **`match`**, **enums** (`ContentType`, `Type`, `Violation`), **constructorproperty promotion** e **tipos nullable**.

---

## 📁 Estrutura do projeto
```
DevClientes/
├── App.php                       # Router script do servidor embutido (resolve estáticos, /api/* e a página principal)
├── public/                       # Raiz pública (front-end)
│   └── index.html                # Página única com os atributos hx-* e reações hx-on::
└── api/                          # Backend PHP
    ├── index.php                 # Front controller: registra rotas e despacha
    ├── config/
    │   └── Database.php          # DSN/credenciais do PDO (SQLite)
    └── src/
        ├── Router.php                   # Roteador
        ├── Controllers/
        │   └── CustomerController.php   # Controlador de lógica
        ├── Services/
        │   └── CustomerService.php      # Regras de negócio
        ├── Repositories/
        │   └── CustomerRepository.php   # Acesso ao banco (SQL)
        ├── Models/
        │   └── Customer.php             # Entidade Customer
        ├── Http/
        │   ├── Request.php              # Lê e normaliza o corpo da requisição
        │   └── Response.php             # Monta a resposta (Content-Type + status)
        ├── Views/Customers/
        │   └── list.php                 # Fragmento HTML da lista de clientes (CustomerView::list)
        ├── Utils/
        │   ├── Validation.php           # Validação de nome/email/cargo
        │   ├── Normalization.php        # Normalização (case, sanitização)
        │   └── Exception.php            # Operation::runSafe (try/catch helper)
        └── Database/
            ├── Connection.php           # Singleton PDO
            ├── Schema.sql               # DDL da tabela Customers
            └── Main.db                  # Banco SQLite (ignorado no .gitignore)
```

### Arquitetura em camadas
```
HTMX (index.html)
   │  hx-httpVerb
   ▼
App.php (router script)  →  api/index.php  →  Router  →  CustomerController
                              │
                              ▼
                        CustomerService   (validação + normalização)
                              │
                              ▼
                       CustomerRepository  →  PDO/SQLite
                              │
                              ▼
                        Views (HTML)  →  Response  →  HTMX faz o swap
```

---

## 🗄️ Banco de dados
Tabela `Customers` (ver `api/src/Database/Schema.sql`):

| Coluna   | Tipo    | Observações                                         |
|----------|---------|-----------------------------------------------------|
| `id`     | INTEGER | PK, autoincremento                                  |
| `name`   | TEXT    | Obrigatório                                         |
| `email`  | TEXT    | Obrigatório, **único**                              |
| `role`   | TEXT    | Obrigatório (`Full Stack`, `Back End`, `Front End`) |
| `status` | BOOLEAN | Padrão `1` (ativo)                                  |

O arquivo `Main.db` é **ignorado pelo Git** (`.gitignore`). Crie o banco localmente a partir do schema (ver abaixo).

---

## 🚀 Como executar localmente
> Pré-requisitos: **PHP 8.5+** com as extensões **`pdo_sqlite`** e **`mbstring`** habilitadas, e o binário `sqlite3` (opcional, para criar o banco).

1. **Clone o repositório remoto** do projeto para seu ambiente local:
   ```bash
   git clone https://github.com/raphaelkaique1/DevClientes.git && cd DevClientes/
   ```

2. **Instale as dependências do PHP** (extensões necessárias). Em distribuições baseadas em Debian/Ubuntu/WSL:
   ```bash
   sudo apt install php8.5-sqlite3 php8.5-mbstring
   ```
   > `pdo_sqlite` / `sqlite3` → acesso ao banco SQLite via PDO · `mbstring` → normalização de texto (`mb_strtolower`, `mb_convert_case`) em `api/src/Utils/Normalization.php`. Sem o `mbstring`, o cadastro (`POST`) falha com `500`.
   > Verifique com `php -m` se ambas aparecem na lista de módulos carregados.

3. **Criar o banco de dados** a partir do schema:
   ```bash
   sqlite3 api/src/Database/Main.db < api/src/Database/Schema.sql
   ```

4. **Subir o servidor embutido do PHP** a partir da raiz do projeto, o _router script_ (`App.php`) já resolve para que `http://localhost:8000/` exiba a página principal e `/api/*` seja roteado para o backend:
   ```bash
   php -S localhost:8000 App.php
   ```

5. Acesse no navegador:
   ```
   http://localhost:8000
   ```

> O _router script_ na raiz faz a ponte de forma idiomática:
> 1. Se a requisição corresponde a um **arquivo estático** (por exemplo: `/public/index.html`), nada é executado (`return false`), então o próprio servidor assume, resolve a requisção e o entrega.
> 2. Se o caminho parseado na requiseção, começa com `/api/`, delega ao **front controller** do backend (`api/index.php`).
> 3. Qualquer outra rota entrega a **página principal** (`public/index.html`).
>
> Resultado em `localhost:8000/` carregando a aplicação, os assets carregam pela URL real e a API responde.

---

## 🔌 Endpoints da API
| Método | Rota             | Ação                | Respostas                                                                                                                |
|--------|------------------|---------------------|--------------------------------------------------------------------------------------------------------------------------|
| `GET`  | `/api/customers` | Lista os clientes   | `200` lista · `204` vazio · `500` erro                                                                                   |
| `POST` | `/api/customers` | Cadastra um cliente | `201` criado · `400` inválido · `406` campos faltando · `409` e-mail já cadastrado · `415` formato inválido · `500` erro |

Todas as respostas são padronizadas em `media type: text`, contemplando os `subtypes` mais utilizados dessa categoria. Em suma, os alertas (texto estático padronizado de acordo com o `http status code` devido) são em `plain`, enquanto os elementos dinâmicos (obtidos através de iteração com o DB) em `html` prontas para o HTMX inserir no DOM.

---

## 💡 Conceitos de HTMX exercitados
No `public/index.html`:
- `hx-post="/api/customers"`: envia o formulário ao servidor.
- `hx-get="/api/customers"` + `hx-trigger="load"`: carrega a lista de Customers quando a página é carregada.
- Extensão **`response-targets`** (`hx-ext="response-targets"`) para rotear o swap pelo status HTTP. O formulário de cadastro envia as notificações para os _toasts_ no `<header>`:
  - `hx-target-2*="#toast-notification"`: respostas **2xx**
  - `hx-target-4*="#client-error-toast-notification"`: respostas **4xx**
  - `hx-target-5*="#server-error-toast-notification"`: respostas **5xx**
- A listagem trata os próprios erros dentro do bloco: `hx-target-4*`/`hx-target-5*` apontam para `#list-customers` e o texto é exibido nos `<span>` internos (`#client-error-list-notification` / `#server-error-list-notification`).
- Classe `empty:hidden` (Tailwind) para esconder elementos de notificações vazios.
- Reações via `hx-on::` (sem arquivo JS separado):
  - `hx-on::after-swap` no `<header>`: limpa o _toast_ 4s após o swap (substitui o antigo `script.js`).
  - `hx-on::after-request` no `<form>`: `this.reset()` quando a resposta é `201`.
  - `hx-on::after-request` no `<main>`: após um `201`, dispara `htmx.ajax('GET', '/api/customers', '#list-customers')` para recarregar a lista sem reload.

---

## 📜 Changelog
O projeto evoluiu de um `index.html` simples até uma API em camadas:
1. Setup inicial + inclusão de HTMX e Tailwind via CDN.
2. Schema inicial, model `Customer` e conexão PDO desacoplada por config.
3. Método de criação de cliente (`POST`) com roteador e camadas Http.
4. Validação e normalização de dados de entrada.
5. Fixação do HTMX na **v2** para o `response-targets` rotear por status.
6. Notificações (toasts) e limpeza automática.
7. Refatorações: camadas `Http`, `Services`, `Utils` e, por fim, a camada de **Views** para separar a geração de HTML.
8. Listagem de clientes (`GET`) e aplicação do **pipe operator**.
9. Tratamento de e-mail duplicado: violação de `UNIQUE` detectada pelo SQLSTATE `23000` (enum `Violation`) → resposta `409`.
10. Lista estilizada em _cards_, exibindo cargo, status (`Ativo`/`Inativo`) e botões **Editar**/**Excluir**.
11. Remoção do `public/js/script.js`: reações de UI migradas para atributos `hx-on::` (limpeza do _toast_, reset do formulário e recarga automática da lista após um `201`).

---

## 📚 Referências
- [Documentação do HTMX](https://htmx.org/docs/)
- [Extensão response-targets](https://htmx.org/extensions/response-targets/)
- [Livro "Hypermedia Systems"](https://hypermedia.systems/) (gratuito)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [PDO (PHP)](https://www.php.net/manual/pt_BR/book.pdo.php)

---

## 📄 Licença
Distribuído sob a licença **MIT**. Veja o arquivo [`LICENSE`](./LICENSE) para os termos completos.

<div align="center">2026 <a href="https://raphaelkaique1.github.io/raphaelkaique1/main">Raphael Kaíque Dias Santos</a></div>