# Changelog (Catatan Perubahan Versi)

Semua perubahan pada proyek **Rotasi Website** didokumentasikan di file ini.
Pembaruan ini mencakup rilis **versi 2.x** yang mana merupakan arsitektur baru menggunakan **Laravel** (sebagai penerus dari versi 1.0 yang dibangun dengan Next.js).

---

## [v2.4.0] - September 2026 (Pembaruan Perizinan, Tema, & Tugas)
### Ditambahkan (Added)
- **Tema Terang (Light Theme):** Antarmuka kini mendukung mode tema terang.
- **Lampiran & Draft Tugas:** Penambahan kapabilitas untuk melampirkan *file* pada tugas acara (tabel `tasks` diperbarui dengan kolom *attachment*). Menambahkan dukungan *draft* tugas.
- **Sistem Perizinan Keamanan Lanjutan:**
  - Menambahkan alur perizinan keluar-masuk yang lebih detail.
  - Menambahkan riwayat izin untuk individu pada role *Keamanan*.
  - Menambahkan fitur *Export* (unduh data) untuk keseluruhan riwayat izin keamanan.
- **Sektor Stakeholder:** Membuka dan menambahkan akses data sektor ke dalam menu role *Stakeholder*.
- **Pemulihan Akun:** Menambahkan fitur pengaturan ulang (reset) kata sandi pengguna.

### Diubah (Changed)
- Pengaturan ulang kamera *default* untuk fitur pemindaian *QR Code*.

### Diperbaiki (Fixed)
- Perbaikan *bug* komprehensif pada fungsionalitas dan otorisasi *role*.

---

## [v2.3.0] - Agustus s/d September 2026 (Autentikasi Universal & Penyempurnaan Mobile)
### Ditambahkan (Added)
- **Login Universal (Third-Party):** Integrasi autentikasi *Socialite* untuk mendukung *login* melalui pihak ketiga secara lebih mulus.
- **Pemotongan Foto Profil:** Menambahkan modul *crop* otomatis ketika pengguna mengunggah foto profil baru.
- **Desain QR Code Baru:** Perombakan desain *QR Code* akun untuk keperluan identifikasi.

### Diubah (Changed)
- **Pengalaman Seluler (Mobile UX):** Pembaruan interaksi *bottom-navbar* untuk tampilan seluler yang kini mendukung usapan (*swipe*).
- **Format ID Akun:** Perubahan dan penyesuaian pola ID (Custom ID) pembuatan akun baru.

---

## [v2.2.0] - Juli s/d Agustus 2026 (Pembaruan Banner & Kendali Halaman Publik)
### Ditambahkan (Added)
- **Sistem Banner Dinamis:** Pembuatan modul *banner* yang disesuaikan untuk setiap *role* (termasuk penambahan fitur URL Aksi pada *banner*).
- **Target Unduhan (Downloads):** Modul unduhan kini diatur berdasarkan *target role*.
- **Kontrol Visibilitas Publik:** Fitur tombol sakelar (*show/hide*) pada Admin untuk mengontrol status tayang halaman publik (*Public Page*).
- **Aksi Kelola Masal:** Menambahkan fitur hapus dan setujui pendaftar secara massal.

### Diubah (Changed)
- Restrukturisasi navigasi *sidebar* ke navigasi bawah (*bottom navbar*) khusus pada mode layar kecil.
- Sedikit merombak tata cara pendaftaran peserta baru.

### Diperbaiki (Fixed)
- Memperbaiki tata letak (layout) *dashboard* pada profil pengguna.
- Perbaikan fungsi *autoplay* pada banner.

---

## [v2.1.0] - Juli 2026 (Penyempurnaan Modul Peserta & Izin Keluar)
### Ditambahkan (Added)
- **Modul Izin Keluar (Exit Permission):** Fitur khusus untuk mengelola permohonan dan catatan izin keluar (dengan *database* tabel `exit_permissions` mandiri).
- **Alur Persetujuan (Approval):** Pendaftar sistem (*User*) kini melewati fase "menunggu persetujuan" admin (*is_approved*).
- Menambahkan kapabilitas penyimpanan foto profil ke entitas pengguna.

---

## [v2.0.0] - Juni s/d Juli 2026 (Rotasi Website V2 - Migrasi Laravel)
### Ditambahkan (Added)
- **Perombakan Total (Rewrite):** Migrasi basis proyek secara penuh dari versi **1.0 (Next.js)** ke versi **2.0 (Laravel + Blade + Tailwind)**.
- **Sistem Role/Otorisasi Baru:** Implementasi multi-role yang mencakup *Admin*, *Peserta*, *Keamanan*, dan *Stakeholder*.
- **Modul Manajemen Konten (CMS):** Penambahan fitur pengelolaan data halaman publik meliputi:
  - *Page Contents*
  - *Timelines*
  - *Galleries*
  - *Testimonials*
  - *Downloads*
- **Sistem Penugasan Lengkap:**
  - Tabel dan relasi *Tasks* beserta *Task Submissions* (Pengumpulan tugas).
- **Struktur Divisi & Sektor:** Pembuatan arsitektur *Division*, *Division Members*, hingga perlindungan kata sandi sektor dan divisi.
