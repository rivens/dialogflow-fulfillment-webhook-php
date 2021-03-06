<?php

namespace Dialogflow\tests\Action;

use Dialogflow\Action\Questions\ListCard;
use Dialogflow\Action\Questions\ListCard\Option;
use Dialogflow\WebhookClient;
use PHPUnit\Framework\TestCase;

class ListCardTest extends TestCase
{
    private function getConversation()
    {
        $data = json_decode(file_get_contents(__DIR__.'/../../stubs/request-v2-google.json'), true);

        $agent = new WebhookClient($data);

        return $agent->getActionConversation();
    }

    public function testCreate()
    {
        $conv = $this->getConversation();

        $conv->ask('Please choose below');

        $conv->ask(ListCard::create()
            ->title('This is a title')
            ->addOption(Option::create()
                ->key('OPTION_1')
                ->title('Option 1')
                ->synonyms(['option one', 'one'])
                ->description('Select option 1')
                ->image('https://picsum.photos/300/300')
            )
            ->addOption(Option::create()
                ->key('OPTION_2')
                ->title('Option 2')
                ->synonyms(['option two', 'two'])
                ->description('Select option 2')
                ->image('https://picsum.photos/300/300')
            )
        );

        $this->assertEquals([
            'userStorage' => '{"data":{}}',
            'expectUserResponse' => true,
            'richResponse'       => [
                'items' => [
                    [
                        'simpleResponse' => [
                            'textToSpeech' => 'Please choose below',
                        ],
                    ],
                ],
            ],
            'systemIntent' => [
                'intent' => 'actions.intent.OPTION',
                'data'   => [
                    '@type'      => 'type.googleapis.com/google.actions.v2.OptionValueSpec',
                    'listSelect' => [
                        'title' => 'This is a title',
                        'items' => [
                            [
                                'optionInfo' => [
                                    'key'      => 'OPTION_1',
                                    'synonyms' => [
                                        'option one',
                                        'one',
                                    ],
                                ],
                                'title'       => 'Option 1',
                                'description' => 'Select option 1',
                                'image'       => [
                                    'url'               => 'https://picsum.photos/300/300',
                                    'accessibilityText' => 'accessibility text',
                                ],
                            ],
                            [
                                'optionInfo' => [
                                    'key'      => 'OPTION_2',
                                    'synonyms' => [
                                        'option two',
                                        'two',
                                    ],
                                ],
                                'title'       => 'Option 2',
                                'description' => 'Select option 2',
                                'image'       => [
                                    'url'               => 'https://picsum.photos/300/300',
                                    'accessibilityText' => 'accessibility text',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $conv->render());
    }
}
