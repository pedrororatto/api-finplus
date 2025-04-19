# 📊 Sistema Financeiro Pessoal (API em Laravel)

Este é um sistema financeiro pessoal desenvolvido com Laravel 12 que permite controlar receitas, despesas, metas financeiras com recorrência, notificações automáticas e visualização de dados em um dashboard informativo.

## 🚀 Funcionalidades

### 🔁 Transações
- Registro de **despesas** e **receitas**
- Associação com categorias
- Suporte a filtros por período, tipo, categoria, etc.

### 🎯 Metas Financeiras
- Criação de metas por **categoria**
- Frequência: `semanal` ou `mensal`
- Cálculo automático de progresso
- Geração automática de **metas futuras** ao término da recorrência (via comando programado)

### 🛎️ Notificações
- Envio automático de notificações via **banco de dados** e **broadcast**
- Gatilho configurado por Observer quando a meta atinge determinado percentual
- Endpoint para listar notificações pendentes do usuário

### 📅 Agendamento de Metas Recorrentes
- Comando Artisan `goals:generate-next` roda diariamente via `scheduler`
- Cria a próxima instância da meta ao fim do período atual, de forma automática

### 📈 Dashboard
- Total de despesas e receitas no período
- Evolução do saldo (por dia/mês)
- Gráfico de despesas por categoria
- Lista de transações recentes

### 📋 Relatórios (em breve)
- Resumo mensal: receitas, despesas, saldo final
- Progresso de metas
- Gasto por categoria
- Comparativo com meses anteriores
- Exportação de CSV

---

## 🛠️ Tecnologias Utilizadas

- Laravel 12
- MySQL
- Laravel Notifications (via database + broadcast)
- Laravel Scheduler (para execução diária de jobs)
- Laravel Queues (para envio assíncrono de notificações)
- Kool.dev
- API RESTful

---

## 📦 Instalação com Kool.dev

Este projeto utiliza o [Kool](https://kool.dev) para facilitar o setup de ambientes Docker para desenvolvimento local.

### Pré-requisitos

- [Docker](https://www.docker.com/)
- [Kool CLI](https://kool.dev/docs/getting-started/installation)

### Passos para rodar o projeto localmente:

```bash
git clone git@github.com:pedrororatto/api-finplus.git
cd api-finplus

# Environment variables
Caso necessário troque o KOOL_DATABASE_PORT para uma porta e sua preferência em .env.example

# Instala dependências
kool setup

# Restarta os containers
kool restart

# Executa as migrations
kool run artisan migrate

# Executa os seeders
kool run artisan db:seed

