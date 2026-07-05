<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Timeline;
use App\Models\Stakeholder;
use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\Gallery;
use App\Models\Testimonial;
use App\Models\Download;
use App\Models\PageContent;

class PublicContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTimelines();
        $this->seedStakeholders();
        $this->seedDivisions();
        $this->seedGalleries();
        $this->seedTestimonials();
    }

    private function seedTimelines()
    {
        $timelineData = [
            [
                "title" => "Pra-ROTASI",
                "date" => "September 2025",
                "location" => "Online & Kampus UPI Purwakarta",
                "duration" => "1 minggu",
                "desc" => "Tahap persiapan dan technical meeting sebelum kegiatan ROTASI dimulai.",
                "activities" => ["Sosialisasi ROTASI melalui media sosial", "Pendaftaran online peserta", "Pembagian kelompok", "Technical meeting", "Pengenalan panitia dan pembimbing"],
                "order" => 1
            ],
            [
                "title" => "ROTASI Tahap I",
                "date" => "Oktober 2025",
                "location" => "Kampus UPI Purwakarta",
                "duration" => "3 hari",
                "desc" => "Tahap pertama ROTASI dilaksanakan di kampus UPI Purwakarta dengan fokus pada pengenalan lingkungan kampus dan program studi.",
                "activities" => ["Pembukaan resmi ROTASI", "Pengenalan lingkungan kampus dan fasilitas", "Pengenalan struktur organisasi HIMA PSTI", "Materi dasar-dasar akademik PSTI", "Team building dan ice breaking"],
                "order" => 2
            ],
            [
                "title" => "ROTASI Tahap II",
                "date" => "Oktober 2025",
                "location" => "Luar Kampus",
                "duration" => "3 hari",
                "desc" => "Tahap kedua ROTASI berupa kegiatan outbound di luar kampus untuk membangun kebersamaan dan solidaritas.",
                "activities" => ["Outbound dan kegiatan alam", "Latihan kepemimpinan dan kerja tim", "Malam keakraban dan pentas seni", "Renungan dan refleksi perjalanan ROTASI", "Pengukuhan sebagai mahasiswa PSTI"],
                "order" => 3
            ],
            [
                "title" => "Pasca-ROTASI",
                "date" => "November 2025",
                "location" => "Kampus UPI Purwakarta",
                "duration" => "Berkelanjutan",
                "desc" => "Tahap maintenance untuk menjaga keaktifan anggota muda (savior muda) dan pengenalan ke Bratvacode.",
                "activities" => ["Evaluasi keseluruhan program ROTASI", "Pengenalan Bratvacode", "Kegiatan lanjutan untuk anggota muda", "Monitoring dan pendampingan", "Integrasi ke dalam kegiatan HIMA PSTI"],
                "order" => 4
            ],
        ];

        foreach ($timelineData as $data) {
            Timeline::create($data);
        }
    }

    private function seedStakeholders()
    {
        $stakeholders = [
            [
                "name" => "Rheindy Ari Laksono",
                "role" => "Penanggung Jawab",
                "image" => "/reironaldo.jpg",
                "instagram" => "https://instagram.com/rotasipsti",
                "email" => "rotasi@psti.upi.edu",
                "desc" => "Bertanggung jawab atas keseluruhan kegiatan ROTASI 2025.",
                "order" => 1
            ],
            [
                "name" => "Rhezwan & Ariestama",
                "role" => "Steering Committee",
                "image" => "/placeholder-user.jpg",
                "instagram" => "https://instagram.com/himapstiupi",
                "email" => "himapsti@upi.edu",
                "desc" => "Memberikan arahan dan pengawasan terhadap pelaksanaan ROTASI 2025.",
                "order" => 2
            ],
            [
                "name" => "Fahri Bintang",
                "role" => "Ketua Pelaksana",
                "image" => "/placeholder-user.jpg",
                "instagram" => "https://instagram.com/rotasipsti",
                "email" => "rotasi@psti.upi.edu",
                "desc" => "Bertanggung jawab atas keseluruhan pelaksanaan ROTASI 2025.",
                "order" => 3
            ],
        ];

        foreach ($stakeholders as $data) {
            Stakeholder::create($data);
        }
    }

    private function seedDivisions()
    {
        $divisiData = [
            ["name" => "Divisi Sekretaris", "desc" => "Mengelola administrasi dan dokumentasi kegiatan ROTASI.", "members" => [["Koordinator", "Sayyidah Muthiara Kamilah"], ["Wakil Koordinator", "Septiana Putri"]]],
            ["name" => "Divisi Bendahara", "desc" => "Bertanggung jawab atas pengelolaan keuangan dan anggaran kegiatan ROTASI.", "members" => [["Koordinator", "Niha Karina Azzahra"], ["Wakil Koordinator", "Indri Rahmawati"]]],
            ["name" => "Divisi Acara", "desc" => "Bertanggung jawab atas perencanaan dan pelaksanaan seluruh rangkaian acara ROTASI.", "members" => [["Koordinator", "Octavian Purwa Ramadhani Hidayat"], ["Wakil Koordinator", "Amara Seviany"]]],
            ["name" => "Divisi Relasi", "desc" => "Bertanggung jawab atas hubungan dengan pihak eksternal dan sponsorship.", "members" => [["Koordinator", "Muhammad Raffi Akhdan"], ["Wakil Koordinator", "Nabila Ramadhani"]]],
            ["name" => "Divisi Mentor", "desc" => "Bertanggung jawab atas pembimbingan peserta selama kegiatan ROTASI.", "members" => [["Koordinator", "Alica Azwa Nayla"], ["Wakil Koordinator", "Ilham Agung Pambudi"]]],
            ["name" => "Divisi Keamanan", "desc" => "Bertanggung jawab atas keamanan dan ketertiban selama kegiatan ROTASI.", "members" => [["Koordinator", "Rifat Ali Nurjaman"], ["Wakil Koordinator", "Aryo Bayu Fauzan Ramadhan"]]],
            ["name" => "Divisi Kreatif", "desc" => "Bertanggung jawab atas desain dan konten kreatif untuk kegiatan ROTASI.", "members" => [["Koordinator", "Dara Puspita"], ["Wakil Koordinator", "Agizka Rizqta"]]],
            ["name" => "Divisi Pendanaan", "desc" => "Bertanggung jawab atas pencarian dana dan sponsorship untuk kegiatan ROTASI.", "members" => [["Koordinator", "Siti Shofa"], ["Wakil Koordinator", "Ismawatus Nurul Fadhilah"]]],
            ["name" => "Divisi Inventaris", "desc" => "Bertanggung jawab atas penyediaan dan pengelolaan perlengkapan kegiatan.", "members" => [["Koordinator", "Amirul Muhammad Rabbani"], ["Wakil Koordinator", "Fikriansyah Haikal Ramadhan"]]],
            ["name" => "Divisi Konsumsi", "desc" => "Bertanggung jawab atas penyediaan konsumsi selama kegiatan ROTASI.", "members" => [["Koordinator", "Hanifah Nurul Aini"], ["Wakil Koordinator", "Naufal Hazazi Dzil Ikram"]]],
            ["name" => "Divisi Medis", "desc" => "Bertanggung jawab atas kesehatan dan pertolongan pertama selama kegiatan ROTASI.", "members" => [["Koordinator", "Salsabila Bunga Azzahra"], ["Wakil Koordinator", "Ibnaty Alilatulbariza"]]],
            ["name" => "Komisi disiplin", "desc" => "Bertanggung jawab atas kedisiplinan dan ketertiban peserta selama kegiatan ROTASI.", "members" => [["Koordinator", "Anggita Fitri Permatasari"], ["Wakil Koordinator", "Salwa Aulia"]]]
        ];

        $order = 1;
        foreach ($divisiData as $divisi) {
            $div = Division::create([
                'name' => $divisi['name'],
                'desc' => $divisi['desc'],
                'order' => $order++
            ]);

            $memberOrder = 1;
            foreach ($divisi['members'] as $member) {
                DivisionMember::create([
                    'division_id' => $div->id,
                    'name' => $member[1],
                    'role' => $member[0],
                    'order' => $memberOrder++
                ]);
            }
        }
    }

    private function seedGalleries()
    {
        $galleryData = [
            ["year" => "2024", "src" => "https://content.rotasipsti.id/images/rotasi2024/IMG_0374.jpg", "alt" => "ROTASI 2024 - Pembukaan", "caption" => "Pembukaan ROTASI 2024"],
            ["year" => "2024", "src" => "https://content.rotasipsti.id/images/rotasi2024/IMG_0469.jpg", "alt" => "ROTASI 2024 - Diskusi Kelompok", "caption" => "Diskusi Kelompok"],
            ["year" => "2024", "src" => "https://content.rotasipsti.id/images/rotasi2024/DSC_1413.jpg", "alt" => "ROTASI 2024 - Outbound", "caption" => "Kegiatan Outbound"],
            ["year" => "2024", "src" => "https://content.rotasipsti.id/images/rotasi2024/DSC02455.jpg", "alt" => "ROTASI 2024 - Presentasi", "caption" => "Sesi Presentasi"],
            ["year" => "2024", "src" => "https://content.rotasipsti.id/images/rotasi2024/DSC02206.jpg", "alt" => "ROTASI 2024 - Malam Keakraban", "caption" => "Malam Keakraban"],
            ["year" => "2024", "src" => "https://content.rotasipsti.id/images/rotasi2024/PIF_3119.jpg", "alt" => "ROTASI 2024 - Penutupan", "caption" => "Penutupan ROTASI 2024"],
            ["year" => "2023", "src" => "https://content.rotasipsti.id/images/rotasi2023/opening_rotasi23.jpg", "alt" => "ROTASI 2023 - Pembukaan", "caption" => "Pembukaan ROTASI 2023"],
            ["year" => "2023", "src" => "https://content.rotasipsti.id/images/rotasi2023/workshop_teknologi23.jpg", "alt" => "ROTASI 2023 - Workshop", "caption" => "Workshop Teknologi"],
            ["year" => "2023", "src" => "https://content.rotasipsti.id/images/rotasi2023/team_building23.jpg", "alt" => "ROTASI 2023 - Team Building", "caption" => "Team Building"],
            ["year" => "2023", "src" => "https://content.rotasipsti.id/images/rotasi2023/diskusi23.png", "alt" => "ROTASI 2023 - Diskusi Panel", "caption" => "Diskusi Panel"],
            ["year" => "2023", "src" => "https://content.rotasipsti.id/images/rotasi2023/pensi23.jpg", "alt" => "ROTASI 2023 - Pentas Seni", "caption" => "Pentas Seni"],
            ["year" => "2023", "src" => "https://content.rotasipsti.id/images/rotasi2023/penutupan23.jpg", "alt" => "ROTASI 2023 - Penutupan", "caption" => "Penutupan ROTASI 2023"]
        ];

        $order = 1;
        foreach ($galleryData as $data) {
            Gallery::create([
                'year' => $data['year'],
                'image' => $data['src'],
                'image_type' => 'url',
                'alt' => $data['alt'],
                'caption' => $data['caption'],
                'order' => $order++
            ]);
        }
    }

    private function seedTestimonials()
    {
        $testimoniData = [
            ["name" => "Fahri Bintang", "angkatan" => "2024", "photo" => "/fahri.png", "text" => "ROTASI memberikan saya pengalaman yang luar biasa. Saya belajar banyak tentang kerja tim, kepemimpinan, dan nilai-nilai PSTI yang akan saya bawa sepanjang perkuliahan."],
            ["name" => "Putri Apriyanti", "angkatan" => "2024", "photo" => "/putri.png", "text" => "Melalui ROTASI, saya menemukan keluarga baru di PSTI. Kegiatan ini membentuk karakter saya dan mempersiapkan saya menghadapi tantangan perkuliahan dengan lebih baik."],
            ["name" => "Ariestama Putra", "angkatan" => "2023", "photo" => "/ariestama.png", "text" => "Awalnya saya ragu mengikuti ROTASI, tapi ternyata ini adalah keputusan terbaik. Saya mendapatkan banyak pengetahuan dan keterampilan yang tidak bisa didapatkan di kelas."],
            ["name" => "Rheindy Ari Laksono", "angkatan" => "2023", "photo" => "/reindy.png", "text" => "ROTASI adalah pengalaman yang tak terlupakan. Selain mendapatkan teman baru, saya juga belajar tentang nilai-nilai penting yang membentuk jati diri mahasiswa PSTI."]
        ];

        $order = 1;
        foreach ($testimoniData as $data) {
            Testimonial::create([
                'name' => $data['name'],
                'angkatan' => $data['angkatan'],
                'image' => $data['photo'],
                'image_type' => 'url',
                'text' => $data['text'],
                'order' => $order++
            ]);
        }
    }
}
