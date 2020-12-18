<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Probe;

class ProbeController extends Controller
{
    /* COORDENADAS LIMITE */
    private $initialLimitX;
    private $finalLimitX;
    private $initialLimitY;
    private $finalLimitY;

    /* Inicializa as coordenadas limite */
    public function __construct() {
        $this->initialLimitX = env('LIMITE_INICIAL_X');
        $this->finalLimitX   = env('LIMITE_FINAL_X');
        $this->initialLimitY = env('LIMITE_INICIAL_Y');
        $this->finalLimitY   = env('LIMITE_FINAL_Y');
    }

    /**
     * Retorna a posição atual da sonda
     * @return Json $coords
     */
    public function getCoords() {
        $probePosition = Probe::select('direction','xaxis','yaxis')->first();
        return [
                'x' => $probePosition->xaxis,
                'y' => $probePosition->yaxis,
                'face' => $probePosition->direction
            ];
    }

    /**
     * Envia a sonda para a posição inicial
     * @return String
     */
    public function goToOrigin() {
        $probe = Probe::first();

        if(!$probe) 
            return ['erro' => 'Ops! Nenhuma sonda encontrada.'];

        $probe->direction = 'D';
        $probe->xaxis = 0;
        $probe->yaxis = 0;
        $probe->save();

        return ['mensagem' => 'A sonda retornou à posição inicial.'];
    }

    /**
     * Move a sonda para a posição solicitada
     * @param Array $request
     * @return Json $coords
     */
    public function moveProbe(Request $request) {
        if(!is_array($request->movimentos)) // a requisição deve ser um array com os movimentos
            return ['erro' => 'A requisição deve ser um array.'];

        foreach($request->movimentos as $k=>$val) {
            if(!$this->isValidCommand($val)) return ['erro' => 'O comando fornecido "'.$val.'" não é um comando válido.'];
            
            $executeMovement = $this->executeMovement($val);

            if(!$executeMovement) return ['erro' => 'Um movimento inválido foi detectado. Tente novamente.'];
        }

        return response()->json( $this->getCoords() );
    }


    /**
     * Executa o movimento da sonda
     * @param String
     * @return Bool
     */
    private function executeMovement($movement) {
        if($movement == 'M')
            $execute = $this->stepForward();
        else
            $execute = $this->setProbeDirection($movement);

        return $execute;
    }

    /**
     * Ajusta a direção atual da sonda
     * @return bool
     */
    private function setProbeDirection($movement) {
        $probeDirection = Probe::select('id','direction')->first();

        switch ($probeDirection->direction) {
            case 'D':
                    if($movement == 'GE') $probeDirection->direction = 'C';
                    else $probeDirection->direction = 'B';
                break;
            
            case 'C':
                    if($movement == 'GE') $probeDirection->direction = 'E';
                    else $probeDirection->direction = 'D';
                break;
            
            case 'E':
                    if($movement == 'GE') $probeDirection->direction = 'B';
                    else $probeDirection->direction = 'C';
                break;
            
            case 'B':
                    if($movement == 'GE') $probeDirection->direction = 'D';
                    else $probeDirection->direction = 'E';
                break;
            
            default:
                # 
                break;
        }

        $probeDirection->save();

        return $probeDirection;
    }


    /**
     * Retorna a direção atual da sonda
     * @return String
     */
    private function getProbeDirection() {
        $probeDirection = Probe::select('direction')->first();

        return $probeDirection->direction;
    }

    /**
     * Executa o movimento da sonda
     * @return 
     */
    private function stepForward() {
        $probeStep = Probe::select('id','xaxis','yaxis')->findOrFail(1);

        $direction = $this->getProbeDirection(); // pega a direção atual da sonda
        
        switch ($direction) {
            case 'D':
                    if($probeStep->xaxis >= $this->finalLimitX) return false;
                    $probeStep->xaxis++;
                break;
            
            case 'C':
                    if($probeStep->yaxis >= $this->finalLimitY) return false;
                    $probeStep->yaxis++;
                break;
            
            case 'E':
                    if($probeStep->xaxis <= $this->initialLimitX) return false;
                    $probeStep->xaxis--;
                break;
            
            case 'B':
                    if($probeStep->yaxis <= $this->finalLimitY) return false;
                    $probeStep->yaxis--;
                break;
            
            default:
                    #
                break;
        }
        $probeStep->save();
        
        return $probeStep;
    }


    /**
     * Valida se o comando enviado é válido
     * @param String
     * @return bool
     */
    private function isValidCommand($command) {
        return in_array($command, array('M','GD','GE'));
    }
}
