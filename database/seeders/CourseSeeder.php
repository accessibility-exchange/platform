<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use App\Models\Question;
use App\Models\Quiz;
use Faker\Generator;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Generator::class);

        Course::factory()
            ->has(Module::factory()
                ->state([
                    'title' => [
                        'en' => 'Overview on Communication',
                        'fr' => 'Aperçu de la communication',
                    ],
                    'description' => null,
                    'introduction' => [
                        'en' => 'This webinar will share various communication methods and "best practices" in communications. Incorporating the recommendations will benefit everyone to understand how to communicate effectively and promote the full inclusion of people with disabilities.',
                        'fr' => 'Ce webinaire permettra de présenter diverses méthodes de communication et « pratiques exemplaires » en matière de communication. L’intégration des recommandations se révélera profitable à tous afin de comprendre comment communiquer efficacement et promouvoir la pleine inclusion des personnes handicapées.',
                    ],
                    'video' => [
                        'en' => 'https://vimeo.com/824854573',
                        'fr' => 'https://vimeo.com/824849288',
                        'asl' => 'https://vimeo.com/824856580',
                        'lsq' => 'https://vimeo.com/824851477',
                    ],
                ])
            )
            ->has(Module::factory()
                ->state([
                    'title' => [
                        'en' => 'Customer Service',
                        'fr' => 'Service à la clientèle',
                    ],
                    'description' => null,
                    'introduction' => [
                        'en' => 'Customer service is vital in every business; good customer service and engagement matter. In this webinar, you will learn how to communicate with customers with diverse communication needs effectively.',
                        'fr' => 'Le service à la clientèle est essentiel dans toutes les entreprises; il est important d’assurer un bon service à la clientèle et une bonne mobilisation. Dans le cadre de ce webinaire, vous apprendrez comment communiquer efficacement avec les clients ayant des besoins divers en matière de communication.',
                    ],
                    'video' => [
                        'en' => 'https://vimeo.com/825100128',
                        'fr' => 'https://vimeo.com/825103452',
                    ],
                ])
            )
            ->has(Module::factory()
                ->state([
                    'title' => [
                        'en' => 'The Built Environment & Technology',
                        'fr' => 'L’environnement bâti et la technologie',
                    ],
                    'description' => null,
                    'introduction' => [
                        'en' => 'This webinar will provide an overview of various assistive devices and the use of technology which can facilitate and support communication in areas such as customer service, built environment and the workplace.',
                        'fr' => 'Ce webinaire donnera un aperçu des divers appareils fonctionnels et de l’utilisation de la technologie pouvant faciliter et appuyer la communication dans des domaines tels que le service à la clientèle, l’environnement bâti et le milieu de travail.',
                    ],
                    'video' => [
                        'en' => 'https://vimeo.com/824820321',
                        'fr' => 'https://vimeo.com/824831128',
                    ],
                ])
            )
            ->has(Module::factory()
                ->state([
                    'title' => [
                        'en' => 'The Workplace',
                        'fr' => 'Le milieu de travail',
                    ],
                    'description' => null,
                    'introduction' => [
                        'en' => 'In this webinar, we focus on communication and removing barriers to communication in the workplace. We will review communication strategies in the following areas attitudes, not assumptions, hiring process, etiquette and facilitating communication.',
                        'fr' => 'Nous examinerons les stratégies de communication dans les domaines suivants : les attitudes, et non les suppositions, le processus d’embauche, l’étiquette et la facilitation de la communication. Dans le cadre de ce webinaire, nous mettons l’accent sur la communication et l’élimination des obstacles à la communication en milieu de travail.',
                    ],
                    'video' => [
                        'en' => 'https://vimeo.com/824866934',
                        'fr' => 'https://vimeo.com/824884050',
                        'asl' => 'https://vimeo.com/824862933',
                        'lsq' => 'https://vimeo.com/824871878',
                    ],
                ])
            )
            ->has(Quiz::factory()
                ->has(Question::factory()
                    ->state([
                        'question' => [
                            'en' => 'Accessible meetings have the following in place:',
                            'fr' => 'Les réunions accessibles ont les éléments suivants en place :',
                        ],
                        'choices' => [
                            'en' => [
                                [
                                    'label' => 'Designated chair to monitor speakers list',
                                    'value' => 0,
                                ],
                                [
                                    'label' => 'Accessible devices',
                                    'value' => 1,
                                ],
                                [
                                    'label' => 'Access to the content and proceedings of the meeting',
                                    'value' => 2,
                                ],
                                [
                                    'label' => 'Captioning or ASL interpreter',
                                    'value' => 3,
                                ],
                                [
                                    'label' => 'All the above',
                                    'value' => 4,
                                ],
                            ],
                            'fr' => [
                                [
                                    'label' => 'Chaise désignée pour surveiller la liste des conférenciers',
                                    'value' => 0,
                                ],
                                [
                                    'label' => 'Dispositifs accessibles',
                                    'value' => 1,
                                ],
                                [
                                    'label' => 'Accès au contenu et aux délibérations de la réunion',
                                    'value' => 2,
                                ],
                                [
                                    'label' => 'Sous-titrage ou interprète en American Sign Language',
                                    'value' => 3,
                                ],
                                [
                                    'label' => 'Toutes ces réponses',
                                    'value' => 4,
                                ],
                            ],
                        ],
                        'correct_choices' => [4],
                    ])
                )
                ->has(Question::factory()
                    ->state([
                        'question' => [
                            'en' => 'American Sign Language is a universal language.',
                            'fr' => 'La American Sign Language est une langue universelle.',
                        ],
                        'choices' => [
                            'en' => [
                                [
                                    'label' => 'True',
                                    'value' => 0,
                                ],
                                [
                                    'label' => 'False',
                                    'value' => 1,
                                ],
                            ],
                            'fr' => [
                                [
                                    'label' => 'Vrai',
                                    'value' => 0,
                                ],
                                [
                                    'label' => 'Faux',
                                    'value' => 1,
                                ],
                            ],
                        ],
                        'correct_choices' => [1],
                    ])
                )
                ->has(Question::factory()
                    ->state([
                        'question' => [
                            'en' => 'What does a respectful accommodation look like?',
                            'fr' => 'En quoi consiste une mesure d’adaptation respectueuse?',
                        ],
                        'choices' => [
                            'en' => [
                                [
                                    'label' => 'Not everyone communicates in the same way',
                                    'value' => 0,
                                ],
                                [
                                    'label' => 'Everyone receives information differently',
                                    'value' => 1,
                                ],
                                [
                                    'label' => 'Everyone processes at different speeds, and using different senses',
                                    'value' => 2,
                                ],
                                [
                                    'label' => 'Vocalizations are not the only way to communicate. Not everyone uses them consistently or well.',
                                    'value' => 3,
                                ],
                                [
                                    'label' => 'Respectful communication is inclusive and requires offering supports, respecting a person’s choice of alternative methods.',
                                    'value' => 4,
                                ],
                                [
                                    'label' => 'All the above',
                                    'value' => 5,
                                ],
                            ],
                            'fr' => [
                                [
                                    'label' => 'Tout le monde ne communique pas de la même façon.',
                                    'value' => 0,
                                ],
                                [
                                    'label' => 'Tout le monde reçoit l’information différemment.',
                                    'value' => 1,
                                ],
                                [
                                    'label' => 'Tout le monde analyse l’information à un rythme différent et utilise des sens différents pour ce faire.',
                                    'value' => 2,
                                ],
                                [
                                    'label' => 'Les vocalisations ne sont pas la seule façon de communiquer. Tout le monde ne les utilise pas invariablement ou efficacement.',
                                    'value' => 3,
                                ],
                                [
                                    'label' => 'La communication respectueuse est inclusive et exige d’offrir du soutien, tout en respectant le choix d’une personne d’utiliser d’autres méthodes.',
                                    'value' => 4,
                                ],
                                [
                                    'label' => 'Toutes ces réponses',
                                    'value' => 5,
                                ],
                            ],
                        ],
                        'correct_choices' => [5],
                    ])
                )
                ->has(Question::factory()
                    ->state([
                        'question' => [
                            'en' => 'Select tips when writing in plain language.',
                            'fr' => 'Sélectionnez conseils lorsqu’il s’agit d’écrire en langage simple.',
                        ],
                        'choices' => [
                            'en' => [
                                [
                                    'label' => 'Write for your reader',
                                    'value' => 0,
                                ],
                                [
                                    'label' => 'Organize your document',
                                    'value' => 1,
                                ],
                                [
                                    'label' => 'Use headings and subheadings',
                                    'value' => 2,
                                ],
                                [
                                    'label' => 'Word choices',
                                    'value' => 3,
                                ],
                                [
                                    'label' => 'Write short sentences ',
                                    'value' => 4,
                                ],
                                [
                                    'label' => 'Write short paragraphs',
                                    'value' => 5,
                                ],
                                [
                                    'label' => 'Write in the active voice',
                                    'value' => 6,
                                ],
                                [
                                    'label' => 'Choose your verbs carefully',
                                    'value' => 7,
                                ],
                                [
                                    'label' => 'Use personal pronouns',
                                    'value' => 8,
                                ],
                                [
                                    'label' => 'Write in a positive tone',
                                    'value' => 9,
                                ],
                                [
                                    'label' => 'All the above',
                                    'value' => 10,
                                ],
                            ],
                            'fr' => [
                                [
                                    'label' => 'Rédiger pour son lecteur',
                                    'value' => 0,
                                ],
                                [
                                    'label' => 'Organiser son document',
                                    'value' => 1,
                                ],
                                [
                                    'label' => 'Utiliser des en-têtes et des sous-titres',
                                    'value' => 2,
                                ],
                                [
                                    'label' => 'Choix de mots',
                                    'value' => 3,
                                ],
                                [
                                    'label' => 'Formuler des phrases courtes',
                                    'value' => 4,
                                ],
                                [
                                    'label' => 'Rédiger de courts paragraphes',
                                    'value' => 5,
                                ],
                                [
                                    'label' => 'Écrire à la voix active',
                                    'value' => 6,
                                ],
                                [
                                    'label' => 'Choisir soigneusement ses verbes',
                                    'value' => 7,
                                ],
                                [
                                    'label' => 'Utiliser des pronoms personnels',
                                    'value' => 8,
                                ],
                                [
                                    'label' => 'Employer un ton positif',
                                    'value' => 9,
                                ],
                                [
                                    'label' => 'Toutes ces réponses',
                                    'value' => 10,
                                ],
                            ],
                        ],
                        'correct_choices' => [10],
                    ])
                )
                ->has(
                    Question::factory()
                        ->state([
                            'question' => [
                                'en' => 'When communication with a blind person or someone with a vision loss it is important to do the following:',
                                'fr' => '5.	Lorsqu’il s’agit de communiquer avec une personne aveugle ou une personne ayant une perte de la vue, il est important de faire ce qui suit.',
                            ],
                            'choices' => [
                                'en' => [
                                    [
                                        'label' => 'Speak first',
                                        'value' => 0,
                                    ],
                                    [
                                        'label' => 'Introduce yourself',
                                        'value' => 1,
                                    ],
                                    [
                                        'label' => 'Describe the situation',
                                        'value' => 2,
                                    ],
                                    [
                                        'label' => 'Offer to help but don’t impose it',
                                        'value' => 3,
                                    ],
                                    [
                                        'label' => 'Be specific',
                                        'value' => 4,
                                    ],
                                    [
                                        'label' => 'All the above',
                                        'value' => 5,
                                    ],
                                ],
                                'fr' => [
                                    [
                                        'label' => 'Parler en premier',
                                        'value' => 0,
                                    ],
                                    [
                                        'label' => 'Se présenter',
                                        'value' => 1,
                                    ],
                                    [
                                        'label' => 'Décrire la situation',
                                        'value' => 2,
                                    ],
                                    [
                                        'label' => 'Offrir de l’aide, mais ne pas l’imposer',
                                        'value' => 3,
                                    ],
                                    [
                                        'label' => 'Être précis',
                                        'value' => 4,
                                    ],
                                    [
                                        'label' => 'Toutes ces réponses',
                                        'value' => 5,
                                    ],
                                ],
                            ],
                            'correct_choices' => [5],
                        ])
                )
                ->has(
                    Question::factory()
                        ->state([
                            'question' => [
                                'en' => 'For individuals who are hard of hearing make sure to speak in a loud voice.',
                                'fr' => 'Pour les personnes malentendantes, il faut vous assurer de parler fort.',
                            ],
                            'choices' => [
                                'en' => [
                                    [
                                        'label' => 'True',
                                        'value' => 0,
                                    ],
                                    [
                                        'label' => 'False',
                                        'value' => 1,
                                    ],
                                ],
                                'fr' => [
                                    [
                                        'label' => 'Vrai',
                                        'value' => 0,
                                    ],
                                    [
                                        'label' => 'Faux',
                                        'value' => 1,
                                    ],
                                ],
                            ],
                            'correct_choices' => [1],
                        ])
                )
                ->state([
                    'title' => [
                        'en' => 'Disability Connect, linking communication to transportation: Quiz',
                        'fr' => 'Connexion personnes handicapées, Pour un lien entre les communications et le transport: Questionnaire',
                    ],
                    'minimum_score' => '0.75',
                ])
            )
            ->create([
                'title' => [
                    'en' => 'Disability Connect, linking communication to transportation',
                    'fr' => 'Connexion personnes handicapées, Pour un lien entre les communications et le transport',
                ],
                'author' => [
                    'en' => 'Canadian Hard of Hearing Association',
                    'fr' => 'Association des malentendants canadiens',
                ],
                'introduction' => [
                    'en' => 'Welcome to Disability Connect, linking communication to transportation. In this introduction video, you will learn and meet Canadians who share the importance of understanding the various communication needs of individuals with communication disabilities and accessing services in the transportation sector.',
                    'fr' => 'Bienvenue à Connexion personnes handicapées, Pour un lien entre les communications et le transport. Dans cette vidéo de présentation, vous rencontrerez des Canadiens qui partagent l’importance de comprendre les divers besoins en matière de communication des personnes ayant des troubles de communication et d’accéder aux services dans le secteur des transports, en plus de prendre connaissance de l’information à cet égard.',
                ],
                'video' => null,
            ]);
    }
}
