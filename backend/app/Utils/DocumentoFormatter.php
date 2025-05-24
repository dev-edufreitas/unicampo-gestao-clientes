<?php

namespace App\Utils;

class DocumentoFormatter
{

    public static function formatarDocumento(string $documento, string $tipoPessoa): string
    {
        $documento = preg_replace('/[^0-9]/', '', $documento);

        return match($tipoPessoa) {
            'fisica'   => self::formatarCPF($documento),
            'juridica' => self::formatarCNPJ($documento),
            default    => $documento
        };
    }

    private static function formatarCPF(string $cpf): string
    {
        return substr($cpf, 0, 3) . '.' .
               substr($cpf, 3, 3) . '.' .
               substr($cpf, 6, 3) . '-' .
               substr($cpf, 9, 2);
    }


    private static function formatarCNPJ(string $cnpj): string
    {
        return substr($cnpj, 0, 2) . '.' .
               substr($cnpj, 2, 3) . '.' .
               substr($cnpj, 5, 3) . '/' .
               substr($cnpj, 8, 4) . '-' .
               substr($cnpj, 12, 2);
    }

    public static function formatarTelefone(string $telefone): string
    {
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        $ddd      = substr($telefone, 0, 2);

        if (strlen($telefone) === 11) {
            $parte1 = substr($telefone, 2, 5);
            $parte2 = substr($telefone, 7, 4);
            return "({$ddd}) {$parte1}-{$parte2}";
        } else {
            $parte1 = substr($telefone, 2, 4);
            $parte2 = substr($telefone, 6, 4);
            return "({$ddd}) {$parte1}-{$parte2}";
        }
    }
}