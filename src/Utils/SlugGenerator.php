<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Utils;

use EPKTechnologies\EPKBundle\Utils\SlugGenerator\SlugConflictCheckInterface;
use EPKTechnologies\EPKBundle\Utils\SlugGenerator\SlugGeneratorException;
use InvalidArgumentException;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Slug (like some-transliterated-phrase) generator with length limiting and duplicities checking
 *
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class SlugGenerator
{
    const UNLIMITED_LENGTH = 0;
    const DEFAULT_SEPARATOR = '-';
    const DEFAULT_LENGTH = self::UNLIMITED_LENGTH;
    const DEFAULT_MAX_ITERATIONS = 100;
    const UNLIMITED_ITERATIONS = 0;

    protected SluggerInterface $slugger;

    /**
     * Maximal length of generated slug
     */
    protected int $max_length;

    /**
     * Separator between segments
     */
    protected string $separator = self::DEFAULT_SEPARATOR;

    /**
     * After how many attempts to give up generation (like when there's some bug in existence check method)
     */
    protected int $max_iterations = self::DEFAULT_MAX_ITERATIONS;

    protected ?SlugConflictCheckInterface $existence_checker = null;

    function __construct(
        int $max_length = null,
        SlugConflictCheckInterface $existence_checker = null,
        SluggerInterface $slugger = null,
    )
    {
        $this->setMaxLength($max_length ?? static::DEFAULT_LENGTH);
        $this->slugger = $slugger ?? new AsciiSlugger();
        $this->existence_checker = $existence_checker;
    }

    public function getMaxLength(): int
    {
        return $this->max_length;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setMaxLength(int $max_length): void
    {
        if($max_length < self::UNLIMITED_LENGTH){
            throw new InvalidArgumentException("Invalid length");
        }
        $this->max_length = $max_length;
    }

    public function getSeparator(): string
    {
        return $this->separator;
    }

    public function setSeparator(string $separator): void
    {
        $this->separator = $separator;
    }

    public function getMaxIterations(): int
    {
        return $this->max_iterations;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setMaxIterations(int $max_iterations): void
    {
        if($max_iterations < self::UNLIMITED_ITERATIONS){
            throw new InvalidArgumentException("Invalid iterations");
        }
        $this->max_iterations = $max_iterations;
    }

    /**
     * @return SlugConflictCheckInterface|null
     */
    public function getExistenceChecker(): ?SlugConflictCheckInterface
    {
        return $this->existence_checker;
    }

    /**
     * @param SlugConflictCheckInterface|null $existence_checker
     */
    public function setExistenceChecker(?SlugConflictCheckInterface $existence_checker): void
    {
        $this->existence_checker = $existence_checker;
    }



    /**
     * Generates slug from text with possibility to check if slug already exists and generate unique slug ending with numbers
     * Example:
     * When original text is 'Some phrase', default slug will be 'some-phrase'
     * When existence_checker is defined and returns TRUE = 'some-phrase' already exists,
     * generator will try 'some-phrase1' ... then 'some-phrase2' ... until finding non-existing slug.
     * When there's maximal length defined, for example 4, default slug will be 'some'.
     * When 'some' exists, generator will try 'som1' ... 'som2' ... until finds non-existing slug with given length.
     */
    function generateSlug(string $from_text, array $context = []): string
    {
        $slug = $this->slugger
            ->slug($from_text, $this->separator)
            ->lower()
            ->trim($this->separator);

        if($slug->length() === 0){
            throw new SlugGeneratorException("Wrong text format, generated slug may not be empty", SlugGeneratorException::CODE_TOO_SHORT);
        }

        if($this->max_length !== self::UNLIMITED_LENGTH){
            $slug = $slug->slice(0, $this->max_length)->trim($this->separator);
        }

        if(!$this->existence_checker){
            return (string)$slug;
        }

        $iteration = 0;
        $base_slug = $slug;

        while($this->existence_checker->__invoke((string)$slug, $context)){
            $iteration++;
            if(
                $this->max_iterations !== self::UNLIMITED_ITERATIONS &&
                $iteration >= $this->max_iterations
            ){
                throw new SlugGeneratorException(
                    "Failed to generate unique slug in {$iteration} iterations.",
                    SlugGeneratorException::CODE_TOO_MANY_ITERATIONS
                );
            }

            if($this->max_length === self::UNLIMITED_LENGTH){
                $slug = $base_slug->append((string)$iteration);
                continue;
            }

            $remaining_slug_length = $this->max_length - strlen((string)$iteration);
            if($remaining_slug_length <= 0){
                throw new SlugGeneratorException(
                    "Original text is too short for further iterations",
                    SlugGeneratorException::CODE_TOO_SHORT
                );
            }

            $slug = $base_slug
                ->slice(0, $remaining_slug_length)
                ->trim($this->separator)
                ->append((string)$iteration);
        }

        return (string)$slug;
    }

    function __invoke(string $from_text): string
    {
        return $this->generateSlug($from_text);
    }
}
