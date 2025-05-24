<?php

namespace Tests\Unit;

use App\Utils\DocumentoValidator;
use PHPUnit\Framework\TestCase;

class DocumentoValidatorTest extends TestCase
{
    public function test_cpf_valido(): void
    {
        $this->assertTrue(DocumentoValidator::validaCPF('529.982.247-25'));
    }

    public function test_cpf_invalido(): void
    {
        $this->assertFalse(DocumentoValidator::validaCPF('111.111.111-11'));
    }
    
    public function test_cnpj_valido(): void
    {
        $this->assertTrue(DocumentoValidator::validaCNPJ('11.444.777/0001-61'));
    }
}