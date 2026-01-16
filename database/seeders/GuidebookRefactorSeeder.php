<?php

namespace Database\Seeders;

use App\Models\GuidebookSection;
use App\Models\GuidebookItem;
use App\Models\Level;
use Illuminate\Database\Seeder;

class GuidebookRefactorSeeder extends Seeder
{
    /**
     * Seed Duolingo-style guidebook data
     */
    public function run(): void
    {
        // Get first level (Beginner)
        $level = Level::orderBy('order')->first();

        if (!$level) {
            $this->command->error('❌ No levels found. Please create levels first.');
            return;
        }

        $this->command->info('🎯 Creating guidebook for: ' . $level->name);

        // Section 1: KEY PHRASES
        $keyPhrasesSection = GuidebookSection::create([
            'level_id' => $level->id,
            'title' => 'KEY PHRASES',
            'subtitle' => 'Essential greetings and introductions',
            'description' => 'Learn the most common phrases for everyday conversations',
            'order' => 1,
            'is_active' => true,
        ]);

        // Add phrases to Section 1
        $phrases = [
            ['text' => 'Hello, how are you?', 'translation' => 'Halo, apa kabar?'],
            ['text' => 'My name is...', 'translation' => 'Nama saya...'],
            ['text' => 'Nice to meet you', 'translation' => 'Senang bertemu dengan Anda'],
            ['text' => 'Thank you very much', 'translation' => 'Terima kasih banyak'],
            ['text' => 'You\'re welcome', 'translation' => 'Sama-sama'],
        ];

        foreach ($phrases as $index => $phrase) {
            GuidebookItem::create([
                'guidebook_section_id' => $keyPhrasesSection->id,
                'type' => 'phrase',
                'text' => $phrase['text'],
                'translation' => $phrase['translation'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Section 2: GRAMMAR TIPS
        $grammarSection = GuidebookSection::create([
            'level_id' => $level->id,
            'title' => 'GRAMMAR TIPS',
            'subtitle' => 'Basic sentence structure',
            'description' => 'Understanding how to form simple sentences',
            'order' => 2,
            'is_active' => true,
        ]);

        // Add tips to Section 2
        $tips = [
            [
                'text' => 'Subject + Verb + Object is the basic sentence pattern',
                'translation' => 'Subjek + Kata Kerja + Objek adalah pola kalimat dasar'
            ],
            [
                'text' => 'Always use "is/am/are" with adjectives',
                'translation' => 'Selalu gunakan "is/am/are" dengan kata sifat'
            ],
            [
                'text' => 'Questions start with question words (What, Where, When)',
                'translation' => 'Pertanyaan dimulai dengan kata tanya (Apa, Dimana, Kapan)'
            ],
        ];

        foreach ($tips as $index => $tip) {
            GuidebookItem::create([
                'guidebook_section_id' => $grammarSection->id,
                'type' => 'tip',
                'text' => $tip['text'],
                'translation' => $tip['translation'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Section 3: COMMON EXPRESSIONS
        $expressionsSection = GuidebookSection::create([
            'level_id' => $level->id,
            'title' => 'COMMON EXPRESSIONS',
            'subtitle' => 'Everyday useful phrases',
            'order' => 3,
            'is_active' => true,
        ]);

        $expressions = [
            ['text' => 'Excuse me', 'translation' => 'Permisi'],
            ['text' => 'I don\'t understand', 'translation' => 'Saya tidak mengerti'],
            ['text' => 'Can you help me?', 'translation' => 'Bisakah Anda membantu saya?'],
            ['text' => 'Where is...?', 'translation' => 'Dimana...?'],
        ];

        foreach ($expressions as $index => $expr) {
            GuidebookItem::create([
                'guidebook_section_id' => $expressionsSection->id,
                'type' => 'phrase',
                'text' => $expr['text'],
                'translation' => $expr['translation'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ Created 3 sections with ' . GuidebookItem::count() . ' items');
        $this->command->info('📚 Guidebook refactor seeding completed!');
    }
}
