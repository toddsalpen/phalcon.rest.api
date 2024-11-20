<?php

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\Messages\Message;
use Phalcon\Filter\Validation;
use Phalcon\Filter\Validation\Validator\Uniqueness;
use Phalcon\Filter\Validation\Validator\InclusionIn;

class Robots extends Model{

    public $id;
    public $name;
    public $type;
    public $year;

    public function initialize(){
        $this->setSource('robots');
    }

    public function validation(){
        $validator = new Validation();
        $validator->add(
            "type",
            new InclusionIn(
                [
                    'message' => 'Type must be "droid", "mechanical", or "virtual"',
                    'domain'  => [
                        'Protocol',
                        'Bounty Hunter',
                        'Virtual Companion',
                        'Agricultural',
                        'Astromechanic',
                        'Humanoid',
                        'Delivery',
                        'Chatbot',
                        'Spambot',
                        'Socialbot',
                        'Janitorial',
                        'Security',
                        'Exploration',
                        'Medical',
                        'Educational',
                        'Entertainment',
                        'Rescue',
                        'Military',
                        'Hospitality',
                        'Industrial',
                        'Companion',
                        'Pet',
                    ],
                ]
            )
        );
        $validator->add(
            'name',
            new Uniqueness(
                [
                    'field'   => 'name',
                    'message' => 'The robot name must be unique',
                ]
            )
        );
        if ($this->year < 0) {
            $this->appendMessage(
                new Message('The year cannot be less than zero')
            );
        }
        // Validate the validator
        return $this->validate($validator);
    }
}