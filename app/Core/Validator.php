<?php

declare(strict_types=1);

namespace App\Core;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * Klasa Validator služi za validaciju korisničkih podataka kao što su korisničko ime, lozinka, IP adrese itd.
 * Omogućava:
 *   - primenu pravila validacije uz lokalizovane poruke grešaka,
 *   - dinamičku primenu pravila (uključujući optional, XSS zaštitu),
 *   - pristup greškama koje su nastale tokom validacije.
 * 
 * @author
 * @version 1.0.1
 */
class Validator
{
    /**
     * Niz u kome se čuvaju poruke o greškama nastalim tokom validacije.
     * @var array
     */
    private $errors = [];

    /**
     * Proverava da li postoje greške nastale tokom validacije.
     * 
     * @return bool Vraća true ako postoje greške, false ako nema
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * Vraća niz sa svim greškama koje su nastale tokom validacije.
     * 
     * @return array Niz tekstualnih poruka o greškama
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Ručno dodaje grešku za određeno polje.
     * 
     * @param string $field Naziv polja (ključ)
     * @param string $message Poruka greške
     */
    public function addError($field, $message)
    {
        $this->errors[$field] = [$message];
    }

    /**
     * Validira podatke prema definisanim pravilima.
     * 
     * @param array $data Asocijativni niz sa podacima (npr. $_POST)
     * @param array $rules Asocijativni niz pravila validacije (Respect\Validation\Validator)
     * 
     * @return self Vraća instancu klase radi dalje upotrebe (npr. chaining)
     */
    public function validate(array $data, array $rules): self
    {
        // Učitavanje prevoda za poruke grešaka iz lang fajlova
        $translations = Lang::getGroup("validator");
        foreach ($rules as $field => $rule) {
            try {
                // Postavljanje lokalizovanih poruka za validaciona pravila
                $this->applyTemplates($rule, $translations);

                // Pokreće validaciju; ako ne uspe baca izuzetak
                $rule->assert($data[$field] ?? null);
            } catch (NestedValidationException $e) {
                // Čuva sve greške za to polje
                $this->errors[$field] = array_values($e->getMessages());
            }
        }

        return $this;
    }

    /**
     * Interna pomoćna funkcija koja postavlja prevod (poruku) za svako pravilo.
     * Radi i za složene validatore koji sadrže više pravila (npr. oneOf, optional...).
     * 
     * @param mixed $rule Instanca validatora (npr. v::intVal())
     * @param array $translations Niz sa prevedenim porukama za validator (lang fajlovi)
     * 
     * @return void
     */
    private function applyTemplates($rule, array $translations): void
    {
        // Ako validator sadrži više pravila (npr. allOf, oneOf...)
        if (method_exists($rule, 'getRules')) {
            foreach ($rule->getRules() as $subRule) {
                $this->applyTemplates($subRule, $translations);
            }

            // Ako je pravilo tipa Optional, specijalno se obrađuje
            if (get_class($rule) === \Respect\Validation\Rules\Optional::class) {
                $innerRule = $rule->getRules()[0] ?? null;
                if ($innerRule) {
                    $this->applyTemplates($innerRule, $translations);
                }
            }
        }

        // Ako pravilo podržava setTemplate i postoji prevod za njega
        if (method_exists($rule, 'setTemplate')) {
            $className = (new \ReflectionClass($rule))->getShortName(); // npr. Ip → "Ip"
            $key = "validator." . lcfirst($className); // "Ip" → "ip"

            if (isset($translations[$key])) {
                $rule->setTemplate($translations[$key]);
            }
        }
    }

    /**
     * Ako je polje prazno, ne primenjuje se pravilo; u suprotnom validacija mora proći.
     * Koristi se npr. kada je polje neobavezno, ali ako je popunjeno – mora biti validno.
     * 
     * @param mixed $rule Pravilo koje se primenjuje ako je vrednost unešena
     * @return mixed Kompozitno pravilo koje dozvoljava praznu vrednost ili prolaznu validaciju
     */
    public function optionalIfFilled($rule)
    {
        return v::oneOf(
            v::nullType()->setTemplate(''),   // Ako je null – dozvoli
            v::equals('')->setTemplate(''),   // Ako je prazan string – dozvoli
            $rule                             // Inače primeni prosleđeno pravilo
        );
    }

    public function notEmpty()
    {
        return v::not(v::equals(''))->setTemplate(
            Lang::get("validator.notEmpty")
        );
    }
    /**
     * Kreira pravilo koje odbacuje vrednosti sa specijalnim karakterima koji mogu biti XSS.
     * 
     * @return mixed Pravilo koje odbacuje vrednosti koje sadrže < > " ' / \ karaktere
     */
    public function noSpecialChars()
    {
        return v::not(v::regex('/[<>\"\'\/\\\\]/'))->setTemplate(
            Lang::get("validator.specialCharacters")
        );
    }
}
