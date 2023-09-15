
# Ticketing!

System Pengelolaan tiket menggunakan PHP native.

# Installation
- **Prerequisite**
		- PHP 8.0
		- RabbitMq
		- Composer
- Install dependency terlebih dahulu dengan menggunakan perintah `composer install`.
- Sesuaikan konfigurasi database anda di file `/Library/Phinx/phinx.json` dan di file `params.php`
> Jangan lupa untuk membuat database terlebih dahulu sebelum melakukan migrasi.
- Sesuaikan kredensial RabbitMq di file `params.php`
- Lakukan migrasi dengan perintah `./migration.sh` dan jalankan seeder dengan perintah `./seed.sh`.
- Sebelum men-generate ticket jalankan terlebih dahulu perintah `./start_listen.sh` untuk membuat RabbitMq bersiap menerima request membuat ticket.
- Untuk men-generate ticket dapat dengan menjalankan perintah `./generate_ticket.sh {event_id} {total_ticket}`
- Aktifkan server rest api dengan perintah `./start_rest.sh`
- Collection sudah disediakan di root directory. silahkan untuk di import di postman

## Arsitektur

Aplikasi ini dibuat menggunakan arsitektur Hexagonal dimana arsitektur ini berfokus pada memisahkan inti bisnis (domain) dengan entitas external (adapter) baik input maupun output yang disebut port dan adapter. Tujuannya untuk menghindari ketergantungan inti bisnis dengan entitas external seperti halnya sebuah stop kontak listrik yang tidak berketergantungan dengan penggunanya.
