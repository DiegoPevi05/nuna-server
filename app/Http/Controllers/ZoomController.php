<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\Models\TokenZoom;
use Illuminate\Support\Facades\Log;
use App\Services\LogService;

class ZoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function callBackZoomUri(){

        $tokenDb = TokenZoom::orderBy('created_at', 'desc')->first();

        if(!$tokenDb){

            $return_message = 'Primero ingresa las credenciales de la cuenta de Zoom.';

            $this->logService->Log(4,$return_message);

            return redirect()->route('zooms.index')->with('logError', $return_message);

        }else if($tokenDb->CLIENT_ID_ZOOM == null || $tokenDb->CLIENT_SECRET_ZOOM == null){

            $return_message = 'Primero ingresa las credenciales de la de Zoom.';

            $this->logService->Log(4,$return_message);

            return redirect()->route('zooms.index')->with('logError', $return_message);
        }

        try {
            $client = new GuzzleHttpClient(['base_uri' => 'https://zoom.us']);
          
            $response = $client->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic ". base64_encode($tokenDb->CLIENT_ID_ZOOM.':'.$tokenDb->CLIENT_SECRET_ZOOM)
                ],
                'form_params' => [
                    "grant_type" => "authorization_code",
                    "code" => $_GET['code'],
                    "redirect_uri" => env('REDIRECT_URI_ZOOM')
                ],
            ]);
          
            $token = json_decode($response->getBody()->getContents(), true);
            $tokenDb->update([
                'access_token' => json_encode($token),
            ]);

            $return_message = 'Token creado exitosamente.';

            $this->logService->Log(1,$return_message);

            return redirect()->route('zooms.index')->with('logSuccess', $return_message);

        } catch(Exception $e) {

            $return_message = $e->getMessage();

            $this->logService->Log(4,$return_message);

            return redirect()->route('zooms.index')->with('logError', $return_message);
        }
    }

    public function index()
    {
        $credentials = TokenZoom::orderBy('created_at', 'desc')
            ->select('CLIENT_ID_ZOOM', 'CLIENT_SECRET_ZOOM')
            ->first();
        return view('zooms.index', compact('credentials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'CLIENT_ID' => 'required|string',
            'CLIENT_SECRET' => 'required|string',
        ], [
            'CLIENT_ID.required' => 'El CLIENT ID  es obligatorio si quieres actualizarlo.',
            'CLIETN_SECRET.required' => 'El CLIENT SECRET  es obligatorio si quieres actualizarlo.',
            'CLIENT_ID.string' => 'El CLIENT ID debe ser un texto.',
            'CLIENT_SECRET.string' => 'El CLIENT_SECRET debe ser un texto.',
        ]);

        $tokenDb = TokenZoom::orderBy('created_at', 'desc')->first();

        if($tokenDb){
            $tokenDb->update([
                'CLIENT_ID_ZOOM' => $validatedData['CLIENT_ID'],
                'CLIENT_SECRET_ZOOM' => $validatedData['CLIENT_SECRET']
            ]); 
        }else{
            $tokenzoom = TokenZoom::create([
                'CLIENT_ID_ZOOM' => $validatedData['CLIENT_ID'],
                'CLIENT_SECRET_ZOOM' => $validatedData['CLIENT_SECRET'],
            ]);
            
        }

        $return_message = 'Claves de ZOOM actualizadas exitosamente.';

        $this->logService->Log(1,$return_message);

        return redirect()->route('zooms.index')->with('logSuccess', $return_message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
