<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscountCode;
use App\Services\LogService;

class AdminDiscountCodeController extends Controller
{

    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function searchByName(Request $request)
    {
        $name = strtolower($request->input('name')); // Convert input name to lowercase

        $users = DiscountCode::whereRaw('LOWER(name) like ?', ["%$name%"])
            ->where('status', 'active') // Filter by role = 'USER'
            ->where('quantity_discounts', '>', 0)
            ->select('id', 'name','discount')
            ->limit(5)
            ->get();

        return response()->json($users);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $discountCodeQuery = DiscountCode::query();

        // Check if the name search parameter is provided
        $name = $request->query('name');
        if ($name) {
            // Apply the name filter to the query
            $discountCodeQuery->where('name', 'like', '%' . $name . '%');
        }

        // Paginate the categories
        $discountcodes = $discountCodeQuery->paginate(10);

        // Get the requested page from the query string
        $page = $request->query('page');

        // Redirect to the first page if the requested page is not valid
        if ($page && ($page < 1 || $page > $discountcodes->lastPage())) {
            return redirect()->route('discountcodes.index');
        }

        $searchParam = $name ? $name : '';

        // Return a view or JSON response as desired
        return view('discountcodes.index', compact('discountcodes', 'searchParam'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('discountcodes.create');
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
            'name' => 'required|max:25|unique:discount_codes,name',
            'discount' => 'required|numeric|min:0',
            'quantity_discounts' => 'required|numeric|min:1',
            'status' => 'required|in:active,inactive|max:25',
            'expired_date' => 'required|date|after:today',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no debe exceder los 25 caracteres.',
            'name.unique' => 'El nombre ya está en uso.',
            'discount.required' => 'El descuento es obligatorio.',
            'discount.numeric' => 'El descuento debe ser numérico.',
            'discount.min' => 'El descuento debe ser mayor que 0.',
            'quantity_discounts.required' => 'La cantidad de descuentos es obligatoria.',
            'quantity_discounts.numeric' => 'La cantidad de descuentos debe ser numérica.',
            'quantity_discounts.min' => 'La cantidad de descuentos debe ser al menos 1.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser "activo" o "inactivo".',
            'status.max' => 'El estado no debe exceder los 25 caracteres.',
            'expired_date.required' => 'La fecha de vencimiento es obligatoria.',
            'expired_date.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'expired_date.after' => 'La fecha de vencimiento debe ser mayor que la fecha actual.',
        ]);


        $discountcode = DiscountCode::create([
            'name' => $validatedData['name'],
            'discount' => $validatedData['discount'],
            'quantity_discounts' => $validatedData['quantity_discounts'],
            'status' => $validatedData['status'],
            'expired_date' => $validatedData['expired_date']
        ]);

        $return_message = 'Codigo de Descuento creado exitosamente.';

        $this->logService->Log(1,$return_message);

        return redirect()->route('discountcodes.index')->with('logSuccess', $return_message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DiscountCode $discountcode)
    {
        return view('discountcodes.show', compact('discountcode'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DiscountCode $discountcode)
    {
        return view('discountcodes.edit', compact('discountcode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DiscountCode $discountcode)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:25|unique:discount_codes,name,' . $discountcode->id,
            'discount' => 'required|numeric|min:0',
            'quantity_discounts' => 'required|numeric|min:1',
            'status' => 'required|in:active,inactive|max:25',
            'expired_date' => 'required|date|after:today',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no debe exceder los 25 caracteres.',
            'name.unique' => 'El nombre ya está en uso.',
            'discount.required' => 'El descuento es obligatorio.',
            'discount.numeric' => 'El descuento debe ser numérico.',
            'discount.min' => 'El descuento debe ser mayor que 0.',
            'quantity_discounts.required' => 'La cantidad de descuentos es obligatoria.',
            'quantity_discounts.numeric' => 'La cantidad de descuentos debe ser numérica.',
            'quantity_discounts.min' => 'La cantidad de descuentos debe ser al menos 1.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser "activo" o "inactivo".',
            'status.max' => 'El estado no debe exceder los 25 caracteres.',
            'expired_date.required' => 'La fecha de vencimiento es obligatoria.',
            'expired_date.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'expired_date.after' => 'La fecha de vencimiento debe ser mayor que la fecha actual.',
        ]);


        $discountcode->update([
            'name' => $validatedData['name'],
            'discount' => $validatedData['discount'],
            'quantity_discounts' => $validatedData['quantity_discounts'],
            'status' => $validatedData['status'],
            'expired_date' => $validatedData['expired_date']
        ]);

        $return_message = 'Codigo de Descuento actualizado exitosamente.';

        $this->logService->Log(1,$return_message);

        return redirect()->route('discountcodes.index')->with('logSuccess', $return_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiscountCode $discountcode)
    {

        $return_message = 'Codigo de Descuento borrado exitosamente.';

        $this->logService->Log(1,$return_message);

        $discountcode->delete();
        // Return a success response or redirect as desired
        return redirect()->route('discountcodes.index')->with('logSuccess', $return_message);
    }
}
