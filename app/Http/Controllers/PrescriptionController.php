<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\PrescriptionImage;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PrescriptionController extends Controller
{
    public function createPrescriptions()
    {
        return view('user.create-prescription');
    }

    public function storePrescriptions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'delivery_time' => [
                'required',
                'after: ' . Carbon::now()->addHours(2),
            ],
            'note' => 'nullable|string|max:500',
            'images.*' => 'image|max:500',
            'images' => 'required|array|min:1|max:5',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
        }

        $user = auth()->user();

        $prescription = Prescription::create([
            'user_id' => auth()->user()->id,
            'note' => $request->input('note'),
            'address' => $request->input('address'),
            'delivery_time' => $request->input('delivery_time'),
        ]);

        if ($request->hasFile('images')) {
            // Get the array of files
            $images = $request->file('images');

            // $images = $request->file('images');
            foreach ($images as $image) {
                try {
                    $fileName = $this->saveFile($image, '/images', 'image_'); // Generate a unique filename for the image
                    $imageUrl = "http://127.0.0.1:8000/images/$fileName"; // Create the URL for the image
                    $path[] = $imageUrl;

                } catch (\Throwable $th) {
                    return response()->json(['error' => 'Image upload failed.'], 500);
                }

                $prescriptionImage = new PrescriptionImage;
                $prescriptionImage->prescription_id = $prescription->id;
                $prescriptionImage->image_url = $imageUrl;
                $prescriptionImage->save();
            }
        }
        return redirect()->route('prescriptions.index')->with('success', 'Prescription created successfully.');
    }

    public function saveFile($file, $path, $startWith)
    {
        File::makeDirectory($path, $mode = 0777, true, true);
        $extension = $file->getClientOriginalExtension();
        $fileName = $startWith . rand(11111, 99999) . '' . time() . '' . rand(11111, 99999) . '.' . $extension;
        $file->move(public_path() . $path, $fileName);
        return $fileName;
    }

    public function show()
    {
        $user = Auth::user();
        $prescriptions = Prescription::with('user');

        if ($user->type == 'user') {
            $prescriptions = $prescriptions->where('user_id', $user->id);
        }
        $prescriptions = $prescriptions->get();

        return view('view-prescriptions', compact('prescriptions'));
    }
}
