<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = ['Авто и мото', 'Бизнес и стартапы', 'Видеоигры', 'В мире животных', 'Дети и родители', 'Еда и кулинария', 'Здоровье и медицина','Знаменитости и образ жизни', 'Инвестиции', 'Иностранные языки', 'Интернет технологии', 'Искусство и дизайн', 'Кино', 'Книги, Аудиокниги и Подкасты', 'Красота и уход', 'Криптовалюты', 'Культура и события', 'Любопытные факты', 'Маркетинг и PR', 'Мода и стиль', 'Мотивация и саморазвитие', 'Музыка', 'Наука и технологии', 'Недвижимость', 'Новости и СМИ', 'Образование', 'Отдых и развлечения', 'Психология и отношения', 'Путешествия и туризм', 'Работа и вакансии', 'Региональные', 'Религия и духовность', 'Спорт', 'Строительство и ремонт', 'Фитнес', 'Хобби и деятельность', 'Юмор и мемы'];
        foreach ($topics as $topic) {
            Topic::create(['title' => $topic]);
        }
    }
}
