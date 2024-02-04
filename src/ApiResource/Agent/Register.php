<?php

namespace App\ApiResource\Agent;

use ApiPlatform\Metadata\Post;
use App\State\Processor\Agent\RegisterProcessor;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[Post(
    uriTemplate: '/register',
    output: Token::class,
    name: 'api_register',
    processor: RegisterProcessor::class
)]
class Register
{
    #[Assert\When(
        expression: 'this.isTokenEmpty()',
        constraints: [
            new Assert\NotBlank(),
            new Assert\Length(
                min: 3, max: 14,
                minMessage: 'Username is too short. It should have {{ limit }} characters or more.',
                maxMessage: 'Username is too long. It should have {{ limit }} characters or less.'
            ),
        ],
    )]
    public string $username = '';
    #[Assert\Length(min: 5, minMessage: 'Password is too short. It should have {{ limit }} characters or more.',)]
    public string $password = '';
    public string $faction = 'COSMIC';
    #[Assert\When(
        expression: 'this.isUsernameEmpty()',
        constraints: [
            new Assert\NotBlank(),
            new Assert\Length(min: 20),
        ],
    )]
    public string $token = '';

    #[Ignore]
    public function isTokenEmpty(): bool
    {
        return trim($this->token) === '';
    }

    #[Ignore]
    public function isUsernameEmpty(): bool
    {
        return trim($this->username) === '';
    }
}