<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use App\Models\Client;
use Exception;
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
            throw new Exception('Budget not found', 404); // Respuesta clara si no se encuentra el presupuesto
            return response()->json(['error' => 'Budget not found'], 404); // Respuesta clara si no se encuentra el presupuesto
        } catch (\Throwable $th) {
            throw new Exception('An error occurred', 500); // Otros errores
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
            throw new Exception('An error occurred' . $th, 500); // Otros errores
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
            $pdf->SetMargins(10, 10, 0);
            $pdf->AddPage();
            define('EURO', chr(128));
            //Fuentes

            $pdf->AddFont('Montserrat', '', ('Montserrat-VariableFont_wght.php'));
            $pdf->AddFont('Montserrat', 'B', ('Montserrat-VariableFont_wght.php'));
            $pdf->AddFont('Lato', '', ('Lato-Regular.php'));

            // Agregar la información de la empresa (o la información correspondiente)
            $pdf->SetFont('Lato', "", 12);

            // Calculate center position for text alignment
            $pageWidth = $pdf->GetPageWidth();
            $companyName = $user?->company_name ?? 'My Company';
            $textWidth = $pdf->GetStringWidth($companyName);
            // Position image and text on the left side with a reduced top margin
            $leftMargin = 10; // Space from the left edge
            $topMargin = 8; // Reduced from 20 to move content up
            $imageWidth = 15;
            $spacing = 5; // Space between image and text

            // Set position for proper vertical alignment
            $pdf->SetX($leftMargin);

            // Draw image first on the left side
            if ($user?->profile_photo_path) {
                $pdf->Image(($user->profile_photo_path),
                    $leftMargin,
                    $topMargin,
                    $imageWidth,
                    $imageWidth
                );
            }

            // Draw company name to the right of the image
            $pdf->SetY($topMargin + 5); // Adjusted vertical position
            $pdf->SetX($leftMargin + $imageWidth + $spacing);
            $pdf->Cell($textWidth, 10, $companyName, 0, 1, 'L');




            $pdf->SetFont('Lato', "", 20);
            // Información del presupuesto
            $pdf->Cell(0, 15, 'Presupuesto ', 0, 1, 'C');
            $pdf->SetFont('Montserrat', "", 8);
            $pdf->Cell(0, 0, 'Fecha: ' . now()->format('d/m/Y'), 0, 1, 'C');
            $pdf->Ln(10);

            // Información del cliente
            $pdf->SetFont('Lato', '', 12);
            ($budget->client !== null ? $pdf->Cell(0, 10, 'Cliente: ' . $budget->client->name, 0, 1, 'L') : "");
            $pdf->SetFont('Lato', '', 8);
            ($budget->client !== null ? $pdf->Cell(0, 8, $budget->client->email, 0, 1, 'L') : "");
            $pdf->SetFont('Arial', '', 10);

            $pdf->Ln(10);

            // Encabezado de la tabla
            $pdf->SetFont('Lato', '', 10);
            $pdf->Cell(25, 10, 'Cantidad', 'B');
            $pdf->Cell(90, 10, 'Descripcion', 'B');
            $pdf->Cell(30, 10, 'Precio', 'B');
            $pdf->Cell(30, 10, 'Subtotal ', 'B');

            $pdf->Ln();

            // Datos de los productos (Asegúrate de tener los productos en tu modelo o ajusta según tus datos)
            $pdf->SetFont('Lato', '', 10);
            $contentArray = json_decode($budget->content);
            $total = 0;
            foreach ($contentArray as $content) {
                $pdf->Cell(25, 10, $content->quantity, 0);
                $description = $this->normalizeBudgetContent($content->description, 45);
                $pdf->Cell(90, 10, $description, 0);
                $pdf->Cell(30, 10, $content->cost . " " . EURO, 0);
                $total += $content->quantity * $content->cost;
                $pdf->Cell(30, 10, $this->formatMoney($content->quantity * $content->cost) . " " . EURO, 0);
                $pdf->Ln();
            }
            $subtotal = $total;
            // Calculate discount amount
            $discountAmount = $subtotal * ($budget->discount / 100);
            // Calculate discounted total
            $discountedTotal = $subtotal - $discountAmount;
            // Calculate tax amount
            $taxAmount = $discountedTotal * ($budget->taxes / 100);
            // Calculate final total
            $total = $discountedTotal + $taxAmount;

            $pdf->SetFont('Arial', '', 10);
            // Move to the bottom of the page
            $pdf->SetY(-65);
            $pdf->SetX(2);

            // Subtotal
            $pdf->Cell(190, 10, 'Subtotal: ' . $this->formatMoney($subtotal) . " " . EURO, 0, 0, 'R');
            $pdf->Ln(5);
            $pdf->SetX(2);

            // Only show discount if greater than 0
            if ($budget->discount > 0) {
                $pdf->Cell(190, 10, 'Descuento: ' . number_format($budget->discount) . " % (-" . $this->formatMoney($discountAmount) . " " . EURO . ")", 0, 0, 'R');
                $pdf->Ln(5);
                $pdf->SetX(2);
            }
            $pdf->Cell(190, 10, 'Impuestos: ' . number_format($budget->taxes) . " % (" . $this->formatMoney($taxAmount) . " " . EURO . ")", 0, 0, 'R');
            $pdf->Ln(10);
            $pdf->SetX(2);
            $pdf->SetFont('Arial', '', 12);
            // Total
            $pdf->Cell(190, 12, 'Total: ' . $this->formatMoney(+$total) . " " . EURO, 0, 0, 'R');
            $pdf->Ln(5);


            $pdf->SetY(-38); // Position 40mm from bottom of page
            $pdf->SetFont('Montserrat', 'B',  8);
            //Footer
            $pdf->SetY(-29);
            $pdf->Cell(0, 5, 'Este presupuesto tiene validez por 30 dias ', 0, 1, 'C');
            $pdf->SetFont('Montserrat', 'B', 5); // Smaller font for Budget App
            $pdf->Cell(0, 3, 'Budget App', 0, 1, 'C'); // Note: To remove margins, add $pdf->SetMargins(0,0,0); after creating the PDF object

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
            throw new Exception('An error occurred generating the budget' . $th, 500); // Otros errores
            return response()->json(['error' => 'An error occurred', dd($th)], 500);
        }
    }

    /**
     * Normalize budget content by converting accented characters to non-accented equivalents
     * and adding line breaks at specified character limits
     *
     * @param string $content The content to normalize
     * @param int $maxCharsPerLine Maximum characters per line before adding a line break
     * @return string Normalized content
     */
    public function normalizeBudgetContent($content, $maxCharsPerLine = 120)
    {
        // Convert to UTF-8
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');

        // Convert accented characters to their non-accented equivalents
        $search = ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'Ü', 'à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù'];
        $replace = ['a', 'e', 'i', 'o', 'u', 'n', 'u', 'A', 'E', 'I', 'O', 'U', 'N', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];
        $content = str_replace($search, $replace, $content);



        // Insert line breaks when text exceeds the character limit
        if (mb_strlen($content) > $maxCharsPerLine) {
            $content = mb_substr($content, 0, $maxCharsPerLine) . '...';
        }

        // Remove any leading or trailing whitespace
        return $content;
    }


    /**
     * Format a number as money with two decimal places and comma as decimal separator
     *
     * @param mixed $value The value to format
     * @return string Formatted money string
     */
    public function formatMoney($value)
    {
        $number = (float)$value;
        if (is_nan($number)) {
            return $value;
        }
        return number_format($number, 2, ',', '.');
    }
}
