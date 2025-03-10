<?php
 
 namespace App\Http\Controllers;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = FAQ::all();
        return $faqs;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question' => 'required|string',
            'answer' => 'nullable|string',
 
        ]);

        $faq = FAQ::create($validatedData);

        return response()->json([
            'message' => 'FAQ created successfully!',
            'data' => $faq,
        ], 201);
    }

    public function show($id)
    {
        // Find the vendor level by ID
        $faq = FAQ::find($id);

        // If vendor level not found, return a 404 response
        if (!$faq) {
            return response()->json([
                'message' => 'FAQ not found',
            ], 404);
        }

        // Return the found vendor level
        return response()->json($faq);
    }


    public function update(Request $request, $id)
{
    $faq = FAQ::find($id);

    if (!$faq) {
        return response()->json([
            'message' => 'FAQ not found',
        ], 404);
    }

    $validatedData = $request->validate([
       'question' => 'required|string',
        'answer' => 'nullable|string',
    ]);

    $faq->update($validatedData);

    return response()->json([
        'message' => 'FAQ updated successfully!',
        'data' => $faq,
    ]);
}
public function destroy($id)
{
    $faq = FAQ::find($id);

    if (!$faq) {
        return response()->json([
            'message' => 'FAQ not found',
        ], 404);
    }

    $faq->delete();

    return response()->json([
        'message' => 'FAQ deleted successfully!',
        'data' => $faq,
    ]);
}
}
