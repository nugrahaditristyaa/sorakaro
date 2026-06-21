<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FlashcardCategory;
use App\Models\Flashcard;

class FlashcardSeeder extends Seeder
{
    /**
     * Seed flashcard categories and sample flashcards.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sapaan',
                'description' => 'Kata-kata sapaan dan salam dalam Bahasa Karo',
                'icon' => '👋',
                'order' => 1,
                'flashcards' => [
                    ['karo_word' => 'Mejuah-juah', 'indonesian_translation' => 'Halo / Salam sejahtera', 'example_sentence' => 'Mejuah-juah, uga kabar?', 'example_translation' => 'Halo, apa kabar?', 'order' => 1],
                    ['karo_word' => 'Bujur', 'indonesian_translation' => 'Terima kasih', 'example_sentence' => 'Bujur melala!', 'example_translation' => 'Terima kasih banyak!', 'order' => 2],
                    ['karo_word' => 'Uga kabar', 'indonesian_translation' => 'Apa kabar', 'example_sentence' => 'Uga kabar kam?', 'example_translation' => 'Apa kabar kamu?', 'order' => 3],
                    ['karo_word' => 'Mehuli', 'indonesian_translation' => 'Baik', 'example_sentence' => 'Kabar mehuli.', 'example_translation' => 'Kabar baik.', 'order' => 4],
                    ['karo_word' => 'Selamat pagi', 'indonesian_translation' => 'Selamat pagi', 'example_sentence' => null, 'example_translation' => null, 'order' => 5],
                ],
            ],
            [
                'name' => 'Angka',
                'description' => 'Angka dan bilangan dalam Bahasa Karo',
                'icon' => '🔢',
                'order' => 2,
                'flashcards' => [
                    ['karo_word' => 'Sada', 'indonesian_translation' => 'Satu', 'example_sentence' => 'Sada buah jambu.', 'example_translation' => 'Satu buah jambu.', 'order' => 1],
                    ['karo_word' => 'Dua', 'indonesian_translation' => 'Dua', 'example_sentence' => 'Dua kalak anak.', 'example_translation' => 'Dua orang anak.', 'order' => 2],
                    ['karo_word' => 'Telu', 'indonesian_translation' => 'Tiga', 'example_sentence' => null, 'example_translation' => null, 'order' => 3],
                    ['karo_word' => 'Empat', 'indonesian_translation' => 'Empat', 'example_sentence' => null, 'example_translation' => null, 'order' => 4],
                    ['karo_word' => 'Lima', 'indonesian_translation' => 'Lima', 'example_sentence' => null, 'example_translation' => null, 'order' => 5],
                    ['karo_word' => 'Enem', 'indonesian_translation' => 'Enam', 'example_sentence' => null, 'example_translation' => null, 'order' => 6],
                    ['karo_word' => 'Pitu', 'indonesian_translation' => 'Tujuh', 'example_sentence' => null, 'example_translation' => null, 'order' => 7],
                    ['karo_word' => 'Waluh', 'indonesian_translation' => 'Delapan', 'example_sentence' => null, 'example_translation' => null, 'order' => 8],
                    ['karo_word' => 'Siwah', 'indonesian_translation' => 'Sembilan', 'example_sentence' => null, 'example_translation' => null, 'order' => 9],
                    ['karo_word' => 'Sepuluh', 'indonesian_translation' => 'Sepuluh', 'example_sentence' => null, 'example_translation' => null, 'order' => 10],
                ],
            ],
            [
                'name' => 'Keluarga',
                'description' => 'Istilah kekerabatan dan keluarga dalam Bahasa Karo',
                'icon' => '👨‍👩‍👧',
                'order' => 3,
                'flashcards' => [
                    ['karo_word' => 'Nande', 'indonesian_translation' => 'Ibu', 'example_sentence' => 'Nandeku memasak i dapur.', 'example_translation' => 'Ibuku memasak di dapur.', 'order' => 1],
                    ['karo_word' => 'Bapa', 'indonesian_translation' => 'Ayah', 'example_sentence' => 'Bapa ku kerja i juma.', 'example_translation' => 'Ayahku bekerja di ladang.', 'order' => 2],
                    ['karo_word' => 'Turang', 'indonesian_translation' => 'Saudara perempuan (dari laki-laki)', 'example_sentence' => null, 'example_translation' => null, 'order' => 3],
                    ['karo_word' => 'Senina', 'indonesian_translation' => 'Saudara semarga', 'example_sentence' => null, 'example_translation' => null, 'order' => 4],
                    ['karo_word' => 'Bibi', 'indonesian_translation' => 'Bibi', 'example_sentence' => null, 'example_translation' => null, 'order' => 5],
                ],
            ],
            [
                'name' => 'Warna',
                'description' => 'Nama-nama warna dalam Bahasa Karo',
                'icon' => '🎨',
                'order' => 4,
                'flashcards' => [
                    ['karo_word' => 'Megara', 'indonesian_translation' => 'Merah', 'example_sentence' => 'Baju enda megara.', 'example_translation' => 'Baju ini merah.', 'order' => 1],
                    ['karo_word' => 'Meratah', 'indonesian_translation' => 'Hijau', 'example_sentence' => 'Bulung kayu e meratah.', 'example_translation' => 'Daun pohon itu hijau.', 'order' => 2],
                    ['karo_word' => 'Megersing', 'indonesian_translation' => 'Kuning', 'example_sentence' => null, 'example_translation' => null, 'order' => 3],
                    ['karo_word' => 'Metutup', 'indonesian_translation' => 'Hitam', 'example_sentence' => null, 'example_translation' => null, 'order' => 4],
                    ['karo_word' => 'Mbulan', 'indonesian_translation' => 'Putih', 'example_sentence' => null, 'example_translation' => null, 'order' => 5],
                ],
            ],
            [
                'name' => 'Aktivitas Sehari-hari',
                'description' => 'Kata kerja dan aktivitas harian dalam Bahasa Karo',
                'icon' => '🏃',
                'order' => 5,
                'flashcards' => [
                    ['karo_word' => 'Man', 'indonesian_translation' => 'Makan', 'example_sentence' => 'Aku man nakan.', 'example_translation' => 'Aku makan nasi.', 'order' => 1],
                    ['karo_word' => 'Minem', 'indonesian_translation' => 'Minum', 'example_sentence' => 'Minem lau mbentar.', 'example_translation' => 'Minum air putih.', 'order' => 2],
                    ['karo_word' => 'Medem', 'indonesian_translation' => 'Tidur', 'example_sentence' => 'Aku medem berngi enda.', 'example_translation' => 'Aku tidur malam ini.', 'order' => 3],
                    ['karo_word' => 'Erdalan', 'indonesian_translation' => 'Berjalan', 'example_sentence' => null, 'example_translation' => null, 'order' => 4],
                    ['karo_word' => 'Erjabat', 'indonesian_translation' => 'Bekerja', 'example_sentence' => null, 'example_translation' => null, 'order' => 5],
                ],
            ],
            [
                'name' => 'Bagian Tubuh',
                'description' => 'Nama-nama bagian tubuh dalam Bahasa Karo',
                'icon' => '🫁',
                'order' => 6,
                'flashcards' => [
                    ['karo_word' => 'Takal', 'indonesian_translation' => 'Kepala', 'example_sentence' => 'Takalku mesui.', 'example_translation' => 'Kepalaku sakit.', 'order' => 1],
                    ['karo_word' => 'Mata', 'indonesian_translation' => 'Mata', 'example_sentence' => null, 'example_translation' => null, 'order' => 2],
                    ['karo_word' => 'Cuping', 'indonesian_translation' => 'Telinga', 'example_sentence' => null, 'example_translation' => null, 'order' => 3],
                    ['karo_word' => 'Igung', 'indonesian_translation' => 'Hidung', 'example_sentence' => null, 'example_translation' => null, 'order' => 4],
                    ['karo_word' => 'Babah', 'indonesian_translation' => 'Mulut', 'example_sentence' => null, 'example_translation' => null, 'order' => 5],
                ],
            ],
            [
                'name' => 'Makanan dan Minuman',
                'description' => 'Nama makanan dan minuman dalam Bahasa Karo',
                'icon' => '🍛',
                'order' => 7,
                'flashcards' => [
                    ['karo_word' => 'Nakan', 'indonesian_translation' => 'Nasi', 'example_sentence' => 'Aku man nakan.', 'example_translation' => 'Aku makan nasi.', 'order' => 1],
                    ['karo_word' => 'Lau', 'indonesian_translation' => 'Air', 'example_sentence' => 'Minem lau mbentar.', 'example_translation' => 'Minum air putih.', 'order' => 2],
                    ['karo_word' => 'Manuk', 'indonesian_translation' => 'Ayam', 'example_sentence' => 'Gulei manuk mehuli.', 'example_translation' => 'Gulai ayam enak.', 'order' => 3],
                    ['karo_word' => 'Beras', 'indonesian_translation' => 'Beras', 'example_sentence' => null, 'example_translation' => null, 'order' => 4],
                    ['karo_word' => 'Tehu', 'indonesian_translation' => 'Tebu', 'example_sentence' => null, 'example_translation' => null, 'order' => 5],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $flashcards = $categoryData['flashcards'];
            unset($categoryData['flashcards']);

            $category = FlashcardCategory::create($categoryData);

            foreach ($flashcards as $flashcardData) {
                $category->flashcards()->create($flashcardData);
            }
        }
    }
}
