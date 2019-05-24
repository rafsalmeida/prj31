<?php

namespace App\Http\Controllers;

use App\Movimento;
use Illuminate\Http\Request;
use Illuminate\validation\Rule;
use App\AeronaveValor;
use App\User;

class MovimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ativo');
    }

    
    public function index()
    {
        $movimentos = Movimento::paginate(15);
        $title = "Lista de Movimentos";
        $pilotos = User::all();
        return view('movimentos.list', compact('movimentos','title','pilotos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Adicionar Movimento";
        return view("movimentos.add", compact("title"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has("cancel")) {
            return redirect()->action("MovimentoController@index");
        }
        
        $movimento = new Movimento();
       
        $request->validated();
        $movimento->fill($request->all());

        $piloto = User::find($request->piloto_id);

        $movimento->hora_aterragem = date('Y-m-d', strtotime($request->data)).' '.$request->hora_aterragem;
        $movimento->hora_descolagem = date('Y-m-d', strtotime($request->data)).' '.$request->hora_descolagem;
        $movimento->num_licenca_piloto = $piloto->num_licenca;
        $movimento->validade_licenca_piloto = $piloto->validade_licenca;
        $movimento->tipo_licenca_piloto = $piloto->tipo_licenca;
        $movimento->num_certificado_piloto = $piloto->num_certificado;
        $movimento->validade_certificado_piloto = $piloto->validade_certificado;
        $movimento->classe_certificado_piloto = $piloto->classe_certificado;
        $movimento->tempo_voo = ($request->conta_horas_fim-$request->conta_horas_inicio)*6;
        $aeronave_precos = AeronaveValor::whereMatricula($request->aeronave)->whereUnidadeContaHoras($request->conta_horas_fim-$request->conta_horas_inicio)->first();
        $movimento->preco_voo = $aeronave_precos->preco;
        $movimento->confirmado = 0;
        if ($request->natureza == 'I'){
            $instrutor = User::find($request->piloto_id);
            $movimento->num_licenca_instrutor = $instrutor->num_licenca;
            $movimento->validade_licenca_instrutor = $instrutor->validade_licenca;
            $movimento->tipo_licenca_instrutor = $instrutor->tipo_licenca;
            $movimento->num_certificado_instrutor = $instrutor->num_certificado;
            $movimento->validade_certificado_instrutor = $instrutor->validade_certificado;
            $movimento->classe_certificado_instrutor = $instrutor->classe_certificado;
        }

        if($request->conta_horas_inicio<5){

        }

        $movimento->save();

        return redirect()
                 ->action("MovimentoController@index")            
                 ->with("success", "Movimento adicionado corretamente");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movimento  $movimento
     * @return \Illuminate\Http\Response
     */
    public function show(Movimento $movimento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movimento  $movimento
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Editar Movimento";
        $movimento = Movimento::findOrFail($id);
        return view("movimentos.edit", compact("title", "movimento"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movimento  $movimento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validar e dar store na bd

        if ($request->has('cancel')) {
            return redirect()->action('MovimentoController@index');
        }

        $request->validated();
            
        $movimento = Movimento::findOrFail($id);
        $movimento->fill($request->all());

        $movimento->save();
        return redirect()
                ->action('MovimentoController@index')
                ->with('success', 'Movimento editada corretamente');

        

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movimento  $movimento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movimento $movimento)
    {
        //
    }
}
