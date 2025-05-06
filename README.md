#   📊 Personal Finance System (Laravel API)

This is a personal finance system developed with Laravel 12 that allows you to control income, expenses, recurring financial goals, automatic notifications, and data visualization in an informative dashboard.

##   🚀 Features

###   🔁 Transactions

* Registration of **expenses** and **income**
* Association with categories
* Support for filters by period, type, category, etc.

###   🎯 Financial Goals

* Creation of goals by **category**
* Frequency: `weekly` or `monthly`
* Automatic calculation of progress
* Automatic generation of **future goals** at the end of the recurrence (via scheduled command)

###   🛎️ Notifications

* Automatic sending of notifications via **database** and **broadcast**
* Trigger configured by Observer when the goal reaches a certain percentage
* Endpoint to list pending user notifications

###   📅 Recurring Goals Scheduling

* Artisan command `goals:generate-next` runs daily via `scheduler`
* Automatically creates the next instance of the goal at the end of the current period

###   📈 Dashboard

* Total expenses and income in the period
* Balance evolution (by day/month)
* Graph of expenses by category
* List of recent transactions

###   📋 Reports (coming soon)

* Monthly summary: income, expenses, final balance
* Goals progress
* Spending by category
* Comparison with previous months
* CSV export

---

##   🛠️ Technologies Used

* Laravel 12
* MySQL
* Laravel Notifications (via database + broadcast)
* Laravel Scheduler (for daily execution of jobs)
* Laravel Queues (for asynchronous sending of notifications)
* Kool.dev
* RESTful API

---

##   📦 Installation with Kool.dev

This project uses [Kool](https://kool.dev) to facilitate the setup of Docker environments for local development.

###   Prerequisites

* [Docker](https://www.docker.com/)
* [Kool CLI](https://kool.dev/docs/getting-started/installation)

###   Steps to run the project locally:

```bash
git clone git@github.com:pedrororatto/api-finplus.git
cd api-finplus

#   Environment variables
#   If necessary, change the KOOL_DATABASE_PORT to your preferred port in .env.example

#   Install dependencies
kool setup

#   Restart the containers
kool restart

#   Execute the migrations
kool run artisan migrate

#   Execute the seeders
kool run artisan db:seed
