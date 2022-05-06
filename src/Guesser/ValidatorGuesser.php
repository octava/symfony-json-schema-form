<?php

namespace Octava\SymfonyJsonSchemaForm\Guesser;

use Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\ValueGuess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class ValidatorGuesser extends ValidatorTypeGuesser
{
    public function guessMinLength(string $class, string $property): ?Guess
    {
        return $this->guess($class, $property, function (Constraint $constraint) {
            return $this->guessMinLengthForConstraint($constraint);
        });
    }

    public function guessMinLengthForConstraint(Constraint $constraint): ?Guess
    {
        switch (get_class($constraint)) {
            case Constraints\Length::class:
                if (is_numeric($constraint->min)) {
                    return new ValueGuess($constraint->min, Guess::HIGH_CONFIDENCE);
                }
                break;
            case Constraints\Type::class:
                if (in_array($constraint->type, ['double', 'float', 'numeric', 'real'])) {
                    return new ValueGuess(null, Guess::MEDIUM_CONFIDENCE);
                }
                break;
            case Constraints\Range::class:
                if (is_numeric($constraint->min)) {
                    return new ValueGuess(strlen((string)$constraint->min), Guess::LOW_CONFIDENCE);
                }
                break;
        }

        return null;
    }
}
