<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use App\Models\Client;
use Fpdf\Fpdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Budget::all();
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    static public function store(StoreBudgetRequest $request)
    {

        try {
            // Validar
            $validated = $request->validated();

            $newBudget = new Budget($validated);

            $newBudget->save();


            return response()->json(['messsage' => "New Budget Created", $newBudget], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Budget not found'], 404); // Respuesta clara si no se encuentra el presupuesto
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500); // Otros errores
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        try {
            return $budget;
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        try {

            // Validar

            $validated = $request->validated();

            $budget->update($validated);


            return response()->json(['messsage' => " Budget Updated", "updatedBudget" => $budget], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Budget not found'], 404); // Respuesta clara si no se encuentra el presupuesto
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500); // Otros errores
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    static public function destroy(Budget $budget)
    {
        try {
            // Si es el ultimo presupuesto del cliente, eliminar el cliente

            if ($budget->client->budgets->count() === 1) {
                Client::removeClient($budget->client);
            }
            $budget->delete();


            return response()->json(['message' => 'Deleted'], 200);
        } catch (\Throwable $th) {

            return response()->json(['error' => 'An error occurred'], 500);
        }
    }


    /**
     * Generate a PDF for a budget.
     */

    public function generatePdf($id)
    {
        try {
            define('FPDF_FONTPATH', storage_path('app/fonts/'));
            // Obtener el presupuesto o factura
            $budget = Budget::findOrFail($id);
            $user = Auth::user();
            // Crear una instancia de FPDF
            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();

            //Fuentes

            $pdf->AddFont('Montserrat', '', ('Montserrat-VariableFont_wght.php'));
            $pdf->AddFont('Montserrat', 'B', ('Montserrat-VariableFont_wght.php'));
            $pdf->AddFont('Lato', '', ('Lato-Regular.php'));

            // Agregar la información de la empresa (o la información correspondiente)

            $pdf->SetFont('Montserrat', "B",  12);
            $pdf->Cell(0, 10, $user?->company_name ?? 'My Company', 0, 1, 'C');
            $pdf->SetFont('Montserrat', 'B', 10);
            // $pdf->Cell(0, 10, 'Direccion: Calle Ficticia 123', 0, 1, 'C');
            // $pdf->Cell(0, 10, 'Telefono: +123456789', 0, 1, 'C');
            $pdf->Ln(10);

            // Información de la factura
            $pdf->Cell(0, 10, 'Budget : ' . $budget->id, 0, 1, 'C');
            $pdf->Cell(0, 10, 'Date: ' . now()->format('d/m/Y'), 0, 1, 'C');
            $pdf->Ln(10);

            // Información del cliente
            $pdf->SetFont('Lato', '', 12);
            ($budget->client !== null ? $pdf->Cell(0, 10, 'Client: ' . $budget->client->name, 0, 1, 'L') : "");
            $pdf->SetFont('Arial', '', 10);

            $pdf->Ln(10);

            // Encabezado de la tabla
            $pdf->SetFont('Lato', '', 10);
            $pdf->Cell(40, 10, 'Quantity', 1);
            $pdf->Cell(90, 10, 'Description', 1);
            $pdf->Cell(30, 10, 'Price', 1);
            $pdf->Cell(30, 10, 'Subtotal ', 1);

            $pdf->Ln();

            // Datos de los productos (Asegúrate de tener los productos en tu modelo o ajusta según tus datos)
            $pdf->SetFont('Lato', '', 10);
            $contentArray = json_decode($budget->content);
            $total = 0;
            foreach ($contentArray as $content) {
                $pdf->Cell(40, 10, $content->quantity, 1);
                $pdf->Cell(90, 10, $content->description, 1);
                $pdf->Cell(30, 10, $content->cost . " $", 1);
                $total += $content->quantity * $content->cost;
                $pdf->Cell(30, 10, number_format($content->quantity * $content->cost, 2) . " $", 1);
                $pdf->Ln();
            }

            $total = $total - ($total * $budget->discount / 100);
            $total = $total + ($total * $budget->taxes / 100);

            $pdf->SetFont('Lato', '', 10);
            $pdf->Cell(190, 10, 'Discount: ' . number_format($budget->discount, 2) . " %", 1, 0, 'R');
            $pdf->Ln(10);
            $pdf->Cell(190, 10, 'Taxes: ' . number_format($budget->taxes, 2) . " %", 1, 0, 'R');
            $pdf->Ln(10);
            // Total
            $pdf->Cell(190, 10, 'Total: ' . number_format($total, 2) . " $", 1, 0, 'R');
            $pdf->Ln(10);

            if (request()->query('download') === 'true') {
                return response($pdf->Output('', 'S'), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="budget_' . $budget->id . '.pdf"');
            }

            // Generar y devolver el PDF
            // Enviar el PDF al navegador para que lo abra en una nueva ventana
            return response($pdf->Output('S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="budget_' . $budget->id . '.pdf"');
        } catch (\Throwable $th) {

            return response()->json(['error' => 'An error occurred', dd($th)], 500);
        }
    }
}
