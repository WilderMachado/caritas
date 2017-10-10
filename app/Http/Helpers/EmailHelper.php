<?php

/**
 * Created by PhpStorm.
 * User: Wilder
 * Date: 01/10/2017
 * Time: 10:29
 */
namespace caritas\Http\Helpers;

use caritas\Email;
use Illuminate\Database\Eloquent\Model;

class EmailHelper
{

    /**Método para filtrar relação de e-mails removendo posições vazias
     * @param array|null $emails relação de e-mails passada
     * @return array relação de e-mails filtrada
     */
    public static function filtar(array $emails = null)
    {
        $retorno = array();                 //Cria variável para retornar com array vazio
        if ($emails):                       //Verifica se relação de e-mails não é nula
            foreach ($emails as $email):    //Percorre relação de e-mails
                if ($email['email']):       //Verifica se e-mail possui atributo email
                    $retorno[] = $email;    //Passa e-mail para array de retorno
                endif;
            endforeach;
        endif;
        return $retorno;                    //Retorna array
    }

    /**Método que atualiza dados de e-mails
     * @param Model $modelo dona dos e-mail
     * @param array $emails relação de e-mails
     */
    public static function atualizar(Model $modelo, $emails)
    {
        foreach ($modelo->emails as $i => $email):  //Percorre relação de e-mails
            $email->update($emails[$i]);            //Altera dados
        endforeach;
    }

    /** Método que aumenta relação de e-mail
     * @param Model $modelo dona dos e-mails
     * @param array $emails relação de e-mails
     */
    public static function aumentar(Model $modelo, $emails)
    {
        //Atualiza e-mails já existente, por meio do método atualizar (até o número já existente)
        self::atualizar($modelo, array_slice($emails, 0, $modelo->emails->count()));
        //Cria novos e-mails (a partir da quantidade existente)
        $modelo->emails()->createMany(array_slice($emails, $modelo->emails->count()));
    }

    /**Método que reduz relação de e-mails
     * @param Model $modelo dona dos e-mails
     * @param array $emails relação de e-mails
     */
    public static function diminuir(Model $modelo, $emails)
    {
        foreach ($emails as $i => $email):                                      //Percorre relação de e-mails passada
            $modelo->emails->get($i)->update($email);                           //Atualiza e-mails já existentes
        endforeach;
        Email::destroy(array_slice($modelo->emails->toArray(), count($emails)));//Exclui e-mail sobrando
    }
}