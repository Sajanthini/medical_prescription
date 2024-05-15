<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quotation;
use App\Mail\QuotationMail;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\QuotationItem;
use App\Models\PrescriptionImage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NotifyQuotationStatus;

class QuotationController extends Controller
{
    public function createQuotation($id)
    {
        $prescriptionImages = PrescriptionImage::where('prescription_id', $id)->get();
        return view('pharmacy-user.create-quotation', compact('prescriptionImages', 'id')); //for showing a new quatation form,
    }

    public function storeQuotation(Request $request, $id)
    {
        $prescription = Prescription::find($id);
        if (!$prescription) {
            return response()->json(['error' => 'Prescription not found'], 404);
        }
        $userId = $prescription->user_id;

        // Check if required data is present in the request
        if (!$request->has('total') || !$request->has('cartData')) {
            return response()->json(['error' => 'Missing required data'], 400);
        }
        // Create a new Quotation object and fill its fields with data from the request.
        $quotation = new Quotation;
        $quotation->total = $request->input('total');
        $quotation->user_id = $userId;
        $quotation->prescription_id = $id;
        $quotation->save();

        // Loop through each item in the cart data from the request, and create a new QuotationItem object for each one.
        foreach ($request->input('cartData') as $item) {
            $quotationItem = new QuotationItem;
            $quotationItem->drug = $item['name'];
            $quotationItem->amount = $item['price'];
            $quotationItem->quantity = $item['quantity'];
            $quotationItem->quotation_id = $quotation->id;
            $quotationItem->save();
        }
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Quotation created successfully.:)'], 200);
        }
        // Get the User object associated with the prescription owner, and send them a notification and email.
        $user = User::find($userId);
        
        // Log before sending notification and mail
        Log::info("About to notify user and send email.", ['user_id' => $user->id, 'email' => $user->email]);

        $quotation->status = "created";
        $user->notify(new NotifyQuotationStatus($quotation, $user));
        Mail::to($user->email)->send(new QuotationMail($user, $request->total));

        return redirect()->route('prescriptions.index')->with('success', 'Quotation created successfully.');
        
        // return response()->json(["message" => "Quotation created", "data" => $quotation]);
    }

    public function showQuotation()
    {
        $user = Auth::user();
        $quotations = Quotation::with('user');

        if ($user->type == 'user') {
            $quotations = $quotations->where('user_id', $user->id);
        }
        $quotations = $quotations->get();
        return view('view-quotations', compact('quotations'));
    }

    public function Item_Find($id)
    {
        $quotationItems = QuotationItem::where('quotation_id', $id)->get();
        return response()->json($quotationItems);
    }

    public function status_update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:quotations,id',
            'status' => 'required|string',
        ]);

        try {
            $quotation = Quotation::find($request->id);

            $quotation->status = $request->status;
            $quotation->save();

            if (Auth::user()->type == 'user') {
                $pharmacyUsers = User::where('type', 'pharmacy')->get();
                foreach ($pharmacyUsers as $pharmacyUser) {
                    $pharmacyUser->notify(new NotifyQuotationStatus($quotation, Auth::user()));
                }
            } else {
                $user = User::find($quotation->user_id);
                $user->notify(new NotifyQuotationStatus($quotation, $user));
            }

            return response()->json("Status updated successfully", 200);
            // return  redirect()->route('home');
        } catch (\Exception $e) {
            Log::error("Error updating quotation status: " . $e->getMessage());
            return response()->json("Internal Server Error", 500);
        }
    }

}
