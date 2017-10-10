<?php
/**
 * Created by PhpStorm.
 * User: Wilder
 * Date: 01/10/2017
 * Time: 10:30
 */

namespace caritas\Http\Helpers;

use caritas\Telefone;
use Illuminate\Database\Eloquent\Model;

class TelefoneHelper
{
    /**Método para filtrar relação de telefones removendo posições vazias
     * @param array|null $telefones relação de telefones passada
     * @return array relação de telefones filtrada
     */
    public static function filtar(array $telefones = null)
    {
        $retorno = array();                                                         //Cria variável para retornar com array vazio
        if ($telefones):                                                            //Verifica se relação de telefones não é nula
            foreach ($telefones as $telefone):                                      //Percorre relação de telefones
                if ($telefone['ddd'] && $telefone['numero'] && $telefone['tipo']):  //Verifica se telefone possui atributos ddd, numero e tipo
                    $retorno[] = $telefone;                                         //Passa telefone para array de retorno
                endif;
            endforeach;
        endif;
        return $retorno;                                                            //Retorna array
    }

    /**Método que atualiza dados de telefones
     * @param Model $modelo dona dos telefones
     * @param array $telefones relação de telefones
     */
    public static function atualizar(Model $modelo, $telefones)
    {
        foreach ($modelo->telefones as $i => $telefone)://Percorre relação de telefones
            $telefone->update($telefones[$i]);          //Altera dados
        endforeach;
    }

    /**Método que aumenta relação de telefones
     * @param Model $imodelo dona dos telefones
     * @param array $telefones relação de telefones
     */
    public static function aumentar(Model $modelo, $telefones)
    {
        //Atualiza telefones já existente, por meio do método atualizar (até o número já existente)
        self::atualizar($modelo, array_slice($telefones, 0, $modelo->telefones->count()));
        //Cria novos telefones (a partir da quantidade existente)
        $modelo->telefones()->createMany(array_slice($telefones, $modelo->telefones->count()));
    }

    /**Método que reduz relação de telefones
     * @param Model $modelo dona dos telefones
     * @param array $telefones relação de telefenes
     */
    public static function diminuir(Model $modelo, $telefones)
    {
        foreach ($telefones as $i => $telefone):                                            //Percorre relação de telefones passada
            $modelo->telefones->get($i)->update($telefone);                                 //Atualiza telefones já existentes
        endforeach;
        Telefone::destroy(array_slice($modelo->telefones->toArray(), count($telefones)));   //Exclui telefones sobrando
    }
}